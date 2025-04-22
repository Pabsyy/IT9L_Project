<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTransactionItem extends Model
{
    protected $table = 'salestransactionitem';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'TransactionID',  // Note: This matches the column name in migration
        'ProductID', 
        'Quantity',
        'UnitPrice'
    ];

    public function salesTransaction()
    {
        return $this->belongsTo(SalesTransaction::class, 'TransactionID', 'id');
        // Changed from 'SalesTransactionID' to 'TransactionID' to match migration
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID', 'ProductID');
    }
}
