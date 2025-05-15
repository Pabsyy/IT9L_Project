<?php

use Illuminate\Support\Facades\Auth;
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
    return Auth::check() ? redirect()->route('dashboard') : redirect('/auth');
});

// Login page
Route::get('/auth', function () {
    return view('auth.login'); // Updated to point to the correct view
})->name('login');

// Dashboard page
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/admin/dashboard/revenue-data', [DashboardController::class, 'getRevenueData'])->name('dashboard.revenue-data');

Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory'); // Alias for inventory.index

Route::get('/suppliers', [SuppliersController::class, 'index'])->name('suppliers');
Route::post('/suppliers', [SuppliersController::class, 'store'])->name('suppliers.store');
Route::post('/suppliers/contact', [SuppliersController::class, 'contact'])->name('suppliers.contact');

Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

// Profile page
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');  // Add this line
    Route::post('/profile/picture', [ProfileController::class, 'updatePicture'])->name('profile.picture');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Orders routes
    Route::get('/orders', [OrdersController::class, 'index'])->name('orders');
    Route::get('/orders/create', [OrdersController::class, 'create'])->name('orders.create');
    Route::get('/orders/export', [OrdersController::class, 'export'])->name('orders.export'); // Moved up
    Route::post('/orders', [OrdersController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}/edit', [OrdersController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrdersController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [OrdersController::class, 'destroy'])->name('orders.destroy');

    // Settings and Products routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::get('/categories', [ProductController::class, 'categories'])->name('categories');
    Route::resource('products', ProductController::class)->except(['show']); // Added except to exclude show route if not needed
});

// Include authentication routes
require __DIR__.'/auth.php';
