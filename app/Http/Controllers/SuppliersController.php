<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuppliersController extends Controller
{
    public function index()
    {
        // Get suppliers with their relationships
        $suppliers = Supplier::with('categories')
            ->withCount('products')
            ->paginate(20);

        // Calculate statistics
        $stats = [
            'total_suppliers' => [
                'value' => number_format(Supplier::count()),
                'change' => $this->calculateChange('suppliers', 'created_at'),
                'changeType' => $this->calculateChange('suppliers', 'created_at') >= 0 ? 'increase' : 'decrease'
            ],
            'active_suppliers' => [
                'value' => number_format(Supplier::where('status', 'active')->count()),
                'change' => $this->calculateStatusChange(),
                'changeType' => $this->calculateStatusChange() >= 0 ? 'increase' : 'decrease'
            ],
            'avg_response_time' => [
                'value' => number_format(Supplier::avg('response_time') ?? 0, 1) . ' days',
                'change' => $this->calculateResponseTimeChange(),
                'changeType' => $this->calculateResponseTimeChange() <= 0 ? 'increase' : 'decrease'
            ],
            'on_time_delivery' => [
                'value' => number_format(Supplier::avg('on_time_delivery_rate') ?? 0, 0) . '%',
                'change' => $this->calculateDeliveryRateChange(),
                'changeType' => $this->calculateDeliveryRateChange() >= 0 ? 'increase' : 'decrease'
            ]
        ];

        return view('suppliers', compact('suppliers', 'stats'));
    }

    private function calculateChange($table, $dateColumn)
    {
        $now = Carbon::now();
        $monthAgo = Carbon::now()->subMonth();

        $current = DB::table($table)
            ->whereMonth($dateColumn, $now->month)
            ->whereYear($dateColumn, $now->year)
            ->count();

        $previous = DB::table($table)
            ->whereMonth($dateColumn, $monthAgo->month)
            ->whereYear($dateColumn, $monthAgo->year)
            ->count();

        if ($previous == 0) return 0;
        
        return number_format((($current - $previous) / $previous) * 100, 0);
    }

    private function calculateStatusChange()
    {
        $now = Carbon::now();
        $monthAgo = Carbon::now()->subMonth();

        $current = Supplier::where('status', 'active')
            ->whereMonth('updated_at', $now->month)
            ->whereYear('updated_at', $now->year)
            ->count();

        $previous = Supplier::where('status', 'active')
            ->whereMonth('updated_at', $monthAgo->month)
            ->whereYear('updated_at', $monthAgo->year)
            ->count();

        if ($previous == 0) return 0;
        
        return number_format((($current - $previous) / $previous) * 100, 0);
    }

    private function calculateResponseTimeChange()
    {
        $now = Carbon::now();
        $monthAgo = Carbon::now()->subMonth();

        $current = Supplier::whereMonth('updated_at', $now->month)
            ->whereYear('updated_at', $now->year)
            ->avg('response_time') ?? 0;

        $previous = Supplier::whereMonth('updated_at', $monthAgo->month)
            ->whereYear('updated_at', $monthAgo->year)
            ->avg('response_time') ?? 0;

        if ($previous == 0) return 0;
        
        return number_format($current - $previous, 1);
    }

    private function calculateDeliveryRateChange()
    {
        $now = Carbon::now();
        $monthAgo = Carbon::now()->subMonth();

        $current = Supplier::whereMonth('updated_at', $now->month)
            ->whereYear('updated_at', $now->year)
            ->avg('on_time_delivery_rate') ?? 0;

        $previous = Supplier::whereMonth('updated_at', $monthAgo->month)
            ->whereYear('updated_at', $monthAgo->year)
            ->avg('on_time_delivery_rate') ?? 0;

        if ($previous == 0) return 0;
        
        return number_format($current - $previous, 0);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'status' => 'required|in:active,inactive',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id'
        ]);

        $supplier = Supplier::create($validated);
        $supplier->categories()->attach($request->categories);

        return redirect()->route('suppliers')->with('success', 'Supplier added successfully');
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'status' => 'required|in:active,inactive',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id'
        ]);

        $supplier->update($validated);
        $supplier->categories()->sync($request->categories);

        return redirect()->route('suppliers')->with('success', 'Supplier updated successfully');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers')->with('success', 'Supplier deleted successfully');
    }

    public function contact(Request $request)
    {
        $request->validate([
            'Email' => 'required|email',
            'ContactNumber' => 'required|string',
            'Message' => 'required|string',
        ]);

        // Send email
        Mail::raw($request->Message, function ($message) use ($request) {
            $message->to($request->Email)
                ->subject('Message from Supplier Management');
        });

        // Optionally, send SMS (requires an SMS gateway integration)

        return redirect()->route('suppliers')->with('success', 'Message sent successfully.');
    }
}
