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
        $validatedData = $request->validate([
            'ProductName' => 'required|string|max:255',
            'SKU' => 'nullable|string|max:255|unique:products,sku',
            'Category' => 'required|string|max:255',
            'Brand' => 'required|string|max:255',
            'Quantity' => 'required|integer|min:0',
            'Price' => 'required|numeric|min:0',
            'Description' => 'required|string',
            'Image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Featured' => 'nullable|boolean',
        ]);

        $product = new Product();
        $product->name = $validatedData['ProductName'];
        $product->sku = $validatedData['SKU'];
        $product->category = $validatedData['Category'];
        $product->brand = $validatedData['Brand'];
        $product->stock = $validatedData['Quantity'];
        $product->price = $validatedData['Price'];
        $product->description = $validatedData['Description'];
        $product->featured = $request->has('Featured') ? 1 : 0;

        if ($request->hasFile('Image')) {
            $image = $request->file('Image');
            $imageName = $image->getClientOriginalName();

            // Check if the image already exists
            if (file_exists(public_path('storage/products/' . $imageName))) {
                return redirect()->back()->with('error', 'An image with that name already exists.');
            }

            $path = $image->storeAs('products', $imageName, 'public');
            $product->image_url = $path;
        }

        $product->save();
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
        
        $validatedData = $request->validate([
            'ProductName' => 'required|string|max:100',
            'SKU' => 'required|string|max:50|unique:products,sku,'.$id,
            'Category' => 'required|string|max:100',
            'Brand' => 'required|string|max:100',
            'Price' => 'required|numeric|min:0',
            'Quantity' => 'required|integer|min:0',
            'Description' => 'nullable|string',
            'Image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'Featured' => 'nullable|boolean'
        ]);

        // Map the form field names to database column names
        $updateData = [
            'name' => $validatedData['ProductName'],
            'sku' => $validatedData['SKU'],
            'category' => $validatedData['Category'],
            'brand' => $validatedData['Brand'],
            'price' => $validatedData['Price'],
            'stock' => $validatedData['Quantity'],
            'description' => $validatedData['Description'],
            'featured' => $request->has('Featured') ? 1 : 0
        ];

        // Handle image upload if a new image is provided
        if ($request->hasFile('Image')) {
            // Store image in public/images/products directory
            $path = $request->file('Image')->store('products', 'public_images');
            $updateData['image_url'] = 'images/' . $path;
        }

        $product->update($updateData);

        return redirect()->route('inventory')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Check if the product exists in any cart
        $cartItemExists = DB::table('cart_items')->where('product_id', $product->id)->exists();

        if ($cartItemExists) {
            return redirect()->route('inventory')->with('error', 'This product cannot be deleted because it is currently in a cart.');
        }

        // Check if the product exists in any sales transaction
        $salesTransactionItemExists = DB::table('sales_transaction_items')->where('product_id', $product->id)->exists();

        if ($salesTransactionItemExists) {
            return redirect()->route('inventory')->with('error', 'This product cannot be deleted because it is part of a sales transaction.');
        }

        // Delete related records in purchase order items table
        DB::table('purchase_order_items')->where('product_id', $product->id)->delete();

        // Delete related records in cart items table
        DB::table('cart_items')->where('product_id', $product->id)->delete();

        // Delete the product
        $product->delete();

        return redirect()->route('inventory')->with('success', 'Product deleted successfully.');
    }
}
