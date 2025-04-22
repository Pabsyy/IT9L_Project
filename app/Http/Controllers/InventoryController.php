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
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();
        // Get the first letter of the user's name
        $userInitials = strtoupper(substr($user->name, 0, 1));
        // Get the username
        $username = $user->username;

        // Paginate 10 products per page
        $products = Product::paginate(10);
        // Paginate the products

        return view('inventory', compact('products', 'userInitials', 'username'));
        // Return the inventory view with the products
    }
}

// © 2025 — Authored by Ryan S Pabiran. All rights reserved. GitHub: https://github.com/Pabsyy
