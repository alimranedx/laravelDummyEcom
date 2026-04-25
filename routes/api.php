<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;

Route::group(['prefix' => 'auth'], function ($router) {
    // Public authentication routes
    Route::post('register', [AuthController::class, 'register']);              // Register a new user
    Route::post('login', [AuthController::class, 'login']);                    // Authenticate user and get JWT token
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']); // Send password reset OTP or link
    Route::post('reset-password', [AuthController::class, 'resetPassword']);   // Reset the user's password

    // Protected authentication routes (Require valid JWT token)
    Route::group(['middleware' => 'auth:api'], function() {
        Route::post('logout', [AuthController::class, 'logout']);              // Invalidate current JWT token
        Route::post('refresh', [AuthController::class, 'refresh']);            // Obtain a fresh JWT token
        Route::get('me', [AuthController::class, 'me']);                       // View authenticated user profile details
    });
});

/*
|--------------------------------------------------------------------------
| Product Catalog Routes
|--------------------------------------------------------------------------
|
| Publicly accessible routes for browsing products and viewing details.
|
*/
Route::group(['prefix' => 'products'], function ($router) {
    Route::get('/', [ProductController::class, 'index']);                      // List products (supports pagination and filtering)
    Route::get('/{id}', [ProductController::class, 'show']);                   // View detailed information for a single product
});

/*
|--------------------------------------------------------------------------
| User Account Routes
|--------------------------------------------------------------------------
|
| Protected routes for authenticated users to access their dashboard
| and personal data like order history. Requires valid JWT token.
|
*/
/*
|--------------------------------------------------------------------------
| Guest Checkout (Cash on Delivery - No Login Required)
|--------------------------------------------------------------------------
*/
Route::get('/track-order/{payment_id}', [\App\Http\Controllers\Api\OrderController::class, 'track']);

Route::group(['prefix' => 'user', 'middleware' => 'auth:api'], function ($router) {
    Route::get('/dashboard', [UserController::class, 'dashboard']);            // Retrieve user dashboard data/metrics
    Route::get('/orders', [UserController::class, 'orders']);                  // Retrieve user's past and current orders

    // Wishlist Routes
    Route::group(['prefix' => 'wishlist'], function() {
        Route::get('/', [\App\Http\Controllers\Api\WishlistController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\WishlistController::class, 'store']);
        Route::delete('/{product_id}', [\App\Http\Controllers\Api\WishlistController::class, 'destroy']);
    });

    // Checkout / Order Routes
    Route::get('/orders/{id}', [\App\Http\Controllers\Api\OrderController::class, 'show']);
    Route::post('/orders/{id}/cancel', [\App\Http\Controllers\Api\OrderController::class, 'cancel']);
});

// Unified Checkout (Publicly accessible, handles both Auth and Guest)
Route::post('/checkout', [\App\Http\Controllers\Api\OrderController::class, 'store']);
