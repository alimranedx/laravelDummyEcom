<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['frontend'])->group(function () {
    Route::get('/', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('home');
    Route::get('/product/{slug}', [App\Http\Controllers\Frontend\HomeController::class, 'productDetail'])->name('product.show');

    Route::get('/cart', [App\Http\Controllers\Frontend\CartController::class, 'index'])->name('cart.index');
    Route::get('/add-to-cart/{id}', [App\Http\Controllers\Frontend\CartController::class, 'add'])->name('cart.add');
    Route::patch('/update-cart', [App\Http\Controllers\Frontend\CartController::class, 'update'])->name('cart.update');
    Route::delete('/remove-from-cart', [App\Http\Controllers\Frontend\CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [App\Http\Controllers\Frontend\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/place-order', [App\Http\Controllers\Frontend\CheckoutController::class, 'placeOrder'])->name('checkout.place');
});

Route::get('/dashboard', [\App\Http\Controllers\Frontend\UserDashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'user'])->name('dashboard');

Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['frontend'])->group(function () {
    require __DIR__ . '/auth.php';
});
