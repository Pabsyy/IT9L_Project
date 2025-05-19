<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer_payment_methods';

    protected $fillable = [
        'user_id',
        'card_type',
        'last_four',
        'card_holder_name',
        'expiry_month',
        'expiry_year',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedExpiryAttribute()
    {
        return $this->expiry_month . '/' . $this->expiry_year;
    }

    public function getFormattedCardNumberAttribute()
    {
        return '•••• •••• •••• ' . $this->last_four;
    }
} 