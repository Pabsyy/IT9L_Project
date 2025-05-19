<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductRatingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000'
        ]);

        $rating = ProductReview::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $validated['product_id']
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['review'],
                'is_verified_purchase' => true,
                'is_approved' => true
            ]
        );

        $product = Product::find($validated['product_id']);
        
        // Update product rating statistics
        $reviews = $product->reviews;
        $avgRating = $reviews->avg('rating');
        $ratingCount = $reviews->count();
        
        $product->update([
            'average_rating' => round($avgRating, 2),
            'rating_count' => $ratingCount
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'rating' => $rating,
                'average_rating' => $product->average_rating,
                'review_count' => $product->rating_count
            ]);
        }

        return back()->with('success', 'Rating submitted successfully');
    }

    public function index(Product $product)
    {
        $ratings = $product->reviews()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('customer.products.ratings', compact('product', 'ratings'));
    }
} 