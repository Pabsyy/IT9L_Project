<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index'); // Ensure this view exists
    }

    public function categories()
    {
        return view('products.categories'); // Ensure this view exists
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Validate the request
        $request->validate([
            'ProductName' => 'required|string|max:255',
            'SKU' => 'required|string|max:255',
            'Category' => 'required|string|max:255',
            'Quantity' => 'required|integer|min:0',
            'Price' => 'required|numeric|min:0',
            'Description' => 'nullable|string',
            'Image' => 'nullable|image|max:2048',
        ]);

        // Update product details
        $product->update($request->only(['ProductName', 'SKU', 'Category', 'Quantity', 'Price', 'Description']));

        // Handle image upload if provided
        if ($request->hasFile('Image')) {
            $path = $request->file('Image')->store('products', 'public');
            $product->Image = '/storage/' . $path;
            $product->save();
        }

        return response()->json(['message' => 'Product updated successfully!']);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('inventory')->with('success', 'Product deleted successfully.');
    }
}