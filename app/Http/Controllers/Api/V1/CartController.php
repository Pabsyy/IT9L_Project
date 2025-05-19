<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return auth()->user()->cart()->with('product')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        return auth()->user()->cart()->create($validated);
    }

    public function show(Cart $cart)
    {
        return $cart->load('product');
    }

    public function update(Request $request, Cart $cart)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        $cart->update($validated);
        return $cart;
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();
        return response()->noContent();
    }
} 