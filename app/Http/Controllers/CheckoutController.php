<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cartitem;
use Illuminate\Support\Facades\Auth;
use App\Customerapp\Facades\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class CheckoutController extends Controller
{
    public function showShipping()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('message', 'Please login to continue checkout');
        }

        $cartItems = Cart::content();
        $subtotal = Cart::subtotal();
        $shipping = 150.00;
        $tax = $subtotal * 0.12;
        $total = $subtotal + $shipping + $tax;

        // Get user's default address
        $defaultAddress = Auth::user()->addresses()->where('is_default', true)->first();

        return view('customer.checkout.shipping', compact('cartItems', 'subtotal', 'shipping', 'tax', 'total', 'defaultAddress'));
    }

    public function saveShipping(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'delivery_method' => 'required|in:delivery,pickup'
        ]);

        session(['checkout.shipping' => $validated]);

        return redirect()->route('customer.checkout.payment');
    }

    public function showPayment()
    {
        if (!session()->has('checkout.shipping')) {
            return redirect()->route('customer.checkout.shipping');
        }

        $cartItems = Cart::content();
        $subtotal = Cart::subtotal();
        $shipping = 150.00;
        $tax = $subtotal * 0.12;
        $total = $subtotal + $shipping + $tax;

        return view('customer.checkout.payment', compact('cartItems', 'subtotal', 'shipping', 'tax', 'total'));
    }

    public function savePayment(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:credit_card,gcash,paypal',
            'card_number' => 'required_if:payment_method,credit_card',
            'expiry_date' => 'required_if:payment_method,credit_card',
            'cvv' => 'required_if:payment_method,credit_card'
        ]);

        session(['checkout.payment' => $validated]);

        return redirect()->route('customer.checkout.review');
    }

    public function showReview()
    {
        if (!session()->has('checkout.shipping') || !session()->has('checkout.payment')) {
            return redirect()->route('customer.checkout.shipping');
        }

        $cartItems = Cart::content();
        $subtotal = Cart::subtotal();
        $shipping = 150.00;
        $tax = $subtotal * 0.12;
        $total = $subtotal + $shipping + $tax;
        $shippingInfo = session('checkout.shipping');
        $paymentInfo = session('checkout.payment');

        return view('customer.checkout.review', compact(
            'cartItems', 
            'subtotal', 
            'shipping', 
            'tax', 
            'total',
            'shippingInfo',
            'paymentInfo'
        ));
    }

    public function process(Request $request)
    {
        try {
            DB::beginTransaction();

            // Get cart items and totals
            $cartItems = Cart::content();
            $subtotal = Cart::subtotal();
            $shipping = 150.00;
            $tax = $subtotal * 0.12;
            $total = $subtotal + $shipping + $tax;

            // Create the order
            $order = Order::create([
                'user_id' => Auth::id(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'status' => 'pending',
                'shipping_info' => session('checkout.shipping'),
                'payment_info' => session('checkout.payment')
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->id,
                    'name' => $item->name,
                    'quantity' => $item->qty,
                    'price' => $item->price,
                    'total' => $item->price * $item->qty,
                    'options' => $item->options
                ]);

                // Update product stock
                $product = Product::find($item->id);
                if ($product) {
                    if ($product->stock < $item->qty) {
                        throw new \Exception("Insufficient stock for {$product->name}. Available: {$product->stock}, Requested: {$item->qty}");
                    }
                    $product->decrement('stock', $item->qty);
                }
            }

            // Clear cart and session data
            \Cart::clear();
            session()->forget(['shipping_info', 'payment_info']);

            DB::commit();

            return redirect()->route('customer.checkout.success', ['order' => $order->id])
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order processing failed: ' . $e->getMessage());
            return back()->with('error', 'Error processing order: ' . $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('customer.checkout.success', compact('order'));
    }
}
