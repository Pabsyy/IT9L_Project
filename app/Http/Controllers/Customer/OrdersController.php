<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.product', 'inventoryMovements']);
        return view('customer.orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Create the order
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => 0,
                'status' => 'pending',
                'reference_number' => 'ORD-' . time() . rand(1000, 9999)
            ]);

            $total = 0;

            // Process each item
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Check stock availability
                if (!$product->hasStockFor($item['quantity'])) {
                    throw new \Exception("Insufficient stock for product: {$product->ProductName}");
                }

                $subtotal = $product->Price * $item['quantity'];
                $total += $subtotal;

                // Create order item
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $product->Price
                ]);

                // Create inventory movement
                InventoryMovement::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'type' => 'sale',
                    'quantity' => -$item['quantity'],
                    'unit_cost' => $product->average_cost ?? 0,
                    'total_cost' => ($product->average_cost ?? 0) * $item['quantity'],
                    'reference_number' => $order->reference_number,
                    'notes' => "Order #{$order->id}",
                    'moved_at' => now()
                ]);

                // Update product stock
                $product->update([
                    'stock' => $product->stock - $item['quantity'],
                    'last_movement_at' => now()
                ]);
            }

            // Update order total
            $order->update(['total' => $total]);

            DB::commit();
            return redirect()->route('customer.orders.show', $order)
                ->with('success', 'Order placed successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function cancel(Order $order)
    {
        if ($order->UserID !== auth()->id()) {
            abort(403);
        }

        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()->back()
                ->with('error', 'This order cannot be cancelled.');
        }

        try {
            DB::beginTransaction();

            // Update order status
            $order->update(['status' => 'cancelled']);

            // Process each item
            foreach ($order->items as $item) {
                // Create return inventory movement
                InventoryMovement::create([
                    'product_id' => $item->product_id,
                    'user_id' => auth()->id(),
                    'type' => 'return',
                    'quantity' => $item->quantity,
                    'unit_cost' => $item->product->average_cost ?? 0,
                    'total_cost' => ($item->product->average_cost ?? 0) * $item->quantity,
                    'reference_number' => $order->reference_number,
                    'notes' => "Order #{$order->id} cancelled by customer",
                    'moved_at' => now()
                ]);

                // Restore product stock
                $item->product->update([
                    'stock' => $item->product->stock + $item->quantity,
                    'last_movement_at' => now()
                ]);
            }

            DB::commit();
            return redirect()->back()
                ->with('success', 'Order cancelled successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Failed to cancel order. Please try again.');
        }
    }
} 