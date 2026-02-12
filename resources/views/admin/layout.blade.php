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
                    <ul class="nav flex-column">
                        @can('view admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                                    href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                        @endcan
                    </ul>

                    @if(auth()->user()->isSuperAdmin())
                        <div class="sidebar-heading px-3 mt-4 mb-1 text-uppercase fw-bold opacity-75">
                            <i class="bi bi-shield-lock me-1"></i> User Management
                        </div>
                        <ul class="nav flex-column mb-2">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->is('admin/admin-management*') || request()->is('admin/user-management*') ? 'active' : '' }}"
                                    data-bs-toggle="collapse" href="#userMgmtSubmenu" role="button"
                                    aria-expanded="{{ request()->is('admin/admin-management*') || request()->is('admin/user-management*') ? 'true' : 'false' }}"
                                    aria-controls="userMgmtSubmenu">
                                    <i class="bi bi-people-fill me-2"></i>
                                    <span>Accounts</span>
                                    <i class="bi bi-chevron-down ms-auto small transition-icon"></i>
                                </a>
                                <div class="collapse {{ request()->is('admin/admin-management*') || request()->is('admin/user-management*') ? 'show' : '' }} ms-3"
                                    id="userMgmtSubmenu">
                                    <ul
                                        class="nav flex-column border-start border-secondary border-opacity-25 ms-2 ps-2 mt-1">
                                        <li class="nav-item">
                                            <a class="nav-link py-1 opacity-75 {{ request()->routeIs('admin.admin-management.*') ? 'active text-primary fw-bold' : '' }}"
                                                href="{{ route('admin.admin-management.index') }}">
                                                <i class="bi bi-person-badge me-2"></i> Admins
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link py-1 opacity-75 {{ request()->routeIs('admin.user-management.*') ? 'active text-primary fw-bold' : '' }}"
                                                href="{{ route('admin.user-management.index') }}">
                                                <i class="bi bi-person me-2"></i> Users
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <!-- Role page association -->
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/roles*') || request()->is('admin/permissions*') || request()->is('admin/role-permissions*') || request()->is('admin/admin-user-roles*') ? 'active' : '' }}"
                                    data-bs-toggle="collapse" href="#roleSubmenu" role="button" aria-expanded="false"
                                    aria-controls="roleSubmenu">
                                    <i class="bi bi-shield-check"></i> Role & Association
                                    <i class="bi bi-chevron-down ms-auto small"></i>
                                </a>
                                <div class="collapse {{ request()->is('admin/roles*') || request()->is('admin/permissions*') || request()->is('admin/role-permissions*') || request()->is('admin/admin-user-roles*') ? 'show' : '' }} ms-3"
                                    id="roleSubmenu">
                                    <ul class="nav flex-column border-start border-secondary small">
                                        <li class="nav-item">
                                            <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'text-white fw-bold' : '' }}"
                                                href="{{ route('admin.roles.index') }}">
                                                <i class="bi bi-tags small me-1"></i> Role Management
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'text-white fw-bold' : '' }}"
                                                href="{{ route('admin.permissions.index') }}">
                                                <i class="bi bi-key small me-1"></i> Permission Management
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ request()->routeIs('admin.admin-user-roles.*') ? 'text-white fw-bold' : '' }}"
                                                href="{{ route('admin.admin-user-roles.index') }}">
                                                <i class="bi bi-person-badge small me-1"></i> Admin User Role
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ request()->routeIs('admin.role-permissions.*') ? 'text-white fw-bold' : '' }}"
                                                href="{{ route('admin.role-permissions.index') }}">
                                                <i class="bi bi-link-45deg small me-1"></i> Role Permission Association
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    @endif

                    <div class="sidebar-heading">Management</div>
                    <ul class="nav flex-column">
                        @can('manage brands')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}"
                                    href="{{ route('admin.brands.index') }}">
                                    <i class="bi bi-patch-check"></i> Brands
                                </a>
                            </li>
                        @endcan
                        @can('manage categories')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                                    href="{{ route('admin.categories.index') }}">
                                    <i class="bi bi-folder"></i> Categories
                                </a>
                            </li>
                        @endcan

                        @can('manage products')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                                    href="{{ route('admin.products.index') }}">
                                    <i class="bi bi-box"></i> Products
                                </a>
                            </li>
                        @endcan

                        @can('manage orders')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
                                    href="{{ route('admin.orders.index') }}">
                                    <i class="bi bi-cart"></i> Orders
                                </a>
                            </li>
                        @endcan
                    </ul>


                    <ul class="nav flex-column">
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