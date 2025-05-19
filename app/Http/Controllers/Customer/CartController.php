<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cartitem;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Customerapp\Facades\Cart;

class CartController extends Controller
{
    public function viewCart()
    {
        $cartItems = Cart::content()->map(function($item) {
            $product = Product::find($item->id);
            if ($product) {
                $item->image_url = $product->getImageUrl();
                $item->product = $product;
            } else {
                $item->image_url = asset('storage/images/products/default.jpg');
                $item->product = null;
            }
            return $item;
        });

        $categories = \App\Models\Category::orderBy('name')->get();

        return view('Customer.cart.view', [
            'cartItems' => $cartItems,
            'total' => Cart::total(),
            'categories' => $categories
        ]);
    }

    public function getCartModal()
    {
        $cartItems = Cart::content()->map(function($item) {
            $product = Product::find($item->id);
            if ($product) {
                $item->image_url = $product->getImageUrl();
            } else {
                $item->image_url = asset('storage/images/products/default.jpg');
            }
            return $item;
        });

        return view('Customer.cart.modal', [
            'cartItems' => $cartItems,
            'total' => Cart::total()
        ]);
    }

    public function add(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $product = Product::findOrFail($request->product_id);
            $quantity = $request->quantity;
            
            // Check if product is in stock
            if (!$product->isInStock()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, this product is out of stock.'
                ], 422);
            }

            // Check if requested quantity is available
            if (!$product->hasStockFor($quantity)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, we don\'t have enough stock for the requested quantity.'
                ], 422);
            }

            // Check if adding to existing cart item
            $cartItem = Cart::content()->where('id', $product->id)->first();
            if ($cartItem) {
                $newQuantity = $cartItem->qty + $quantity;
                if (!$product->hasStockFor($newQuantity)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sorry, we don\'t have enough stock for the total quantity requested.'
                    ], 422);
                }
            }

            try {
                // Add to cart
                Cart::add(
                    $product->id,
                    $product->name,
                    $quantity,
                    $product->price,
                    [
                        'image' => $product->image_url,
                        'stock' => $product->stock
                    ]
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Product added to cart successfully.',
                    'cartCount' => Cart::count()
                ]);
            } catch (\Exception $e) {
                \Log::error('Cart add error: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add product to cart. Please try again.'
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error('Cart add error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to cart. Please try again.'
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'rowId' => 'required|string',
                'quantity_change' => 'required|integer'
            ]);

            $cartItem = Cart::content()->where('rowId', $request->rowId)->first();
            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found.'
                ], 404);
            }

            $product = Product::find($cartItem->id);
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found.'
                ], 404);
            }

            $newQuantity = $cartItem->qty + $request->quantity_change;

            // Ensure quantity doesn't go below 1
            if ($newQuantity < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quantity cannot be less than 1.'
                ], 422);
            }

            // Check if requested quantity is available
            if (!$product->hasStockFor($newQuantity)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, we don\'t have enough stock for the requested quantity.'
                ], 422);
            }

            Cart::update($request->rowId, $newQuantity);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully!',
                'cartCount' => Cart::count(),
                'cartTotal' => Cart::total()
            ]);
        } catch (\Exception $e) {
            \Log::error('Cart update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart. Please try again.'
            ], 500);
        }
    }

    public function remove($rowId)
    {
        Cart::remove($rowId);
        return back()->with('success', 'Product removed from cart');
    }

    public function getCartItems()
    {
        $cartItems = Cart::content()->map(function($item) {
            $product = Product::find($item->id);
            if (!$product) {
                return null;
            }
            
            return [
                'rowId' => $item->rowId,
                'id' => $item->id,
                'name' => $item->name,
                'qty' => $item->qty,
                'price' => $item->price,
                'image_url' => $product->getImageUrl(),
                'stock' => $product->stock
            ];
        })->filter();

        return response()->json([
            'items' => $cartItems,
            'total' => Cart::total(),
            'count' => Cart::count()
        ]);
    }
} 