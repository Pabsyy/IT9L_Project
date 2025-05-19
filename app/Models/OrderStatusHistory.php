<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_transaction_id',
        'status',
        'notes',
        'created_by'
    ];

    public function salesTransaction()
    {
        return $this->belongsTo(SalesTransaction::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
} 