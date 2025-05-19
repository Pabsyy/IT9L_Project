<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cartitem;
use Illuminate\Support\Facades\Auth;
use App\Customerapp\Facades\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function view()
    {
        // Redirect to login if user is not authenticated
        if (!Auth::check()) {
            return redirect()->route('customer.login')->with('message', 'Please login or register to complete your purchase');
        }

        $cartItems = Cart::content();
        $subtotal = Cart::subtotal();
        $shipping = 150.00;
        $total = Cart::total() + $shipping;

        return view('checkout', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    public function process(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate stock availability for all items
            $cartItems = Cart::content();
            foreach ($cartItems as $item) {
                $product = Product::find($item->id);
                if (!$product) {
                    throw new \Exception("Product not found: {$item->name}");
                }

                if (!$product->isInStock()) {
                    throw new \Exception("Product out of stock: {$product->ProductName}");
                }

                if (!$product->hasStockFor($item->qty)) {
                    throw new \Exception("Insufficient stock for {$product->ProductName}. Available: {$product->stock}, Requested: {$item->qty}");
                }
            }

            // Create order
            $order = Order::create([
                'UserID' => auth()->id(),
                'total' => Cart::total(),
                'status' => 'processing',
                'reference_number' => 'ORD-' . time() . rand(1000, 9999)
            ]);

            // Process each item
            foreach ($cartItems as $item) {
                $product = Product::find($item->id);

                // Create order item
                $order->items()->create([
                    'product_id' => $product->ProductID,
                    'quantity' => $item->qty,
                    'price' => $item->price
                ]);

                // Create inventory movement
                InventoryMovement::create([
                    'product_id' => $product->ProductID,
                    'user_id' => auth()->id(),
                    'type' => 'sale',
                    'quantity' => -$item->qty,
                    'unit_cost' => $product->average_cost ?? 0,
                    'total_cost' => ($product->average_cost ?? 0) * $item->qty,
                    'reference_number' => $order->reference_number,
                    'notes' => "Order #{$order->id}",
                    'moved_at' => now()
                ]);

                // Update product stock
                $product->update([
                    'stock' => $product->stock - $item->qty,
                    'last_movement_at' => now()
                ]);
            }

            // Clear cart
            Cart::destroy();

            DB::commit();
            return redirect()->route('checkout.success', $order)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('cart.view')
                ->with('error', $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        return view('checkout.success', compact('order'));
    }
}
