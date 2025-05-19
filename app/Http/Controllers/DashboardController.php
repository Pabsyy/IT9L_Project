<?php
// Start of PHP code

namespace App\Http\Controllers;
// Define the namespace for the controllers

use Illuminate\Http\Request;
// Import the Request class
use App\Models\SalesTransaction;
// Import the SalesTransaction model
use App\Models\Product;
// Import the Product model
use App\Models\Activity; // Add this if you have an Activity model
// Import the Activity model
use Illuminate\Support\Facades\DB;
// Import the DB facade
use Illuminate\Support\Facades\Auth;
// Import the Auth facade
use App\Models\Category;
// Import the Category model

class DashboardController extends Controller
{
    // Index method to display the dashboard
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // Get the user's initials from first_name and last_name
        $userInitials = strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1));
        
        // Get the username (generated from first_name and last_name)
        $username = $user->username;

        // Check system status
        $systemStatus = $this->checkSystemStatus();
        // Call the checkSystemStatus method

        // Fetch data from the database
        $totalRevenue = SalesTransaction::sum('grand_total'); // Renamed from 'GrandTotal'
        $totalOrders = SalesTransaction::count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        // Calculate the average order value
        $totalProducts = Product::count();
        // Count the total number of products
        $newProducts = Product::where('created_at', '>=', now()->subMonth())->count();
        // Count the number of new products added in the last month
        $topSellingParts = Product::orderBy('sales', 'desc')->take(5)->get(); // Ensure 'sales' column exists
        // Get the top 5 selling products
        
        // Fetch recent orders/transactions
        $recentTransactions = SalesTransaction::with('user')  // Add eager loading for user relationship
            ->latest()
            ->take(5)
            ->get();
        // Get the 5 most recent transactions

        // Calculate inventory value (sum of price * stock for all products)
        $inventoryValue = Product::sum(DB::raw('price * stock'));
        // Calculate the inventory value

        // Calculate inventory value for the previous month
        $lastMonth = now()->subMonth()->month;
        $lastYear = now()->subMonth()->year;
        $lastMonthValue = Product::whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $lastYear)
            ->sum(DB::raw('price * stock'));
        // Calculate the inventory value from last month
        $inventoryGrowth = $lastMonthValue > 0 
            ? (($inventoryValue - $lastMonthValue) / $lastMonthValue) * 100 
            : 0;
        // Calculate the inventory growth
        
        // Get low stock products
        $lowStockProducts = Product::where('stock', '<=', 10)->get();
        // Get the products with low stock
        $lowStockCount = $lowStockProducts->count();
        // Count the number of products with low stock
        $newLowStock = Product::where('stock', '<=', 10)
            ->where('updated_at', '>=', now()->subDay())
            ->count();
        // Count the number of new products with low stock

        // Calculate order growth
        $lastMonthOrders = SalesTransaction::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        // Count the number of orders from last month
        $orderGrowth = $lastMonthOrders > 0 
            ? (($totalOrders - $lastMonthOrders) / $lastMonthOrders) * 100 
            : 0;
        // Calculate the order growth

        // Get product categories with counts
        $categories = Category::withCount('products')
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'total_products' => $category->products_count
                ];
            });
        // Get the product categories

        // Get monthly revenues for the chart
        $monthlyRevenues = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyRevenue = SalesTransaction::whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->sum('grand_total'); // Renamed from 'GrandTotal'
            $monthlyRevenues[] = floatval($monthlyRevenue);
        }
        // Get the monthly revenues

        // Calculate monthly revenue
        $monthlyRevenue = SalesTransaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('grand_total'); // Renamed from 'GrandTotal'

        // Calculate revenue growth
        $lastMonthRevenue = SalesTransaction::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('grand_total'); // Renamed from 'GrandTotal'
        
        $revenueGrowth = $lastMonthRevenue > 0 
            ? (($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;
        // Calculate the revenue growth

        $data = [
            'totalInventoryValue' => $inventoryValue,
            'inventoryGrowth' => round($inventoryGrowth, 1),
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'orderGrowth' => $orderGrowth,
            'averageOrderValue' => $averageOrderValue,
            'totalProducts' => $totalProducts,
            'newProducts' => $newProducts,
            'topSellingParts' => $topSellingParts,
            'recentTransactions' => $recentTransactions,
            'lowStockProducts' => $lowStockProducts,
            'lowStockItems' => $lowStockCount,
            'newLowStock' => $newLowStock,
            'systemStatus' => $systemStatus,
            'categories' => $categories,
            'monthlyRevenues' => $monthlyRevenues,
            'monthlyRevenue' => $monthlyRevenue,
            'revenueGrowth' => $revenueGrowth,
            'userInitials' => $userInitials,
            'username' => $username,
        ];

        return view('dashboard', $data);
    }

    private function checkSystemStatus()
    {
        try {
            // Check database connection
            DB::connection()->getPdo();
            
            // You can add more checks here (e.g., cache, queue, storage)
            
            return [
                'status' => 'online',
                'color' => 'bg-green-500'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'offline',
                'color' => 'bg-red-500'
            ];
        }
    }
    // Check the system status

    public function getRevenueData(Request $request)
    {
        $period = $request->period ?? 'monthly';
        
        switch ($period) {
            case 'quarterly':
                $revenues = [];
                for ($quarter = 1; $quarter <= 4; $quarter++) {
                    $startMonth = ($quarter - 1) * 3 + 1;
                    $revenue = SalesTransaction::whereYear('created_at', now()->year)
                        ->whereMonth('created_at', '>=', $startMonth)
                        ->whereMonth('created_at', '<=', $startMonth + 2)
                        ->sum('grand_total'); // Renamed from 'GrandTotal'
                    $revenues[] = floatval($revenue);
                }
                return response()->json([
                    'labels' => ['Q1', 'Q2', 'Q3', 'Q4'],
                    'data' => $revenues,
                    'currency' => '₱'  // Add PHP currency symbol
                ]);

            case 'yearly':
                $revenues = [];
                for ($year = now()->year - 4; $year <= now()->year; $year++) {
                    $revenue = SalesTransaction::whereYear('created_at', $year)
                        ->sum('grand_total'); // Renamed from 'GrandTotal'
                    $revenues[] = floatval($revenue);
                }
                return response()->json([
                    'labels' => range(now()->year - 4, now()->year),
                    'data' => $revenues,
                    'currency' => '₱'  // Add PHP currency symbol
                ]);

            default: // monthly
                $monthlyRevenues = [];
                for ($i = 1; $i <= 12; $i++) {
                    $monthlyRevenue = SalesTransaction::whereMonth('created_at', $i)
                        ->whereYear('created_at', now()->year)
                        ->sum('grand_total'); // Renamed from 'GrandTotal'
                    $monthlyRevenues[] = floatval($monthlyRevenue);
                }
                return response()->json([
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    'data' => $monthlyRevenues,
                    'currency' => '₱'  // Add PHP currency symbol
                ]);
        }
    }
    // Get the revenue data
}

// © 2025 — Authored by Ryan S Pabiran. All rights reserved. GitHub: https://github.com/Pabsyy
