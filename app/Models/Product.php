<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name', 'sku', 'description', 'price', 
        'stock', 'category', 'brand', 'image_url',
        'featured', 'supplier_id', 'sales'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'SupplierID');
    }

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'ProductID');
    }

    public function salesTransactionItems()
    {
        return $this->hasMany(SalesTransactionItem::class, 'ProductID');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'ProductID');
    }
}
