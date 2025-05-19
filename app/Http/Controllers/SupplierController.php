<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::with('categories')
            ->withCount('products');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('categories')) {
            $categoryIds = $request->categories;
            $query->whereHas('categories', function($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }

        // Product filter - show suppliers that supply this product's category
        if ($request->filled('product_id')) {
            $product = Product::with('category')->find($request->product_id);
            if ($product && $product->category) {
                $query->whereHas('categories', function($q) use ($product) {
                    $q->where('categories.id', $product->category_id);
                });
            }
        }

        $perPage = $request->get('per_page', 12);
        $suppliers = $query->latest()->paginate($perPage);
        
        // Get categories for the add/edit forms
        $categories = Category::orderBy('name')->get();
        
        // Get the product if product_id is provided
        $selectedProduct = null;
        if ($request->filled('product_id')) {
            $selectedProduct = Product::find($request->product_id);
        }
        
        // Calculate current month and previous month dates
        $now = Carbon::now();
        $monthAgo = Carbon::now()->subMonth();

        // Calculate total suppliers change
        $currentTotal = Supplier::count();
        $previousTotal = Supplier::whereMonth('created_at', $monthAgo->month)
            ->whereYear('created_at', $monthAgo->year)
            ->count();
        $totalChange = $previousTotal > 0 ? (($currentTotal - $previousTotal) / $previousTotal) * 100 : 0;

        // Calculate active suppliers change
        $currentActive = Supplier::where('status', 'active')->count();
        $previousActive = Supplier::where('status', 'active')
            ->whereMonth('created_at', $monthAgo->month)
            ->whereYear('created_at', $monthAgo->year)
            ->count();
        $activeChange = $previousActive > 0 ? (($currentActive - $previousActive) / $previousActive) * 100 : 0;

        // Calculate average response time change
        $currentResponseTime = Supplier::avg('response_time') ?? 0;
        $previousResponseTime = Supplier::whereMonth('created_at', $monthAgo->month)
            ->whereYear('created_at', $monthAgo->year)
            ->avg('response_time') ?? 0;
        $responseTimeChange = $previousResponseTime > 0 ? $currentResponseTime - $previousResponseTime : 0;

        // Calculate on-time delivery rate change
        $currentDeliveryRate = Supplier::avg('on_time_delivery_rate') ?? 0;
        $previousDeliveryRate = Supplier::whereMonth('created_at', $monthAgo->month)
            ->whereYear('created_at', $monthAgo->year)
            ->avg('on_time_delivery_rate') ?? 0;
        $deliveryRateChange = $previousDeliveryRate > 0 ? $currentDeliveryRate - $previousDeliveryRate : 0;

        $stats = [
            'total_suppliers' => [
                'value' => $currentTotal,
                'change' => round($totalChange),
                'changeType' => $totalChange >= 0 ? 'increase' : 'decrease'
            ],
            'active_suppliers' => [
                'value' => $currentActive,
                'change' => round($activeChange),
                'changeType' => $activeChange >= 0 ? 'increase' : 'decrease'
            ],
            'avg_response_time' => [
                'value' => number_format($currentResponseTime, 1),
                'change' => number_format($responseTimeChange, 1),
                'changeType' => $responseTimeChange <= 0 ? 'increase' : 'decrease'
            ],
            'on_time_delivery' => [
                'value' => number_format($currentDeliveryRate, 0) . '%',
                'change' => round($deliveryRateChange),
                'changeType' => $deliveryRateChange >= 0 ? 'increase' : 'decrease'
            ]
        ];

        return view('admin.suppliers', compact('suppliers', 'stats', 'categories', 'selectedProduct'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id'
        ]);

        $supplier = Supplier::create([
            'name' => $validated['name'],
            'contact_person' => $validated['contact_person'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'status' => $validated['status'],
            'rating' => 0,
            'on_time_delivery_rate' => 0
        ]);

        $supplier->categories()->attach($validated['categories']);

        return redirect()->route('admin.suppliers')->with('success', 'Supplier added successfully');
    }

    public function edit(Supplier $supplier)
    {
        try {
            $supplierData = $supplier->load('categories');
            return response()->json($supplierData);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id'
        ]);

        $supplier->update([
            'name' => $validated['name'],
            'contact_person' => $validated['contact_person'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'status' => $validated['status']
        ]);

        $supplier->categories()->sync($validated['categories']);

        return redirect()->route('admin.suppliers')->with('success', 'Supplier updated successfully');
    }

    public function destroy(Supplier $supplier)
    {
        try {
            // Delete the supplier's category relationships
            $supplier->categories()->detach();
            
            // Delete the supplier
            $supplier->delete();

            return redirect()->route('admin.suppliers')->with('success', 'Supplier deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting supplier: ' . $e->getMessage());
        }
    }
} 