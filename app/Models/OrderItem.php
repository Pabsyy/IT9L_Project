<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    //
    // Specify the table name if it's not the default 'order_items'
    protected $table = 'order_items'; // Update this if your table name is different
    protected $primaryKey = 'id'; // Update this if your primary key is different

    protected $fillable = [
        'ProductID', // Add other fillable fields as needed
        'quantity',
        'price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID', 'id'); // Updated from 'product'
    }
}