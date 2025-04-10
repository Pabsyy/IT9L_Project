<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $primaryKey = 'SupplierID';
    public $timestamps = true;

    protected $fillable = ['SupplierName', 'ContactNumber', 'Email'];

    public function products()
    {
        return $this->hasMany(Product::class, 'SupplierID');
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'SupplierID');
    }
}
