<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesTransaction;
use App\Models\Product;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $query = SalesTransaction::query();

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('customer_email', 'LIKE', "%{$search}%");
            });
        }

        // Apply date range filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Get paginated results with custom per_page
        $orders = $query->latest()->paginate($perPage)->withQueryString();
        
        // Get counts for status filters
        $pendingCount = SalesTransaction::where('status', 'pending')->count();
        $processingCount = SalesTransaction::where('status', 'processing')->count();
        $completedCount = SalesTransaction::where('status', 'completed')->count();
        $cancelledCount = SalesTransaction::where('status', 'cancelled')->count();

        return view('orders', compact(
            'orders', 
            'pendingCount', 
            'processingCount', 
            'completedCount', 
            'cancelledCount'
        ));
    }

    public function show(SalesTransaction $order)
    {
        return view('orders', compact('order'));
    }

    public function create()
    {
        return view('orders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string',
        ]);

        $order = SalesTransaction::create([
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
        ]);

        // Add items and calculate total
        $total = 0;
        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $subtotal = $product->price * $item['quantity'];
            $total += $subtotal;

            $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'subtotal' => $subtotal,
            ]);
        }

        $order->update(['grand_total' => $total]);

        return redirect()->route('orders')->with('success', 'Order created successfully.');
    }

    public function edit(SalesTransaction $order)
    {
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, SalesTransaction $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update($validated);

        return redirect()->route('orders')->with('success', 'Order updated successfully.');
    }

    public function destroy(SalesTransaction $order)
    {
        $order->items()->delete();
        $order->delete();

        return redirect()->route('orders')->with('success', 'Order deleted successfully.');
    }
}
