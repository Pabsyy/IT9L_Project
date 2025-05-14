<?php
// Start of PHP code

namespace App\Http\Controllers;
// Define the namespace for the controllers

use Illuminate\Http\Request;
// Import the Request class
use App\Models\Product;
// Import the Product model
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

        $query = Product::query();

        if ($request->filled('category')) {
            $query->where('Category', $request->category);
        }

        if ($request->filled('brand')) {
            $query->where('Brand', $request->brand);
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
            $query->where('Price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('Price', '<=', $request->price_max);
        }

        $products = $query->paginate(8);

        return view('inventory', compact('products', 'userInitials', 'username'));
        // Return the inventory view with the products
    }
}

// © 2025 — Authored by Ryan S Pabiran. All rights reserved. GitHub: https://github.com/Pabsyy
