<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Customer Services Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for customer-related services
    | including the shopping cart and other customer features.
    |
    */

    'cart' => [
        'service' => App\Customerapp\Services\CartService::class,
        'session_key' => 'shopping_cart',
        'tax_rate' => 0.12, // 12% tax rate
        'decimals' => 2,
    ],
]; 