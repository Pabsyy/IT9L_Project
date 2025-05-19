<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function stockIn()
    {
        $products = Product::orderBy('name')->get();
        $recentMovements = InventoryMovement::where('type', 'purchase')
            ->with(['product', 'user'])
            ->orderBy('moved_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.stock.stock-in', compact('products', 'recentMovements'));
    }

    public function stockOut()
    {
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();
        $recentMovements = InventoryMovement::whereIn('type', ['sale', 'damage', 'return', 'adjustment'])
            ->where('quantity', '<', 0) // Ensure we only get stock out movements
            ->with(['product', 'user'])
            ->orderBy('moved_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.stock.stock-out', compact('products', 'recentMovements'));
    }

    public function processStockIn(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99999',
            'notes' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:50',
            'batch_number' => 'nullable|string|max:50',
            'unit_cost' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);
            $newQuantity = $product->stock + $request->quantity;
            
            // Check if new quantity exceeds system limits
            if ($newQuantity > 999999) {
                throw new \Exception('Stock quantity cannot exceed system limit (999,999 units)');
            }

            // Create inventory movement record
            $movement = InventoryMovement::create([
                'product_id' => $request->product_id,
                'type' => 'purchase',
                'quantity' => $request->quantity,
                'notes' => $request->notes,
                'reference_number' => $request->reference_number,
                'batch_number' => $request->batch_number,
                'unit_cost' => $request->unit_cost,
                'total_cost' => $request->unit_cost * $request->quantity,
                'moved_at' => Carbon::now(),
                'user_id' => Auth::id()
            ]);

            // Update product stock and average cost
            $product->update([
                'stock' => $newQuantity,
                'last_stocked_at' => Carbon::now(),
                'average_cost' => $this->calculateNewAverageCost($product, $request->quantity, $request->unit_cost)
            ]);

            // Log the successful transaction
            Log::info('Stock In - Success', [
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'user_id' => Auth::id(),
                'movement_id' => $movement->id
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Stock added successfully. New stock level: ' . $newQuantity);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Stock In - Error', [
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Error processing stock in: ' . $e->getMessage());
        }
    }

    public function processStockOut(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99999',
            'notes' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:50',
            'reason' => 'required|string|in:sale,damage,return,adjustment'
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);
            
            // Check if enough stock is available
            if ($product->stock < $request->quantity) {
                throw new \Exception('Not enough stock available. Current stock: ' . $product->stock);
            }

            // Calculate new stock level
            $newQuantity = $product->stock - $request->quantity;

            // Create inventory movement record
            $movement = InventoryMovement::create([
                'product_id' => $request->product_id,
                'type' => $request->reason,
                'quantity' => -$request->quantity, // Negative for stock out
                'notes' => $request->notes,
                'reference_number' => $request->reference_number,
                'unit_cost' => $product->average_cost,
                'total_cost' => $product->average_cost * $request->quantity,
                'moved_at' => Carbon::now(),
                'user_id' => Auth::id()
            ]);

            // Update product stock
            $product->update([
                'stock' => $newQuantity,
                'last_movement_at' => Carbon::now()
            ]);

            // Log the successful transaction
            Log::info('Stock Out - Success', [
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'reason' => $request->reason,
                'user_id' => Auth::id(),
                'movement_id' => $movement->id
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Stock removed successfully. New stock level: ' . $newQuantity);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Stock Out - Error', [
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Error processing stock out: ' . $e->getMessage());
        }
    }

    private function calculateNewAverageCost($product, $newQuantity, $newUnitCost)
    {
        $currentTotalValue = $product->stock * ($product->average_cost ?? 0);
        $newTotalValue = $newQuantity * $newUnitCost;
        $totalQuantity = $product->stock + $newQuantity;

        return $totalQuantity > 0 ? ($currentTotalValue + $newTotalValue) / $totalQuantity : $newUnitCost;
    }
} 