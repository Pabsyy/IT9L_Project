<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'ProductID';
    public $timestamps = true;

    protected $fillable = [
        'SupplierID',
        'ProductName',
        'SKU',
        'Description',
        'Image',
        'Price',
        'Quantity',
        'Category',
        'sales',
        'stock',
        'min_stock_threshold',
        'last_stocked_at',
        'last_movement_at'
    ];

    /**
     * Get the inventory movements for this product
     */
    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class, 'product_id');
    }

    /**
     * Scope a query to filter by category
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('Category', $category);
    }

    /**
     * Get the formatted price with currency symbol
     */
    public function getFormattedPrice()
    {
        return '₱' . number_format($this->Price, 2);
    }

    /**
     * Get the stock status with detailed information
     */
    public function getStockStatus()
    {
        if ($this->stock <= 0) {
            return [
                'status' => 'Out of Stock',
                'class' => 'text-red-600',
                'icon' => 'ri-close-circle-fill'
            ];
        } elseif ($this->stock <= ($this->min_stock_threshold ?? 10)) {
            return [
                'status' => 'Low Stock',
                'class' => 'text-yellow-600',
                'icon' => 'ri-error-warning-fill'
            ];
        } else {
            return [
                'status' => 'In Stock',
                'class' => 'text-green-600',
                'icon' => 'ri-checkbox-circle-fill'
            ];
        }
    }

    /**
     * Check if product is in stock
     */
    public function isInStock()
    {
        return $this->stock > 0;
    }

    /**
     * Check if product has sufficient stock for quantity
     */
    public function hasStockFor($quantity)
    {
        return $this->stock >= $quantity;
    }

    /**
     * Get the product's image URL
     */
    public function getImageUrl()
    {
        return asset('images/Customerpanel/' . $this->Image);
    }

    /**
     * Get recent stock movements
     */
    public function getRecentMovements($limit = 5)
    {
        return $this->inventoryMovements()
            ->orderBy('moved_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Get stock movement history for a date range
     */
    public function getStockMovementHistory($startDate = null, $endDate = null)
    {
        $query = $this->inventoryMovements();
        
        if ($startDate) {
            $query->where('moved_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('moved_at', '<=', $endDate);
        }
        
        return $query->orderBy('moved_at', 'desc')->get();
    }
}

