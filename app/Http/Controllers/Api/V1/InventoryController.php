<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        return Inventory::paginate(10);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);
        return Inventory::create($validated);
    }

    public function show(Inventory $inventory)
    {
        return $inventory;
    }

    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);
        $inventory->update($validated);
        return $inventory;
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return response()->noContent();
    }
} 