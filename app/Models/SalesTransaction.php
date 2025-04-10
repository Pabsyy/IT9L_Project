<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTransaction extends Model
{
    protected $table = 'salestransaction';
    protected $primaryKey = 'TransactionID';
    public $timestamps = true;

    protected $fillable = [
        'UserID', 'PaymentMethod', 'TransactionDate', 'GrandTotal'
    ];
}
