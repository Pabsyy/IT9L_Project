<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cartitem';
    protected $primaryKey = 'CartItemID';
    public $timestamps = true;

    protected $fillable = ['CartID', 'ProductID', 'Quantity', 'UnitPrice'];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'CartID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID');
    }
}