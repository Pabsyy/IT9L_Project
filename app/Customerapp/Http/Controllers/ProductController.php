<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Apply sorting
        switch ($request->get('sort')) {
            case 'price_asc':
                $query->orderBy('Price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('Price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'best_selling':
                // Default to ProductID if sales_count doesn't exist
                $query->orderBy('ProductID', 'desc');
                break;
            case 'featured':
            default:
                // Default sorting by ProductID
                $query->orderBy('ProductID', 'desc');
                break;
        }

        // Category filter
        if ($request->has('category')) {
            $category = str_replace('-', ' ', $request->category);
            $query->where('Category', 'like', '%' . $category . '%');
        }

        // Manufacturer filter
        if ($request->has('manufacturers')) {
            $query->where(function($q) use ($request) {
                foreach($request->manufacturers as $manufacturer) {
                    $q->orWhere('Manufacturer', 'like', '%' . $manufacturer . '%');
                }
            });
        }

        // Price range filter
        if ($request->has('min_price') && is_numeric($request->min_price)) {
            $query->where('Price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && is_numeric($request->max_price)) {
            $query->where('Price', '<=', $request->max_price);
        }

        $products = $query->paginate(12)->withQueryString();
        return view('products.product', compact('products'));
    }

    public function show($ProductID)
    {
        $product = Product::findOrFail($ProductID);
        return view('products.show', compact('product'));
    }

    public function byCategory($category)
    {
        $products = Product::where('Category', $category)->paginate(12);
        return view('products.product', compact('products'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        
        $products = Product::where('ProductName', 'like', "%{$search}%")
            ->orWhere('Description', 'like', "%{$search}%")
            ->orWhere('Category', 'like', "%{$search}%")
            // Remove the SKU search if the column doesn't exist
            ->paginate(12);

        return view('products.product', compact('products'));
    }
}
