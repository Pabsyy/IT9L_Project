<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders'; // Specify the table name if it's not the default 'orders'
    protected $fillable = [
        'status',
        'subtotal',
        'tax',
        'shipping_cost',
        'total',
    ];

    // Define relationships
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
