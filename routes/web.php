<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProductRatingController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\AddressController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\CartController as RootCartController;
use App\Http\Controllers\Customer\CartController as CustomerCartController;
use App\Http\Controllers\Customer\OrdersController as CustomerOrdersController;

// Admin routes
Route::prefix('admin')->group(function () {
    // Guest routes
    Route::middleware('guest')->group(function () {
        // Login Routes
        Route::get('/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
        Route::post('/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');
        
        // Registration Routes
        Route::get('/register', function() {
            return view('admin.auth.register');
        })->name('admin.register');
        Route::post('/register', [AuthController::class, 'adminRegister'])->name('admin.register.submit');
        
        // Password Reset Routes
        Route::get('/forgot-password', [AuthController::class, 'showAdminForgotPassword'])->name('admin.password.request');
        Route::post('/forgot-password', [AuthController::class, 'adminForgotPassword'])->name('admin.password.email');
        Route::get('/reset-password/{token}', [AuthController::class, 'showAdminResetPassword'])->name('admin.password.reset');
        Route::post('/reset-password', [AuthController::class, 'adminResetPassword'])->name('admin.password.update');
    });
    
    // Protected admin routes
    Route::middleware(['auth', \App\Http\Middleware\Admin::class])->group(function () {
        // Make both /admin and /admin/dashboard point to the same controller
        Route::get('/', [DashboardController::class, 'index'])->name('admin.index');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/dashboard/revenue-data', [DashboardController::class, 'getRevenueData'])->name('admin.dashboard.revenue');

        // Admin Profile Management
        Route::get('/profile', function() {
            return view('profile.edit', [
                'user' => Auth::user(),
                'timezones' => [
                    'UTC' => 'UTC',
                    'America/New_York' => 'Eastern Time',
                    'America/Chicago' => 'Central Time',
                    'America/Denver' => 'Mountain Time',
                    'America/Los_Angeles' => 'Pacific Time',
                ],
                'languages' => [
                    'en' => 'English',
                    'es' => 'French',
                    'fr' => 'French',
                ],
                'activities' => collect([])  // Empty collection for now
            ]);
        })->name('profile.edit');
        
        Route::patch('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::post('/profile/picture', [ImageUploadController::class, 'uploadProfilePicture'])->name('profile.picture');
        
        // Main Admin Routes
        Route::get('/inventory', [InventoryController::class, 'index'])->name('admin.inventory');

        // Product Management
        Route::prefix('products')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('admin.products');
            Route::get('/create', [ProductController::class, 'create'])->name('admin.products.create');
            Route::post('/', [ProductController::class, 'store'])->name('admin.products.store');
            Route::get('/{product}', [ProductController::class, 'show'])->name('admin.products.show');
            Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
            Route::put('/{product}', [ProfileController::class, 'update'])->name('admin.products.update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
        });

        // Order Management
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrdersController::class, 'index'])->name('admin.orders');
            Route::get('/create', [OrdersController::class, 'create'])->name('admin.orders.create');
            Route::post('/', [OrdersController::class, 'store'])->name('admin.orders.store');
            Route::get('/{order}', [OrdersController::class, 'show'])->name('admin.orders.show');
            Route::put('/{order}/status', [ProfileController::class, 'updateStatus'])->name('admin.orders.status.update');
            Route::delete('/{order}', [OrdersController::class, 'destroy'])->name('admin.orders.destroy');
        });

        Route::get('/suppliers', [SupplierController::class, 'index'])->name('admin.suppliers');
        Route::post('/suppliers', [SupplierController::class, 'store'])->name('admin.suppliers.store');
        Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('admin.suppliers.edit');
        Route::put('/suppliers/{supplier}', [ProfileController::class, 'update'])->name('admin.suppliers.update');
        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('admin.suppliers.destroy');

        // Categories Management
        Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');

        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('admin.analytics');

        // Users Management
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('admin.users');
            Route::get('/create', [UserController::class, 'create'])->name('admin.users.create');
            Route::post('/', [UserController::class, 'store'])->name('admin.users.store');
            Route::get('/{user}', [UserController::class, 'show'])->name('admin.users.show');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
            Route::put('/{user}', [ProfileController::class, 'update'])->name('admin.users.update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        });

        // Stock Management Routes
        Route::prefix('stock')->group(function () {
            Route::get('/in', [StockController::class, 'stockIn'])->name('admin.stock.in');
            Route::post('/in', [StockController::class, 'stockInStore'])->name('admin.stock.in.store');
            Route::get('/out', [StockController::class, 'stockOut'])->name('admin.stock.out');
            Route::post('/out', [StockController::class, 'stockOutStore'])->name('admin.stock.out.store');
        });

        // Admin Logout
        Route::post('/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');
    });
});

// Set welcome page as the default landing page
Route::get('/', function () {
    $products = \App\Models\Product::latest()->take(8)->get();
    $categories = \App\Models\Category::orderBy('name')->get();
    return view('Customer.welcome', compact('products', 'categories'));
})->name('welcome');

// Customer Auth routes
Route::prefix('customer')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', function() {
            return view('Customer.views.auth.login');
        })->name('login');
        
        Route::get('/register', function() {
            return view('Customer.views.auth.register');
        })->name('register');

        // Social Login Routes
        Route::get('auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
        Route::get('auth/google/callback', [AuthController::class, 'handleFacebookCallback']);
    });
    
    Route::post('/login', [AuthController::class, 'login'])->name('customer.login');
    Route::post('/register', [AuthController::class, 'register'])->name('customer.register');
    Route::post('/logout', [AuthController::class, 'logout'])->name('customer.logout')->middleware('auth');
});

