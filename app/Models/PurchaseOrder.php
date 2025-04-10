<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $table = 'purchaseorder';
    protected $primaryKey = 'PurchaseOrderID';
    public $timestamps = true;

    protected $fillable = [
        'UserID', 'SupplierID', 'OrderDate', 'DeliveryDate'
    ];

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'PurchaseOrderID');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'SupplierID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID');
    }
}