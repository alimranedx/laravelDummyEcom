<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            overflow-x: hidden;
        }

        #sidebar {
            min-height: 100vh;
            background-color: #212529;
        }

        #sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
            padding: 0.75rem 1rem;
            border-left: 3px solid transparent;
        }

        #sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }

        #sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: #0d6efd;
        }

        #sidebar .sidebar-heading {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.5);
            padding: 0.75rem 1rem;
            margin-top: 1rem;
        }

        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: bold;
            color: #fff;
            padding: 1rem;
            text-decoration: none;
            display: block;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand:hover {
            color: #fff;
        }

        #content {
            min-height: 100vh;
        }

        .top-navbar {
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 0.75rem 1.5rem;
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
                position: fixed;
                z-index: 1000;
                width: 250px;
                transition: margin 0.3s;
            }

            #sidebar.show {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                        Admin Panel
                    </a>
                    @php
                        $user = auth()->user();
                        if ($user->isSuperAdmin()) {
                            $permittedModules = \App\Models\Module::with(['subModules' => function($q) {
                                $q->orderBy('sequence');
                            }])->orderBy('sequence')->get();
                        } else {
                            $permittedModules = \App\Models\Module::with(['subModules' => function($query) use ($user) {
                                $query->whereExists(function ($q) use ($user) {
                                    $q->select(DB::raw(1))
                                      ->from('pages')
                                      ->join('role_pages', 'pages.id', '=', 'role_pages.page_id')
                                      ->join('model_has_roles', 'role_pages.role_id', '=', 'model_has_roles.role_id')
                                      ->whereColumn('pages.sub_module_id', 'sub_modules.id')
                                      ->where('model_has_roles.model_id', $user->id)
                                      ->where('model_has_roles.model_type', get_class($user));
                                })->orderBy('sequence');
                            }])
                            ->where('name', '!=', 'RBAC')
                            ->whereHas('subModules', function($query) use ($user) {
                                $query->whereExists(function ($q) use ($user) {
                                    $q->select(DB::raw(1))
                                      ->from('pages')
                                      ->join('role_pages', 'pages.id', '=', 'role_pages.page_id')
                                      ->join('model_has_roles', 'role_pages.role_id', '=', 'model_has_roles.role_id')
                                      ->whereColumn('pages.sub_module_id', 'sub_modules.id')
                                      ->where('model_has_roles.model_id', $user->id)
                                      ->where('model_has_roles.model_type', get_class($user));
                                });
                            })->orderBy('sequence')->get();
                        }

                        $isDashboardActive = request()->routeIs('admin.dashboard');
                    @endphp

                    <li class="nav-item mb-2">
                        <a class="nav-link d-flex align-items-center {{ $isDashboardActive ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    @foreach($permittedModules as $module)
                        @php
                            $isModuleActive = false;
                            $currentAction = request()->route()?->getActionName();
                            if ($currentAction) {
                                $currentController = ltrim(explode('@', $currentAction)[0] ?? '', '\\');
                                $isModuleActive = $module->subModules->contains('controller_name', $currentController);
                            }
                            $moduleId = Str::slug($module->name) . 'Submenu';
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center {{ $isModuleActive ? 'active' : '' }}" 
                               data-bs-toggle="collapse" href="#{{ $moduleId }}" role="button" 
                               aria-expanded="{{ $isModuleActive ? 'true' : 'false' }}" aria-controls="{{ $moduleId }}">
                                <i class="{{ $module->icon }} me-2"></i>
                                <span>{{ $module->display_name }}</span>
                                <i class="bi bi-chevron-down ms-auto small transition-icon"></i>
                            </a>
                            <div class="collapse {{ $isModuleActive ? 'show' : '' }} ms-3" id="{{ $moduleId }}">
                                <ul class="nav flex-column border-start border-secondary border-opacity-25 ms-2 ps-2 mt-1">
                                    @foreach($module->subModules as $subModule)
                                        @php
                                            $isSubActive = false;
                                            if ($currentAction) {
                                                $subController = ltrim(explode('@', $currentAction)[0] ?? '', '\\');
                                                $isSubActive = ($subController === $subModule->controller_name);
                                            }

                                            $routeName = 'admin.' . strtolower($subModule->name) . '.index';
                                            if (!Route::has($routeName)) {
                                                 if ($subModule->name == 'Admins') $routeName = 'admin.admin-management.index';
                                                 elseif ($subModule->name == 'Regular Users') $routeName = 'admin.user-management.index';
                                                 elseif ($subModule->name == 'Role Permission Association' || $subModule->name == 'Role Associations') $routeName = 'admin.role-permissions.index';
                                                 elseif ($subModule->name == 'Admin User Role' || $subModule->name == 'Admin User Roles') $routeName = 'admin.admin-user-roles.index';
                                                 elseif ($subModule->name == 'Dashboard') $routeName = 'admin.dashboard';
                                                 elseif ($subModule->name == 'Role') $routeName = 'admin.roles.index';
                                                 elseif ($subModule->name == 'Permission') $routeName = 'admin.permissions.index';
                                            }
                                            $subModuleUrl = Route::has($routeName) ? route($routeName) : '#';
                                        @endphp
                                        <li class="nav-item">
                                            <a class="nav-link py-1 {{ $isSubActive ? 'text-primary fw-bold' : 'opacity-75' }}" href="{{ $subModuleUrl }}">
                                                <i class="{{ $subModule->icon }} me-2"></i> {{ $subModule->display_name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    @endforeach

                    <ul class="nav flex-column mt-4">
                        <li class="nav-item">
                            <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link border-0 bg-transparent text-start w-100">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" id="content">
                <!-- Top navbar -->
                <div class="top-navbar d-flex justify-content-between align-items-center">
                    <button class="btn btn-link d-md-none" type="button"
                        onclick="document.getElementById('sidebar').classList.toggle('show')">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="ms-auto">
                        <span class="text-muted">Welcome, {{ Auth::user()->name }}</span>
                    </div>
                </div>

                <!-- Flash messages -->
                <div class="mt-3">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                </div>

                <!-- Page content -->
                <div class="py-4">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    @stack('scripts')
</body>

</html>