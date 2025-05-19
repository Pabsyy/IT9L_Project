<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index(Request $request)
    {
        $query = Product::query();

        // Apply category filters
        if ($request->filled('categories')) {
            $query->whereIn('category_id', $request->categories);
        }

        // Apply brand filters
        if ($request->filled('brands')) {
            $query->whereIn('brand_id', $request->brands);
        }

        // Apply price range filters
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Apply stock filter
        if ($request->boolean('in_stock')) {
            $query->where('stock', '>', 0);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('brand', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Apply sorting
        switch ($request->get('sort', 'newest')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('sales', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        // Get categories and brands for filters
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        // Get products with pagination
        $products = $query->with(['category', 'brand'])->paginate(12)->withQueryString();

        // If it's an AJAX request, return only the products partial view
        if ($request->ajax()) {
            return view('Customer.products.partials.products-grid', compact('products'));
        }

        // Return the full view for regular requests
        return view('Customer.products.index', compact('products', 'categories', 'brands'));
    }

    public function show($id)
    {
        $product = Product::with(['category', 'brand', 'ratings'])->findOrFail($id);
        return view('Customer.products.show', compact('product'));
    }

    public function byCategory($category)
    {
        // Find the category by slug
        $category = Category::where('slug', $category)->firstOrFail();
        
        // Query products through the category relationship
        $products = Product::where('category_id', $category->id)
            ->with(['category', 'brand'])
            ->paginate(12);

        // Get categories for the filter sidebar
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('Customer.products.index', compact('products', 'categories', 'brands', 'category'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        
        $products = Product::where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->orWhereHas('category', function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(12);

        // Get categories and brands for filters
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('Customer.views.products.product', compact('products', 'categories', 'brands'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => $request->category_id === 'new' ? 'nullable' : 'required|exists:categories,id',
            'brand_id' => $request->brand_id === 'new' ? 'nullable' : 'required|exists:brands,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'new_category' => $request->category_id === 'new' ? 'required|string|max:255' : 'nullable',
            'new_brand' => $request->brand_id === 'new' ? 'required|string|max:255' : 'nullable',
            'sku' => 'nullable|string|max:50|unique:products',
            'featured' => 'boolean',
            'main_image' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'additional_images.*' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120'
        ]);

        try {
            DB::beginTransaction();

            // Handle new category
            $categoryId = $validated['category_id'];
            if ($request->category_id === 'new' && $request->filled('new_category')) {
                $category = Category::create([
                    'name' => $request->new_category,
                    'slug' => Str::slug($request->new_category)
                ]);
                $categoryId = $category->id;
            }

            // Handle new brand
            $brandId = $validated['brand_id'];
            if ($request->brand_id === 'new' && $request->filled('new_brand')) {
                $brand = Brand::create([
                    'name' => $request->new_brand,
                    'slug' => Str::slug($request->new_brand)
                ]);
                $brandId = $brand->id;
            }

            // Store main image
            $mainImagePath = $this->imageService->storeProductImage($request->file('main_image'), 'main');

            // Create product data array
            $productData = [
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'category_id' => $categoryId,
                'brand_id' => $brandId,
                'supplier_id' => $validated['supplier_id'],
                'sku' => $validated['sku'] ?? null,
                'featured' => $validated['featured'] ?? false,
                'main_image' => $mainImagePath,
                'sales' => 0
            ];

            // Store additional images
            if ($request->hasFile('additional_images')) {
                foreach ($request->file('additional_images') as $index => $image) {
                    if ($image && $index < 4) {
                        $fieldName = 'image_' . ($index + 1);
                        $productData[$fieldName] = $this->imageService->storeProductImage($image, "additional_{$index}");
                    }
                }
            }

            // Create the product
            $product = Product::create($productData);

            DB::commit();

            return redirect()->route('admin.inventory')->with('success', 'Product created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product creation error: ' . $e->getMessage());

            // Clean up any uploaded images
            if (isset($mainImagePath)) {
                $this->imageService->deleteProductImage($mainImagePath);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error creating product: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, Product $product)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'sku' => 'nullable|string|max:50|unique:products,sku,' . $product->id,
            'featured' => 'boolean',
            'main_image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'additional_images.*' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120'
        ]);

        try {
            DB::beginTransaction();

            // Update product data array
            $productData = [
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'],
                'supplier_id' => $validated['supplier_id'],
                'sku' => $validated['sku'],
                'featured' => $validated['featured'] ?? false
            ];

            // Handle main image update
            if ($request->hasFile('main_image')) {
                $productData['main_image'] = $this->imageService->updateProductImage(
                    $request->file('main_image'),
                    $product->main_image,
                    'main'
                );
            }

            // Handle additional images
            if ($request->hasFile('additional_images')) {
                foreach ($request->file('additional_images') as $index => $image) {
                    if ($image && $index < 4) {
                        $fieldName = 'image_' . ($index + 1);
                        $productData[$fieldName] = $this->imageService->updateProductImage(
                            $image,
                            $product->$fieldName,
                            "additional_{$index}"
                        );
                    }
                }
            }

            // Update the product
            $product->update($productData);

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully',
                    'product' => $product->fresh()->load(['category', 'brand', 'supplier'])
                ]);
            }

            return redirect()->route('admin.inventory')->with('success', 'Product updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating product: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating product: ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error updating product: ' . $e->getMessage()]);
        }
    }

    public function destroy(Product $product)
    {
        try {
            // Delete the product image if it exists
            if ($product->image_url) {
                Storage::disk('public_images')->delete($product->image_url);
            }

            // Delete the product
            $product->delete();

            return redirect()->route('admin.inventory')->with('success', 'Product deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }
}
