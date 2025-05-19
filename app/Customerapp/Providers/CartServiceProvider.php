<?php

namespace App\Customerapp\Providers;

use Illuminate\Support\ServiceProvider;
use App\Customerapp\Services\CartService;
use Illuminate\Session\SessionManager;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('cart', function ($app) {
            return new CartService($app->make(SessionManager::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
} 