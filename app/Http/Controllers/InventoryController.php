<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class InventoryController extends Controller
{
    public function index()
    {
        // Paginate 10 products per page
        $products = Product::paginate(10);

        return view('inventory', compact('products'));
    }
}
