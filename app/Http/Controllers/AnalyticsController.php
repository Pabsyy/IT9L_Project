<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Supplier;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;

// AnalyticsController handles analytics-related requests
class AnalyticsController extends Controller
{
    // Index method to display the analytics dashboard
    public function index(Request $request)
    {
        try {
            // Get date range from request or default to 30 days
            $days = $request->input('date_range', 30);
            $startDate = Carbon::now()->subDays($days);
            
            // Check if there are any products
            $hasProducts = Product::count() > 0;

            // Inventory Analytics
            $inventoryMetrics = [
                'total_value' => $this->calculateTotalInventoryValue($startDate),
                'low_stock_items' => $this->getLowStockItems($hasProducts),
                'out_of_stock' => $this->getOutOfStockCount($hasProducts),
                'stock_turnover' => $this->calculateStockTurnover($startDate),
                'movement_activity' => $this->getMovementActivity($startDate),
                'movement_trend' => $this->getMovementTrend(),
                'recent_movements' => $this->getRecentMovements(),
                'low_stock_alerts' => $this->getLowStockAlerts()
            ];

            // Product Performance
            $productMetrics = [
                'top_products' => $this->getTopProducts(),
                'category_distribution' => $this->getCategoryDistribution($hasProducts),
                'brand_performance' => $this->getBrandPerformance($hasProducts),
                'price_distribution' => $this->getPriceDistribution()
            ];

            // Financial Metrics
            $financialMetrics = [
                'monthly_value_trend' => $this->getMonthlyValueTrend(),
                'category_value' => $this->getCategoryValue(),
                'avg_product_value' => $this->getAverageProductValue($startDate)
            ];

            return view('admin.analytics', compact(
                'inventoryMetrics',
                'productMetrics',
                'financialMetrics'
            ));
        } catch (\Exception $e) {
            \Log::error('Error in analytics dashboard: ' . $e->getMessage());
            return $this->getDefaultMetrics();
        }
    }

    private function calculateTotalInventoryValue($startDate)
    {
        $currentValue = Product::sum(DB::raw('stock * average_cost'));
        $previousValue = Product::where('updated_at', '<=', $startDate)
            ->sum(DB::raw('stock * average_cost'));

        $growth = $previousValue > 0 ? 
            (($currentValue - $previousValue) / $previousValue) * 100 : 0;

        return [
            'current' => $currentValue,
            'growth' => $growth
        ];
    }

    private function getLowStockItems($hasProducts = null)
    {
        $hasProducts = $hasProducts ?? Product::count() > 0;
        $lowStockCount = Product::whereBetween('stock', [1, 10])->count();

        return [
            'count' => $lowStockCount,
            'percentage' => $hasProducts ? ($lowStockCount / Product::count()) * 100 : 0
        ];
    }

    private function getOutOfStockCount($hasProducts = null)
    {
        $hasProducts = $hasProducts ?? Product::count() > 0;
        $outOfStockCount = Product::where('stock', 0)->count();

        return [
            'count' => $outOfStockCount,
            'percentage' => $hasProducts ? ($outOfStockCount / Product::count()) * 100 : 0
        ];
    }

    private function calculateStockTurnover($startDate)
    {
        $currentPeriodSales = InventoryMovement::where('type', 'sale')
            ->where('moved_at', '>=', $startDate)
            ->sum('quantity');

        $averageInventory = Product::avg('stock');

        $currentRate = $averageInventory > 0 ? 
            abs($currentPeriodSales) / $averageInventory : 0;

        $previousPeriodSales = InventoryMovement::where('type', 'sale')
            ->where('moved_at', '<', $startDate)
            ->where('moved_at', '>=', $startDate->copy()->subDays(30))
            ->sum('quantity');

        $previousRate = $averageInventory > 0 ? 
            abs($previousPeriodSales) / $averageInventory : 0;

        $change = $previousRate > 0 ? 
            (($currentRate - $previousRate) / $previousRate) * 100 : 0;

        return [
            'rate' => $currentRate,
            'change' => $change
        ];
    }

    private function getMovementActivity($startDate)
    {
        return [
            'stock_in' => InventoryMovement::where('type', 'purchase')
                ->where('moved_at', '>=', $startDate)
                ->count(),
            'stock_out' => InventoryMovement::whereIn('type', ['sale', 'damage', 'return'])
                ->where('moved_at', '>=', $startDate)
                ->count(),
            'adjustments' => InventoryMovement::where('type', 'adjustment')
                ->where('moved_at', '>=', $startDate)
                ->count()
        ];
    }

    private function getMovementTrend()
    {
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $stockIn = [];
        $stockOut = [];

        foreach ($days as $day) {
            $date = Carbon::parse($day);
            $stockIn[] = InventoryMovement::where('type', 'purchase')
                ->whereRaw('DAYNAME(moved_at) = ?', [$day])
                ->count();
            $stockOut[] = InventoryMovement::whereIn('type', ['sale', 'damage', 'return'])
                ->whereRaw('DAYNAME(moved_at) = ?', [$day])
                ->count();
        }

        return [
            'stock_in' => $stockIn,
            'stock_out' => $stockOut
        ];
    }

