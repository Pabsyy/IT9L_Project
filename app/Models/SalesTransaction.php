<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTransaction extends Model
{
    protected $table = 'salestransaction';
    protected $primaryKey = 'id';

    protected $fillable = [
        'UserID',
        'PaymentMethod',
        'TransactionDate',
        'GrandTotal'
    ];

    public function items()
    {
        return $this->hasMany(SalesTransactionItem::class, 'TransactionID', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID');
    }
}
