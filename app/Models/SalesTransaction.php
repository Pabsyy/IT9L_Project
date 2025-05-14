<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTransaction extends Model
{
    protected $table = 'sales_transactions';
    protected $primaryKey = 'order_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'order_id',
        'user_id',
        'customer_name',
        'customer_email',
        'grand_total',
        'payment_method',
        'status'
    ];

    public function items()
    {
        return $this->hasMany(SalesTransactionItem::class, 'transaction_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Calculate total amount for the transaction
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            $transaction->order_id = '#ORD-' . str_pad(random_int(1, 99999), 5, '0', STR_PAD_LEFT);
        });
    }
}
