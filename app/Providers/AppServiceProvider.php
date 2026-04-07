<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        \Illuminate\Auth\Middleware\RedirectIfAuthenticated::redirectUsing(function ($request) {
            if ($request->user()->isAdmin()) {
                return route('admin.dashboard');
            }
            return route('dashboard');
        });

        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            return $user->isSuperAdmin() ? true : null;
        });

        // Define gates based on user_type and Spatie permissions for granular access
        \Illuminate\Support\Facades\Gate::define('view admin', function ($user) {
            return $user->isAdmin();
        });

        \Illuminate\Support\Facades\Gate::define('manage categories', function ($user) {
            return $user->isAdmin() && $user->hasPermissionTo('manage categories');
        });

        \Illuminate\Support\Facades\Gate::define('manage products', function ($user) {
            return $user->isAdmin() && $user->hasPermissionTo('manage products');
        });

        \Illuminate\Support\Facades\Gate::define('manage orders', function ($user) {
            return $user->isAdmin() && $user->hasPermissionTo('manage orders');
        });

        \Illuminate\Support\Facades\Gate::define('manage users', function ($user) {
            return $user->isAdmin() && $user->hasPermissionTo('manage users');
        });
    }
}
