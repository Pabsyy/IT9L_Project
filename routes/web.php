<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Redirect root URL to login or dashboard based on authentication status
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect('/auth');
});

// Login page
Route::get('/auth', function () {
    return view('auth.login'); // Updated to point to the correct view
})->name('login');

// Dashboard page
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');

Route::get('/orders', [OrdersController::class, 'index'])->name('orders');

Route::get('/suppliers', [SuppliersController::class, 'index'])->name('suppliers');
Route::post('/suppliers', [SuppliersController::class, 'store'])->name('suppliers.store');
Route::post('/suppliers/contact', [SuppliersController::class, 'contact'])->name('suppliers.contact');

Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

Route::get('/categories', [ProductController::class, 'categories'])->name('categories');

// Add resourceful routes for products
Route::resource('products', ProductController::class);
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/update-picture', [ProfileController::class, 'updatePicture'])->name('profile.update.picture');
    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.delete');
});

// Include authentication routes
require __DIR__.'/auth.php';
