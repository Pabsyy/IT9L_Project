<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sales_transactions';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'order_id',
        'user_id',
        'supplier_id',
        'reference_number',
        'customer_name',
        'customer_email',
        'contact_number',
        'shipping_address',
        'billing_address',
        'delivery_method',
        'subtotal',
        'tax',
        'shipping_fee',
        'discount',
        'grand_total',
        'payment_method',
        'payment_status',
        'order_status',
        'notes',
        'transaction_date',
        'paid_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at'
    ];

    protected $dates = [
        'transaction_date',
        'paid_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'grand_total' => 'decimal:2'
    ];

    /**
     * Get the user that owns the order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the order
     */
    public function items()
    {
        return $this->hasMany(SalesTransactionItem::class);
    }

    /**
     * Get the status history for the order
     */
    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    /**
     * Get the supplier for the order
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the formatted grand total.
     */
    public function getFormattedGrandTotalAttribute()
    {
        return '₱' . number_format($this->grand_total, 2);
    }

    /**
     * Get the status badge details.
     */
    public function getStatusBadge()
    {
        return match($this->order_status) {
            'completed' => ['class' => 'bg-green-100 text-green-800', 'icon' => 'ri-checkbox-circle-line'],
            'processing' => ['class' => 'bg-blue-100 text-blue-800', 'icon' => 'ri-loader-4-line'],
            'cancelled' => ['class' => 'bg-red-100 text-red-800', 'icon' => 'ri-close-circle-line'],
            default => ['class' => 'bg-yellow-100 text-yellow-800', 'icon' => 'ri-time-line']
        };
    }

    // Calculate total amount for the transaction
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            // Generate a unique order ID if not set
            if (!$transaction->order_id) {
                $transaction->order_id = '#ORD-' . uniqid();
            }
            // Set transaction date if not set
            if (!$transaction->transaction_date) {
                $transaction->transaction_date = now();
            }
        });
    }
}
