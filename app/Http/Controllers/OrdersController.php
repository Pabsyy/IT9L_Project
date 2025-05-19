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
        $showAll = $request->get('show_all', false);

        $query = SalesTransaction::query()
            ->withCount('items')
            ->with(['items', 'user'])
            ->select('sales_transactions.*');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_id', 'LIKE', "%{$search}%")
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
            $query->where('order_status', $request->status);
        }

        // Get counts for status filters
        $pendingCount = SalesTransaction::where('order_status', 'pending')->count();
        $processingCount = SalesTransaction::where('order_status', 'processing')->count();
        $completedCount = SalesTransaction::where('order_status', 'completed')->count();
        $cancelledCount = SalesTransaction::where('order_status', 'cancelled')->count();
        $deliveredCount = SalesTransaction::where('order_status', 'delivered')->count();

        // Get results based on pagination preference
        $orders = $showAll ? 
            $query->orderBy('order_id')->get() : 
            $query->orderBy('order_id')->paginate($perPage)->withQueryString();

        return view('admin.orders', compact(
            'orders', 
            'pendingCount', 
            'processingCount', 
            'completedCount', 
            'cancelledCount',
            'deliveredCount',
            'showAll',
            'perPage'
        ));
    }

    public function show(SalesTransaction $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function create()
    {
        $products = Product::all(['id', 'name', 'price']);
        return view('orders.create', compact('products'));
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
            'order_status' => 'pending',
            'subtotal' => 0,
            'tax' => 0,
            'shipping_fee' => 0,
            'discount' => 0,
            'grand_total' => 0,
            'user_id' => auth()->id(),
            'reference_number' => 'ORD-' . time() . rand(1000, 9999)
        ]);

        // Add items and calculate total
        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $itemSubtotal = $product->price * $item['quantity'];
            $subtotal += $itemSubtotal;

            $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $product->price,
                'subtotal' => $itemSubtotal
            ]);
        }

        // Calculate other amounts
        $tax = $subtotal * 0.12; // 12% tax
        $shippingFee = 0; // Can be calculated based on your business logic
        $discount = 0; // Can be calculated based on your business logic
        $grandTotal = $subtotal + $tax + $shippingFee - $discount;

        // Update the order with calculated totals
        $order->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping_fee' => $shippingFee,
            'discount' => $discount,
            'grand_total' => $grandTotal
        ]);

        return redirect()->route('admin.orders')->with('success', 'Order created successfully.');
    }

    public function updateStatus(Request $request, SalesTransaction $order)
    {
        $validated = $request->validate([
            'order_status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order status updated successfully.');
    }

    public function destroy(SalesTransaction $order)
    {
        $order->items()->delete();
        $order->delete();

        return redirect()->route('admin.orders')->with('success', 'Order deleted successfully.');
    }
}
