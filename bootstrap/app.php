<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        },
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'super_admin' => \App\Http\Middleware\EnsureUserIsSuperAdmin::class,
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'user' => \App\Http\Middleware\EnsureUserIsUser::class,
            'frontend' => \App\Http\Middleware\RedirectAdminFromFrontend::class,
            'check_page_permission' => \App\Http\Middleware\CheckPagePermission::class,
        ]);

        // Redirect unauthenticated users to appropriate login page
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }
            return route('login');
        });
        
        $middleware->redirectUsersTo(fn() => route('dashboard'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
