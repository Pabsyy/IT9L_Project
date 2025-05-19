<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class);
    }

    public function getClasses()
    {
        $classes = [
            'Electronics' => 'bg-blue-50 text-blue-700',
            'Hardware' => 'bg-purple-50 text-purple-700',
            'Automotive' => 'bg-green-50 text-green-700',
            'Industrial' => 'bg-blue-50 text-blue-700',
            'Packaging' => 'bg-orange-50 text-orange-700',
            'Materials' => 'bg-teal-50 text-teal-700',
            'Components' => 'bg-red-50 text-red-700',
            'Tools' => 'bg-indigo-50 text-indigo-700',
            'Precision' => 'bg-blue-50 text-blue-700',
            'Logistics' => 'bg-yellow-50 text-yellow-700',
            'Transport' => 'bg-orange-50 text-orange-700',
            'Sustainable' => 'bg-teal-50 text-teal-700',
            'Raw Materials' => 'bg-purple-50 text-purple-700',
            'Metals' => 'bg-indigo-50 text-indigo-700'
        ];

        return $classes[$this->name] ?? 'bg-gray-50 text-gray-700';
    }
} 