    private function getRecentMovements($limit = 5)
    {
        return InventoryMovement::with(['product', 'user'])
            ->orderBy('moved_at', 'desc')
            ->take($limit)
            ->get()
            ->map(function ($movement) {
                return [
                    'product_name' => $movement->product->name,
                    'type' => $movement->type,
                    'quantity' => abs($movement->quantity),
                    'date' => $movement->moved_at->diffForHumans()
                ];
            });
    }

    private function getLowStockAlerts($limit = 5)
    {
        return Product::whereBetween('stock', [1, 10])
            ->orderBy('stock')
            ->take($limit)
            ->get()
            ->map(function ($product) {
                return [
                    'product_name' => $product->name,
                    'current_stock' => $product->stock,
                    'min_stock' => 10
                ];
            });
    }

    private function getTopProducts($limit = 5)
    {
        return Product::orderByRaw('stock * average_cost DESC')
            ->take($limit)
            ->get()
            ->map(function ($product) {
                return [
                    'name' => $product->name,
                    'stock' => $product->stock,
                    'value' => $product->stock * $product->average_cost
                ];
            });
    }

    private function getCategoryDistribution($hasProducts = null)
    {
        $hasProducts = $hasProducts ?? Product::count() > 0;
        $totalProducts = Product::count();

        return Category::withCount('products')
            ->get()
            ->map(function ($category) use ($hasProducts, $totalProducts) {
                return [
                    'name' => $category->name,
                    'count' => $category->products_count,
                    'percentage' => $hasProducts ? 
                        ($category->products_count / $totalProducts) * 100 : 0
                ];
            });
    }

    private function getPriceDistribution()
    {
        $ranges = [
            '0-100' => [0, 100],
            '101-500' => [101, 500],
            '501-1000' => [501, 1000],
            '1001+' => [1001, null]
        ];

        $distribution = [];
        foreach ($ranges as $label => [$min, $max]) {
            $query = Product::where('price', '>=', $min);
            if ($max) {
                $query->where('price', '<=', $max);
            }
            $distribution[$label] = $query->count();
        }

        return $distribution;
    }

    private function getMonthlyValueTrend()
    {
        return collect(range(5, 0))
            ->map(function ($month) {
                $date = Carbon::now()->subMonths($month);
                return [
                    'month' => $date->format('M'),
                    'value' => Product::whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->sum(DB::raw('stock * average_cost'))
                ];
            });
    }

    private function getCategoryValue()
    {
        return Category::with('products')
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'value' => $category->products->sum(function ($product) {
                        return $product->stock * $product->average_cost;
                    })
                ];
            });
    }

    private function getAverageProductValue($startDate)
    {
        $current = Product::avg(DB::raw('stock * average_cost'));
        $previous = Product::where('updated_at', '<=', $startDate)
            ->avg(DB::raw('stock * average_cost'));

        return [
            'current' => $current,
            'change' => $previous > 0 ? (($current - $previous) / $previous) * 100 : 0
        ];
    }

    private function getBrandPerformance($hasProducts = null)
    {
        $hasProducts = $hasProducts ?? Product::count() > 0;
        $totalProducts = Product::count();

        return Brand::withCount('products')
            ->get()
            ->map(function ($brand) use ($hasProducts, $totalProducts) {
                return [
                    'name' => $brand->name,
                    'count' => $brand->products_count,
                    'percentage' => $hasProducts ? ($brand->products_count / $totalProducts) * 100 : 0
                ];
            });
    }

    private function getDefaultMetrics()
    {
        return view('admin.analytics', [
            'inventoryMetrics' => [
                'total_value' => ['current' => 0, 'growth' => 0],
                'low_stock_items' => ['count' => 0, 'percentage' => 0],
                'out_of_stock' => ['count' => 0, 'percentage' => 0],
                'stock_turnover' => ['rate' => 0, 'change' => 0],
                'movement_activity' => ['stock_in' => 0, 'stock_out' => 0, 'adjustments' => 0],
                'movement_trend' => ['stock_in' => array_fill(0, 7, 0), 'stock_out' => array_fill(0, 7, 0)],
                'recent_movements' => [],
                'low_stock_alerts' => []
            ],
            'productMetrics' => [
                'top_products' => [],
                'category_distribution' => collect([]),
                'brand_performance' => collect([]),
                'price_distribution' => [
                    '0-100' => 0,
                    '101-500' => 0,
                    '501-1000' => 0,
                    '1001+' => 0
                ]
            ],
            'financialMetrics' => [
                'monthly_value_trend' => collect(range(5, 0))->map(function ($month) {
                    return ['month' => Carbon::now()->subMonths($month)->format('M'), 'value' => 0];
                }),
                'category_value' => collect([]),
                'avg_product_value' => ['current' => 0, 'change' => 0]
            ]
        ])->withErrors(['error' => 'Unable to load analytics data. Please try again later.']);
    }
}

// © 2025 — Authored by Ryan S Pabiran. All rights reserved. GitHub: https://github.com/Pabsyy
