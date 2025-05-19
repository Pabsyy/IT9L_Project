<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\InventoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API Version 1
Route::prefix('v1')->middleware(['throttle:60,1'])->group(function () {
    // Public routes
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'index']);
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        
        Route::apiResource('orders', OrderController::class);
        Route::apiResource('cart', CartController::class)->names([
            'index' => 'api.cart.index',
            'store' => 'api.cart.store',
            'show' => 'api.cart.show',
            'update' => 'api.cart.update',
            'destroy' => 'api.cart.destroy',
        ]);
        
        // Admin only routes
        Route::middleware('admin')->group(function () {
            Route::apiResource('users', UserController::class);
            Route::apiResource('inventory', InventoryController::class);
        });
    });
}); 