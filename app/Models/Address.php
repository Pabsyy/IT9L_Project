<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'street_address',
        'city',
        'state',
        'postal_code',
        'country',
        'is_default',
        'phone_number',
        'additional_info'
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Get the user that owns the address.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full address as a string.
     */
    public function getFullAddressAttribute()
    {
        return implode(', ', array_filter([
            $this->street_address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country
        ]));
    }
} 