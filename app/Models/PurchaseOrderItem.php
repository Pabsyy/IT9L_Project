<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $table = 'purchaseorderitem';
    protected $primaryKey = 'PurchaseOrderItemID';
    public $timestamps = true;

    protected $fillable = ['PurchaseOrderID', 'ProductID', 'Quantity', 'UnitPrice'];

    public function order()
    {
        return $this->belongsTo(PurchaseOrder::class, 'PurchaseOrderID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID');
    }
}