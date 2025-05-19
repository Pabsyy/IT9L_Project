<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function adminIndex()
    {
        $pendingCount = Order::where('status', 'pending')->count();
        $processingCount = Order::where('status', 'processing')->count();
        $completedCount = Order::where('status', 'completed')->count();
        $cancelledCount = Order::where('status', 'cancelled')->count();

        $orders = Order::with(['user', 'items.product', 'supplier'])
            ->when(request('status'), function($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        return view('admin.orders', compact('orders', 'pendingCount', 'processingCount', 'completedCount', 'cancelledCount'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'supplier']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully.');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $request->total,
                'status' => 'pending'
            ]);

            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error processing order: ' . $e->getMessage()
            ]);
        }
    }

    public function edit(Order $order)
    {
        $order->load(['user', 'items.product', 'supplier']);
        $suppliers = Supplier::where('status', 'active')->orderBy('name')->get();
        return view('admin.orders.edit', compact('order', 'suppliers'));
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders')->with('success', 'Order deleted successfully.');
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'supplier_id' => 'nullable|exists:suppliers,id'
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders')->with('success', 'Order updated successfully.');
    }
}
