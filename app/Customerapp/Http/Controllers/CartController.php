<?php

namespace App\Http\Controllers;

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
            $item->product = $product;
            return $item;
        });

        return view('cartview', [
            'cartItems' => $cartItems,
            'total' => Cart::total()
        ]);
    }

    public function getCartModal()
    {
        return view('cart', [
            'cartItems' => Cart::content(),
            'total' => Cart::total()
        ]);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,ProductID'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        Cart::add(
            $product->ProductID,
            $product->ProductName,
            1,
            $product->Price,
            ['image' => $product->Image]
        );

        return redirect()->back()->with('success', 'Product added to cart');
    }

    public function update(Request $request, $rowId)
    {
        $quantity = (int)$request->input('qty');

        $cartItem = Cart::content()->where('rowId', $rowId)->first();

        if (!$cartItem) {
            return back()->with('error', 'Cart item not found.');
        }

        if ($quantity < 1) {
            return back()->with('error', 'Quantity must be at least 1.');
        }

        Cart::update($rowId, $quantity);

        return redirect()->route('cart.view')->with('success', 'Cart updated successfully!');
    }

    public function remove($rowId)
    {
        Cart::remove($rowId);
        return back()->with('success', 'Product removed from cart');
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,ProductID'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        Cart::add(
            $product->ProductID,
            $product->ProductName,
            1,
            $product->Price,
            ['image' => $product->Image]
        );

        return back()->with('success', 'Product added to cart');
    }
}
