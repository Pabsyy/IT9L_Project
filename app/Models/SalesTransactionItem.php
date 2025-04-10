<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTransactionItem extends Model
{
    protected $table = 'salestransaction_item'; // Adjust the table name if necessary
    protected $primaryKey = 'TransactionItemID'; // Adjust the primary key if necessary
    public $timestamps = false; // Assuming no timestamps for this table

    protected $fillable = [
        'TransactionID', 'ProductID', 'Quantity', 'Price'
    ];

    public function salesTransaction()
    {
        return $this->belongsTo(SalesTransaction::class, 'TransactionID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID');
    }
}
