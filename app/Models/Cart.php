<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';
    protected $primaryKey = 'CartID';
    public $timestamps = true;

    protected $fillable = ['UserID'];

    public function items()
    {
        return $this->hasMany(CartItem::class, 'CartID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID');
    }
}