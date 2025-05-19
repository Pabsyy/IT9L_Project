<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    public static function remember($key, $ttl, $callback)
    {
        return Cache::remember($key, $ttl, $callback);
    }

    public static function tags($tags, $key, $ttl, $callback)
    {
        return Cache::remember($key, $ttl, $callback);
    }

    public static function flush($tags = null)
    {
        Cache::flush();
    }

    public static function getProductsCache($category = null)
    {
        $key = 'products' . ($category ? "_{$category}" : '');
        $tags = ['products', $category ? "category_{$category}" : 'all'];
        
        return self::tags($tags, $key, now()->addHours(24), function () use ($category) {
            $query = \App\Models\Product::with(['category', 'brand']);
            
            if ($category) {
                $query->where('category_id', $category);
            }
            
            return $query->get();
        });
    }

    public static function getCategoriesCache()
    {
        return self::remember('categories', now()->addDays(7), function () {
            return \App\Models\Category::withCount('products')->get();
        });
    }

    public static function getPopularProductsCache()
    {
        return self::remember('popular_products', now()->addHours(6), function () {
            return \App\Models\Product::withCount('orders')
                ->orderBy('orders_count', 'desc')
                ->take(10)
                ->get();
        });
    }
} 