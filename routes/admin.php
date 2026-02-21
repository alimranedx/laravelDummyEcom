<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RolePermissionAssociationController;
use App\Http\Controllers\Admin\AdminUserRoleController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\UserManagementController;

Route::prefix('admin')->name('admin.')->group(function () {

    // Redirect /admin to dashboard if authenticated, otherwise to login
    // Redirect /admin to dashboard if authenticated, otherwise to login
    Route::get('/', function () {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    });

    // Admin authentication routes
    Route::middleware('guest')->group(function () {
        Route::get('login', function () {
            return view('admin.login');
        })->name('login');

        Route::post('login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
    });

    // Protected admin routes
    Route::middleware(['auth', 'admin', 'check_page_permission'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Categories
        Route::resource('categories', CategoryController::class)->middleware('can:manage categories');

        // Brands
        Route::resource('brands', BrandController::class)->middleware('can:manage categories');
        Route::get('brands/{brand}/categories', [BrandController::class, 'getCategories'])->name('brands.categories');

        // Products
        Route::resource('products', ProductController::class)->middleware('can:manage products');

        // Orders
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index')->middleware('can:manage orders');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show')->middleware('can:manage orders');
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus')->middleware('can:manage orders');


        // Super Admin only routes: Role & Association
        Route::middleware('super_admin')->group(function () {
            // Roles
            Route::resource('roles', RoleController::class);

            // Permission management removed as redundant with new page-based system

            // User Management
            Route::resource('admin-management', AdminManagementController::class)->parameters(['admin-management' => 'admin']);
            Route::resource('user-management', UserManagementController::class)->parameters(['user-management' => 'user']);

            // Role Permission Association
            Route::get('role-permissions', [RolePermissionAssociationController::class, 'index'])->name('role-permissions.index');
            Route::get('role-permissions/{role}/edit', [RolePermissionAssociationController::class, 'edit'])->name('role-permissions.edit');
            Route::put('role-permissions/{role}', [RolePermissionAssociationController::class, 'update'])->name('role-permissions.update');

            // Admin User Role assignment
            Route::get('admin-user-roles', [AdminUserRoleController::class, 'index'])->name('admin-user-roles.index');
            Route::get('admin-user-roles/{user}/edit', [AdminUserRoleController::class, 'edit'])->name('admin-user-roles.edit');
            Route::patch('admin-user-roles/{user}', [AdminUserRoleController::class, 'update'])->name('admin-user-roles.update');
        });

        // Logout
        Route::post('logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');
    });
});