// Customer Product routes
Route::prefix('customer')->name('customer.')->group(function () {
    // Product routes
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/search', [ProductController::class, 'search'])->name('search');
        Route::get('/category/{category}', [ProductController::class, 'byCategory'])->name('category');
        Route::get('/{id}', [ProductController::class, 'show'])->name('show');
        Route::get('/shop', [ProductController::class, 'showEngine'])->name('shop');
        Route::get('/featured', [ProductController::class, 'getFeaturedProducts'])->name('featured');
        Route::middleware('auth')->group(function () {
            Route::post('/{id}/update', [ProductController::class, 'update'])->name('update');
            Route::post('/{product}/rate', [ProductRatingController::class, 'store'])->name('rate');
            Route::get('/{product}/ratings', [ProductRatingController::class, 'index'])->name('ratings');
        });
    });

    // Cart routes
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CustomerCartController::class, 'viewCart'])->name('view');
        Route::get('/modal', [CustomerCartController::class, 'getCartModal'])->name('modal');
        Route::get('/items', [CustomerCartController::class, 'getCartItems'])->name('items');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::post('/update', [CartController::class, 'update'])->name('update');
        Route::middleware('auth')->group(function () {
            Route::delete('/remove/{rowId}', [CartController::class, 'remove'])->name('remove');
        });
    });

    // Wishlist routes
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::post('/toggle', [WishlistController::class, 'toggle'])->name('toggle');
        Route::get('/', [WishlistController::class, 'index'])->name('index');
    });

    // Legal pages
    Route::get('/privacy-policy', function () {
        return view('customer.legal.privacy-policy');
    })->name('privacy-policy');

    Route::get('/terms', function () {
        return view('customer.legal.terms');
    })->name('terms');
});

// Protected customer routes
Route::middleware(['auth'])->group(function () {
    // Account routes
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/', function() {
            $orders = Auth::user()->orders()->orderBy('created_at', 'desc')->get();
            $wishlist = Auth::user()->wishlists()->orderBy('created_at', 'desc')->get();
            return view('Customer.views.account.dashboard', compact('orders', 'wishlist'));
        })->name('dashboard');
        
        Route::get('/settings', function () {
            return view('Customer.views.account.settings');
        })->name('settings');

        // Profile Management Routes
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Payment Method Routes
        Route::post('/payment-methods', [PaymentMethodController::class, 'store'])->name('payment-methods.store');
        Route::delete('/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('payment-methods.delete');

        // Address Management Routes
        Route::prefix('addresses')->name('addresses.')->group(function () {
            Route::post('/', [AddressController::class, 'store'])->name('store');
            Route::put('/{address}', [ProfileController::class, 'update'])->name('update');
            Route::delete('/{address}', [OrderController::class, 'destroy'])->name('delete');
            Route::post('/{address}/make-default', [ProfileController::class, 'make-default'])->name('make-default');
        });
    });
    
    // Checkout Process
    Route::prefix('checkout')->name('customer.checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'showShipping'])->name('index');
        Route::get('/shipping', [CheckoutController::class, 'showShipping'])->name('shipping');
        Route::post('/shipping', [ProfileController::class, 'update'])->name('shipping.save');
        Route::get('/payment', [CheckoutController::class, 'showPayment'])->name('payment');
        Route::post('/payment', [ProfileController::class, 'update'])->name('payment.save');
        Route::get('/review', [CheckoutController::class, 'showReview'])->name('review');
        Route::post('/process', [ProfileController::class, 'update'])->name('process');
        Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
    });
});

// Shop route (renamed for consistency)
Route::get('/shop', [ProductController::class, 'showEngine'])->name('customer.products.shop');
Route::get('/featured-products', [ProductController::class, 'getFeaturedProducts'])->name('customer.products.featured');
Route::get('/cartview', function () { return view('cartview'); })->name('cartview.page');

// Password Reset Routes
Route::get('forgot-password', [AuthController::class, 'showForgotPassword'])
    ->middleware('guest')
    ->name('password.request');

Route::post('forgot-password', [AuthController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');

Route::get('reset-password/{token}', [AuthController::class, 'showResetPassword'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('reset-password', [ProfileController::class, 'update'])->name('password.update');

// Temporary route for review statistics
Route::get('/review-stats', function() {
    $totalReviews = DB::table('product_reviews')->count();
    $ratingDistribution = DB::table('product_reviews')
        ->select('rating', DB::raw('count(*) as count'))
        ->groupBy('rating')
        ->get()
        ->pluck('count', 'rating')
        ->toArray();
    $productsWithReviews = DB::table('products')
        ->where('rating_count', '>', 0)
        ->count();
    
    return [
        'total_reviews' => ProfileController::class, 'update',
        'rating_distribution' => ProfileController::class, 'update',
        'products_with_reviews' => ProfileController::class, 'update',
        'sample_reviews' => ProfileController::class, 'update'
            ->join('products', 'products.id', '=', 'product_reviews.product_id')
            ->select('products.name', 'product_reviews.rating', 'product_reviews.comment')
            ->limit(5)
            ->get()
    ];
});

// Image Upload Routes
Route::get('/upload', function () {
    return view('upload-form');
})->name('image.form');

Route::post('/upload', [ImageUploadController::class, 'upload'])->name('image.upload');

Route::get('/orders', [Customer\OrdersController::class, 'index'])->name('customer.orders');
