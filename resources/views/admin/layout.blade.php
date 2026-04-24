<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    $adminNotifications = \App\Models\AdminNotification::latest()->take(10)->get();
    $unreadCount = $adminNotifications->whereNull('read_at')->count();
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - {{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('/assets/bootstrap-5.3.8-dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="{{ asset('css/admin-theme.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 p-0 d-md-block sidebar collapse"
                style="height: 100vh; position: sticky; top: 0; overflow-y: auto; overflow-x: hidden;">
                <div class="sidebar-brand-wrapper mb-3 text-center">
                    <a href="{{ route('admin.dashboard') }}"
                        class="text-white text-decoration-none d-flex align-items-center justify-content-center">
                        <i class="bi bi-shield-check fs-3 me-2 text-info"></i>
                        <span class="fs-5 fw-bold tracking-tight">E-ADMIN</span>
                    </a>
                </div>

                <div>

                    @php
                        $user = auth()->user();
                        if ($user->isSuperAdmin()) {
                            $permittedModules = \App\Models\Module::with([
                                'subModules' => function ($q) {
                                    $q->orderBy('sequence');
                                },
                            ])
                                ->orderBy('sequence')
                                ->get();
                        } else {
                            $permittedModules = \App\Models\Module::with([
                                'subModules' => function ($query) use ($user) {
                                    $query
                                        ->whereExists(function ($q) use ($user) {
                                            $q->select(DB::raw(1))
                                                ->from('pages')
                                                ->join('role_pages', 'pages.id', '=', 'role_pages.page_id')
                                                ->join(
                                                    'model_has_roles',
                                                    'role_pages.role_id',
                                                    '=',
                                                    'model_has_roles.role_id',
                                                )
                                                ->whereColumn('pages.sub_module_id', 'sub_modules.id')
                                                ->where('model_has_roles.model_id', $user->id)
                                                ->where('model_has_roles.model_type', get_class($user));
                                        })
                                        ->orderBy('sequence');
                                },
                            ])
                                ->where('name', '!=', 'RBAC')
                                ->whereHas('subModules', function ($query) use ($user) {
                                    $query->whereExists(function ($q) use ($user) {
                                        $q->select(DB::raw(1))
                                            ->from('pages')
                                            ->join('role_pages', 'pages.id', '=', 'role_pages.page_id')
                                            ->join(
                                                'model_has_roles',
                                                'role_pages.role_id',
                                                '=',
                                                'model_has_roles.role_id',
                                            )
                                            ->whereColumn('pages.sub_module_id', 'sub_modules.id')
                                            ->where('model_has_roles.model_id', $user->id)
                                            ->where('model_has_roles.model_type', get_class($user));
                                    });
                                })
                                ->orderBy('sequence')
                                ->get();
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

                    @foreach ($permittedModules as $module)
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
                                aria-expanded="{{ $isModuleActive ? 'true' : 'false' }}"
                                aria-controls="{{ $moduleId }}">
                                <i class="{{ $module->icon }} me-2"></i>
                                <span>{{ $module->display_name }}</span>
                                <i class="bi bi-chevron-down ms-auto small transition-icon"></i>
                            </a>
                            <div class="collapse {{ $isModuleActive ? 'show' : '' }} ms-3" id="{{ $moduleId }}">
                                <ul
                                    class="nav flex-column border-start border-secondary border-opacity-25 ms-2 ps-2 mt-1">
                                    @foreach ($module->subModules as $subModule)
                                        @php
                                            $isSubActive = false;
                                            if ($currentAction) {
                                                $subController = ltrim(explode('@', $currentAction)[0] ?? '', '\\');
                                                $isSubActive = $subController === $subModule->controller_name;
                                            }
                                            $routeName =
                                                'admin.' .
                                                strtolower(str_replace(' ', '-', $subModule->name)) .
                                                '.' .
                                                strtolower(trim($subModule->default_method ?? ''));
                                            if (!Route::has($routeName)) {
                                                if ($subModule->name == 'Admins') {
                                                    $routeName = 'admin.admin-management.index';
                                                } elseif ($subModule->name == 'Regular Users') {
                                                    $routeName = 'admin.user-management.index';
                                                } elseif ($subModule->name == 'Dashboard') {
                                                    $routeName = 'admin.dashboard';
                                                } elseif ($subModule->name == 'Role') {
                                                    $routeName = 'admin.roles.index';
                                                }
                                            }
                                            $subModuleUrl = Route::has($routeName) ? route($routeName) : '#';
                                        @endphp
                                        <li class="nav-item">
                                            <a class="nav-link py-1 {{ $isSubActive ? 'text-primary fw-bold' : 'opacity-75' }}"
                                                href="{{ $subModuleUrl }}">
                                                <i class="{{ $subModule->icon }} me-2"></i>
                                                {{ $subModule->display_name }}
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
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 bg-light" style="min-height: 100vh;">
                <!-- Top navbar -->
                <header class="navbar navbar-expand-md navbar-light bg-white border-bottom sticky-top p-3 mb-3">
                    <button class="navbar-toggler d-md-none border-0" type="button" data-bs-toggle="collapse"
                        data-bs-target="#sidebar">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="ms-auto d-flex align-items-center">
                        <div class="dropdown me-3">
                            <a href="#" class="text-secondary position-relative text-decoration-none" id="notificationDropdown" aria-expanded="false">
                                <i class="bi bi-bell fs-5"></i>
                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" id="notificationBadge" style="display: {{ $unreadCount > 0 ? 'inline-block' : 'none' }};">
                                    <span class="visually-hidden">New alerts</span>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 pt-0" aria-labelledby="notificationDropdown" id="notificationList" style="width: 300px; max-height: 400px; overflow-y: auto;">
                                <li class="bg-light px-3 py-2 border-bottom text-muted fw-bold">Notifications</li>
                                @forelse($adminNotifications as $notif)
                                    <a href="{{ $notif->url ?? '#' }}" class="px-3 py-2 border-bottom d-flex align-items-start dropdown-item text-wrap text-decoration-none notification-item">
                                        <div class="me-2 text-{{ $notif->type }}"><i class="bi bi-bell-fill"></i></div>
                                        <div class="flex-grow-1">
                                            <div class="small text-dark fw-medium {!! is_null($notif->read_at) ? 'fw-bold' : '' !!}">{{ $notif->message }}</div>
                                            <div class="text-muted d-flex justify-content-between align-items-center" style="font-size: 0.75rem;">
                                                <span>{{ $notif->created_at->format('h:i A') }}</span>
                                                @if(is_null($notif->read_at))
                                                    <span class="p-1 bg-primary rounded-circle unread-indicator"></span>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <li class="px-3 py-3 text-center text-muted small" id="noNotificationsMsg">No new notifications</li>
                                @endforelse
                            </ul>
                        </div>
                        <span class="text-secondary small fw-medium">Welcome, {{ Auth::user()->name }}</span>
                    </div>
                </header>

                <!-- Flash messages -->
                <div class="mt-3">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
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
    <!-- 1. jQuery (REQUIRED) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('/assets/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    
    <!-- Toast Container for Notifications -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1055;">
        <div id="liveToast" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage">
                    <!-- Message goes here -->
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            let unreadCount = {{ $unreadCount }};

            if (window.Echo) {
                window.Echo.channel('admin-notifications')
                    .listen('SaleCreated', (e) => {
                        showToast(e.notification.message, 'success');
                        addNotificationToDropdown(e.notification.message, 'success', e.notification.url);
                    })
                    .listen('UserRegistered', (e) => {
                        showToast(e.notification.message, 'info');
                        addNotificationToDropdown(e.notification.message, 'info', e.notification.url);
                    });
            }

            function showToast(message, type) {
                const toastEl = document.getElementById('liveToast');
                const toastMessage = document.getElementById('toastMessage');
                
                toastEl.className = `toast align-items-center border-0 text-bg-${type}`;
                toastMessage.innerText = message;
                
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            }

            function addNotificationToDropdown(message, type, url = '#') {
                const noNotificationsMsg = document.getElementById('noNotificationsMsg');
                if (noNotificationsMsg) {
                    noNotificationsMsg.remove();
                }

                const list = document.getElementById('notificationList');
                const time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                
                const item = document.createElement(url !== '#' ? 'a' : 'li');
                if (url !== '#') {
                    item.href = url;
                }
                item.className = 'px-3 py-2 border-bottom d-flex align-items-start dropdown-item text-wrap text-decoration-none';
                item.innerHTML = `
                    <div class="me-2 text-${type}"><i class="bi bi-bell-fill"></i></div>
                    <div>
                        <div class="small text-dark fw-medium">${message}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">${time}</div>
                    </div>
                `;
                
                // Add to list right after the header
                list.insertBefore(item, list.children[1]);

                // Maintain max 10 items (header is index 0, so max index is 10)
                if (list.children.length > 11) {
                    list.removeChild(list.lastElementChild);
                }

                // Update badge
                unreadCount++;
                const badge = document.getElementById('notificationBadge');
                badge.style.display = 'inline-block';
            }

            // Initialize manual dropdown toggle to avoid Bootstrap double-loading conflicts
            const dropdownBtn = document.getElementById('notificationDropdown');
            const dropdownMenu = document.getElementById('notificationList');

            dropdownBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const isShown = dropdownMenu.classList.contains('show');
                
                if (isShown) {
                    dropdownMenu.classList.remove('show');
                    dropdownBtn.setAttribute('aria-expanded', 'false');
                } else {
                    dropdownMenu.classList.add('show');
                    dropdownBtn.setAttribute('aria-expanded', 'true');
                    
                    if (unreadCount > 0) {
                        // Reset count when opening
                        unreadCount = 0;
                        document.getElementById('notificationBadge').style.display = 'none';
                        
                        // Remove unread styling
                        document.querySelectorAll('.unread-indicator').forEach(el => el.remove());
                        document.querySelectorAll('.notification-item .fw-bold').forEach(el => el.classList.remove('fw-bold'));

                        // Mark as read in backend
                        fetch("{{ route('admin.notifications.mark-as-read') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });
                    }
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.remove('show');
                    dropdownBtn.setAttribute('aria-expanded', 'false');
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
