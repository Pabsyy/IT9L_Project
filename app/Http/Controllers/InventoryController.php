<?php
// Start of PHP code

namespace App\Http\Controllers;
// Define the namespace for the controllers

use Illuminate\Http\Request;
// Import the Request class
use App\Models\Product;
// Import the Product model
use App\Models\Category;
// Import the Category model
use App\Models\Brand;
// Import the Brand model
use App\Models\Supplier;
// Import the Supplier model
use Illuminate\Support\Facades\Auth;
// Import the Auth facade

class InventoryController extends Controller
{
    // Index method to display the inventory
    public function index(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();
        // Get the first letter of the user's name
        $userInitials = strtoupper(substr($user->name, 0, 1));
        // Get the username
        $username = $user->username;

        // Get categories, brands and suppliers for filters
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $suppliers = Supplier::where('status', 'active')->orderBy('name')->get();

        $query = Product::query();

        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($request->filled('brand')) {
            $brand = Brand::where('slug', $request->brand)->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status == 'instock') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock_status == 'lowstock') {
                $query->whereBetween('stock', [1, 10]);
            } elseif ($request->stock_status == 'outofstock') {
                $query->where('stock', '=', 0);
            }
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Calculate inventory metrics
        try {
            $inventoryMetrics = [
                'total_value' => Product::sum(\DB::raw('price * stock')) ?? 0,
                'low_stock' => Product::whereBetween('stock', [1, 10])->count() ?? 0,
                'out_of_stock' => Product::where('stock', 0)->count() ?? 0,
                'total_products' => Product::count() ?? 0
            ];
        } catch (\Exception $e) {
            // Set default values if calculation fails
            $inventoryMetrics = [
                'total_value' => 0,
                'low_stock' => 0,
                'out_of_stock' => 0,
                'total_products' => 0
            ];
            \Log::error('Error calculating inventory metrics: ' . $e->getMessage());
        }

        $products = $query->paginate(8);

        return view('admin.inventory', compact(
            'products',
            'categories',
            'brands',
            'suppliers',
            'userInitials',
            'username',
            'inventoryMetrics'
        ));
        // Return the inventory view with the products
    }
}

// © 2025 — Authored by Ryan S Pabiran. All rights reserved. GitHub: https://github.com/Pabsyy
