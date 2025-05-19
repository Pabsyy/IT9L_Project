<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'location',
        'last_restocked_at'
    ];

    protected $casts = [
        'last_restocked_at' => 'datetime'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
} 