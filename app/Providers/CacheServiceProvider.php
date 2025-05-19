<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CacheService;

class CacheServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CacheService::class, function ($app) {
            return new CacheService();
        });
    }

    public function boot()
    {
        // Clear product cache when a product is updated
        \App\Models\Product::updated(function ($product) {
            CacheService::flush(['products', "category_{$product->category_id}"]);
        });

        // Clear category cache when a category is updated
        \App\Models\Category::updated(function ($category) {
            CacheService::flush('categories');
        });

        // Clear popular products cache when an order is placed
        \App\Models\Order::created(function ($order) {
            CacheService::flush('popular_products');
        });
    }
} 