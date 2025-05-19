<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageService;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'brand_id',
        'supplier_id',
        'sku',
        'featured',
        'main_image',
        'image_1',
        'image_2',
        'image_3',
        'image_4',
        'sales',
        'average_cost',
        'last_stocked_at',
        'last_movement_at',
        'average_rating',
        'rating_count'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'featured' => 'boolean',
        'sales' => 'integer',
        'average_cost' => 'decimal:2',
        'last_stocked_at' => 'datetime',
        'last_movement_at' => 'datetime',
        'average_rating' => 'decimal:2',
        'rating_count' => 'integer'
    ];

    /**
     * Get the category that owns the product
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the brand that owns the product
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the supplier that owns the product
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the ratings for the product
     */
    public function ratings()
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * Get the wishlists for the product
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the inventory movements for the product
     */
    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    /**
     * Get the reviews for the product
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * Get the average rating for the product
     */
    public function getAverageRatingAttribute()
    {
        return $this->attributes['average_rating'] ?? 0;
    }

    /**
     * Get the review count for the product
     */
    public function getReviewCountAttribute()
    {
        return $this->attributes['rating_count'] ?? 0;
    }

    /**
     * Check if the product is in a user's wishlist
     */
    public function isInWishlist($userId)
    {
        return $this->wishlists()->where('user_id', $userId)->exists();
    }

    /**
     * Scope a query to filter by category
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get all images for the product
     */
    public function getAllImages(): array
    {
        $images = [];
        
        // Add main image
        if ($this->main_image) {
            $images['main'] = $this->main_image;
        }
        
        // Add additional images
        for ($i = 1; $i <= 4; $i++) {
            $field = "image_{$i}";
            if ($this->$field) {
                $images[$field] = $this->$field;
            }
        }
        
        return $images;
    }

    /**
     * Get the image URL for any image field (main_image, image_1, ...)
     */
    public function getImageUrl($field = 'main_image')
    {
        $path = $this->$field;
        if (!$path) {
            return asset('images/products/default.jpg');
        }
        
        // Remove any storage/products prefix if it exists
        $path = preg_replace('#^storage/products/#', '', $path);
        $path = trim($path, '/');
        
        // Use direct file path
        return url('storage/app/products/' . $path);
    }

    /**
     * Get the main image URL for the product (for attribute access)
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->getImageUrl('main_image');
    }

    /**
     * Get all image URLs for the product
     */
    public function getAllImageUrls(): array
    {
        $urls = [
            'main' => $this->getImageUrlAttribute()
        ];

        // Add additional images if they exist
        for ($i = 1; $i <= 4; $i++) {
            $field = "image_{$i}";
            if ($this->$field) {
                // Remove any duplicate storage/products prefix if it exists
                $path = preg_replace('#^storage/products/#', '', $this->$field);
                
                // Ensure the path is properly formatted
                $path = trim($path, '/');
                
                // Use the products disk URL
                $urls["image{$i}"] = Storage::disk('products')->url($path);
            }
        }

        return $urls;
    }

    /**
     * Get the default image URL for the product
     */
    public function getDefaultImageUrl(): string
    {
        return asset('images/products/default.jpg');
    }

    /**
     * Get the stock status
     */
    public function getStockStatus(): array
    {
        if ($this->stock <= 0) {
            return [
                'status' => 'Out of Stock',
                'class' => 'text-red-600',
                'icon' => 'ri-close-circle-fill'
            ];
        } elseif ($this->stock <= 10) {
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
     * Get the formatted price
     */
    public function getFormattedPrice(): string
    {
        return '₱' . number_format($this->price, 2);
    }

    /**
     * Check if the product is in stock
     */
    public function isInStock()
    {
        return $this->stock > 0;
    }

    /**
     * Check if the product has enough stock for the requested quantity
     */
    public function hasStockFor($quantity)
    {
        return $this->stock >= $quantity;
    }

    /**
     * Check if the product is low in stock
     */
    public function isLowStock()
    {
        return $this->stock > 0 && $this->stock <= 10;
    }

    /**
     * Get the formatted average cost
     */
    public function getFormattedAverageCost()
    {
        return '₱' . number_format($this->average_cost ?? 0, 2);
    }

    /**
     * Get the total value of the product
     */
    public function getTotalValue()
    {
        return $this->stock * ($this->average_cost ?? $this->price);
    }

    /**
     * Get the formatted total value
     */
    public function getFormattedTotalValue()
    {
        return '₱' . number_format($this->getTotalValue(), 2);
    }
}

