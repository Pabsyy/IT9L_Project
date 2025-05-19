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
        'cart_id',
        'product_id',
        'quantity',
        'price'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class)->where('stock', '>', 0);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Calculate subtotal for cart item
    public function getSubtotal()
    {
        return $this->quantity * ($this->price ?? $this->product->price);
    }

    // Check if quantity is available in stock
    public function hasAvailableStock()
    {
        return $this->product->stock >= $this->quantity;
    }

    // Get maximum available quantity
    public function getMaxAvailableQuantity()
    {
        return $this->product->stock;
    }

    // Cart relationship
    public function cart()
    {
        return $this->belongsTo(Cart::class);
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
        return $this->quantity * ($this->price ?? $this->product->price);
    }

    // Prepare item for checkout
    public function prepareForCheckout()
    {
        return [
            'product_name' => $this->product->ProductName,
            'quantity' => $this->quantity,
            'unit_price' => $this->price ?? $this->product->Price,
            'subtotal' => $this->getTotalForCheckout()
        ];
    }

    // Validate item for checkout
    public function isValidForCheckout()
    {
        return $this->hasAvailableStock() && $this->product->isInStock();
    }

    /**
     * Get the total price for this cart item
     */
    public function getTotalPrice()
    {
        return $this->quantity * ($this->price ?? $this->product->price);
    }

    /**
     * Convert the model instance to an array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_name' => $this->product->name,
            'quantity' => $this->quantity,
            'unit_price' => $this->price ?? $this->product->price,
            'subtotal' => $this->getSubtotal()
        ];
    }
}
