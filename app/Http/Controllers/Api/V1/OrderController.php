<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return auth()->user()->orders()->with('items.product')->paginate(10);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_address_id' => 'required|exists:addresses,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);
        return auth()->user()->orders()->create($validated);
    }

    public function show(Order $order)
    {
        return $order->load(['items.product', 'shippingAddress', 'paymentMethod']);
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);
        $order->update($validated);
        return $order;
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->noContent();
    }
} 