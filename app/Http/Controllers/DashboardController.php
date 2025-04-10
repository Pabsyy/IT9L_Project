<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesTransaction; // Updated model
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch data from the database
        $totalRevenue = SalesTransaction::sum('GrandTotal'); // Updated model and column
        $totalOrders = SalesTransaction::count(); // Updated model
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $totalProducts = Product::count();
        $newProducts = Product::where('created_at', '>=', now()->subMonth())->count();
        $topSellingParts = Product::orderBy('sales', 'desc')->take(5)->get();
        $recentOrders = SalesTransaction::latest()->take(5)->get(); // Updated model
        $lowStockProducts = Product::select('ProductName', 'Quantity') // Use 'ProductName' instead of 'name'
            ->where('Quantity', '<=', 10)
            ->get();

        // Example growth calculations (replace with actual logic if needed)
        $revenueGrowth = 10; // Placeholder
        $orderGrowth = 5; // Placeholder
        $aovGrowth = 2; // Placeholder

        $data = [
            'totalRevenue' => $totalRevenue,
            'revenueGrowth' => $revenueGrowth,
            'totalOrders' => $totalOrders,
            'orderGrowth' => $orderGrowth,
            'averageOrderValue' => $averageOrderValue,
            'aovGrowth' => $aovGrowth,
            'totalProducts' => $totalProducts,
            'newProducts' => $newProducts,
            'topSellingParts' => $topSellingParts,
            'recentOrders' => $recentOrders,
            'lowStockProducts' => $lowStockProducts, // Pass lowStockProducts to the view
        ];

        return view('dashboard', $data);
    }
}
