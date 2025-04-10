<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $primaryKey = 'ProductID';
    public $timestamps = true;

    protected $fillable = [
        'SupplierID', 'ProductName', 'Description', 'Image',
        'Price', 'Quantity', 'Category', 'price', 'stock', 'sales',
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
