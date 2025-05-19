<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Services\CartService instance(string $instance = null)
 * @method static \Illuminate\Support\Collection content()
 * @method static float total()
 * @method static float subtotal()
 * @method static int count()
 * @method static mixed add($id, $name, $qty = 1, $price = 0.0, $options = [])
 * @method static void remove($rowId)
 * @method static mixed update($rowId, $qty)
 * 
 * @see \App\Services\CartService
 */
class Cart extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cart';
    }
}
