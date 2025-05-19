<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cartitem extends Model 
{
    use HasFactory;

    protected $table = 'cartitem';
    protected $primaryKey = 'CartitemID';
    public $timestamps = true;

    protected $fillable = [
        'CartID',
        'ProductID',
        'user_id',
        'Quantity',
        'UnitPrice'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID', 'ProductID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Calculate subtotal for cart item
    public function getSubtotal()
    {
        return $this->Quantity * ($this->UnitPrice ?? $this->product->Price);
    }

    // Check if quantity is available in stock
    public function hasEnoughStock()
    {
        return $this->product->Quantity >= $this->Quantity;
    }

    // Get maximum available quantity
    public function getMaxAvailableQuantity()
    {
        return $this->product->Quantity;
    }

    // Cart relationship
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'CartID', 'cartID');
    }

    // Scope for active cart items
    public function scopeActive($query)
    {
        return $query->whereHas('product', function($q) {
            $q->where('Quantity', '>', 0);
        });
    }

    // Get total for checkout
    public function getTotalForCheckout()
    {
        return $this->Quantity * ($this->UnitPrice ?? $this->product->Price);
    }

    // Prepare item for checkout
    public function prepareForCheckout()
    {
        return [
            'product_name' => $this->product->ProductName,
            'quantity' => $this->Quantity,
            'unit_price' => $this->UnitPrice ?? $this->product->Price,
            'subtotal' => $this->getTotalForCheckout()
        ];
    }

    // Validate item for checkout
    public function isValidForCheckout()
    {
        return $this->hasEnoughStock() && $this->product->isInStock();
    }
}
