<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'status',
        'rating',
        'on_time_delivery_rate'
    ];

    protected $casts = [
        'rating' => 'float',
        'on_time_delivery_rate' => 'float',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_supplier');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function getIconBackground()
    {
        return 'bg-indigo-100';
    }

    public function getIcon()
    {
        return 'ri-building-line';
    }

    public function getIconColor()
    {
        return 'text-indigo-500';
    }

    public function getStatusClasses()
    {
        return match($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'inactive' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
