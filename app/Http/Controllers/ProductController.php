<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Get the authenticated user
        $userInitials = strtoupper(substr($user->name, 0, 1)); // Get the first letter of the user's name
        $username = $user->username;

        $products = Product::paginate(10); // Example: Fetch products if needed for this view
        return view('products.index', compact('products', 'userInitials', 'username')); // Ensure this view exists and adjust data as needed
    }

    // Method to handle storing a new product
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'ProductName' => 'required|string|max:255',
            'SKU' => 'nullable|string|max:255|unique:products,SKU', // Updated table name
            'Category' => 'required|string|max:255',
            'Brand' => 'required|string|max:255', // Made Brand required as per form
            'stock' => 'required|integer|min:0', // Changed from Quantity
            'Price' => 'required|numeric|min:0',
            'Description' => 'required|string', // Made Description required as per form
            'Image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Featured' => 'nullable|boolean', // Added Featured field
        ]);

        // Handle image upload if provided
        if ($request->hasFile('Image')) {
            // Store in storage/app/public/products
            $path = $request->file('Image')->store('products', 'public');
            // The path saved in DB will be 'products/filename.ext'
            // Access it via asset('storage/' . $path) in views
            $validatedData['Image'] = $path;
        } else {
            // Ensure 'Image' is null if not provided, to avoid issues if the column doesn't have a default
            $validatedData['Image'] = null;
        }

        // Set 'Featured' to 0 if not present in the request (checkbox not ticked)
        $validatedData['Featured'] = $request->has('Featured') ? 1 : 0;

        // Create the product using the validated data
        Product::create($validatedData);

        // Redirect back to the inventory page with a success message
        return redirect()->route('inventory')->with('success', 'Product added successfully.');
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
            'SKU' => 'required|string|max:255|unique:products,SKU,' . $id . ',ProductID', // Updated table name
            'Category' => 'required|string|max:255',
            'Brand' => 'required|string|max:255', // Added Brand validation
            'stock' => 'required|integer|min:0', // Changed from Quantity
            'Price' => 'required|numeric|min:0',
            'Description' => 'nullable|string',
            'Image' => 'nullable|image|max:2048',
            'Featured' => 'nullable|boolean', // Added Featured validation
        ]);

        // Prepare data for update
        $updateData = $request->only(['ProductName', 'SKU', 'Category', 'Brand', 'stock', 'Price', 'Description']);
        $updateData['Featured'] = $request->has('Featured') ? 1 : 0; // Handle Featured checkbox

        // Update product details
        $product->update($updateData);

        // Handle image upload if provided
        if ($request->hasFile('Image')) {
            $path = $request->file('Image')->store('products', 'public');
            $product->Image = '/storage/' . $path;
            // Update the image path in the database
            // The path saved in DB will be 'products/filename.ext'
            // Access it via asset('storage/' . $path) in views
            $product->Image = $path;
            $product->save();
        }

        // Redirect back to the inventory page with a success message
        return redirect()->route('inventory')->with('success', 'Product updated successfully.');
        // return response()->json(['message' => 'Product updated successfully!']); // Changed from JSON response
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Check if the product exists in any cart
        $cartItemExists = DB::table('cartitem')->where('ProductID', $product->ProductID)->exists();

        if ($cartItemExists) {
            return redirect()->route('inventory')->with('error', 'This product cannot be deleted because it is currently in a cart.');
        }

        // Check if the product exists in any sales transaction
        $salesTransactionItemExists = DB::table('salestransactionitem')->where('ProductID', $product->ProductID)->exists();

        if ($salesTransactionItemExists) {
            return redirect()->route('inventory')->with('error', 'This product cannot be deleted because it is part of a sales transaction.');
        }

        // Delete related records in purchaseorderitem table
        DB::table('purchaseorderitem')->where('ProductID', $product->ProductID)->delete();

        // Delete related records in cartitem table
        DB::table('cartitem')->where('ProductID', $product->ProductID)->delete();

        // Delete the product
        $product->delete();

        // Redirect back to the inventory page with a success message
        return redirect()->route('inventory')->with('success', 'Product deleted successfully.');
    }
}
