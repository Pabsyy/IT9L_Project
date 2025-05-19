<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $table = 'purchaseorderitem';
    protected $primaryKey = 'PurchaseOrderItemID';
    public $timestamps = true;

    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'quantity',
        'unit_price'
    ];

    /**
     * Get the purchase order that owns this item
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    /**
     * Get the product for this purchase order item
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the subtotal for this purchase order item
     */
    public function getSubtotal()
    {
        return $this->quantity * $this->unit_price;
    }
}