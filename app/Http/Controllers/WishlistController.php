<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function toggle(Request $request)
    {
        $productId = $request->input('product_id');
        $userId = Auth::id();

        $wishlist = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $isInWishlist = false;
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            $isInWishlist = true;
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'in_wishlist' => $isInWishlist
            ]);
        }

        return back()->with('success', $isInWishlist ? 'Added to wishlist' : 'Removed from wishlist');
    }

    public function index()
    {
        $wishlistItems = Wishlist::where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->get();

        return view('Customer.views.wishlist.index', compact('wishlistItems'));
    }
} 