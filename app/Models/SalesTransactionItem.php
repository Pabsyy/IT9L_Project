<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesTransactionItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sales_transaction_items';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'sales_transaction_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'discount_amount',
        'sku',
        'product_snapshot'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'product_snapshot' => 'json'
    ];

    /**
     * Get the order that owns the item
     */
    public function order()
    {
        return $this->belongsTo(SalesTransaction::class, 'sales_transaction_id');
    }

    /**
     * Get the product for this item
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the formatted unit price.
     */
    public function getFormattedUnitPriceAttribute()
    {
        return '₱' . number_format($this->unit_price, 2);
    }

    /**
     * Get the formatted subtotal.
     */
    public function getFormattedSubtotalAttribute()
    {
        return '₱' . number_format($this->subtotal, 2);
    }
}
