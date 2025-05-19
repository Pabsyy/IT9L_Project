<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Store a new address.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'type' => 'required|string|in:home,office,other',
            'is_default' => 'boolean',
            'additional_info' => 'nullable|string'
        ]);

        // If this is the first address or is_default is true, unset all other default addresses
        if (($request->user()->addresses()->count() === 0) || ($validated['is_default'] ?? false)) {
            $request->user()->addresses()->update(['is_default' => false]);
            $validated['is_default'] = true;
        }

        $address = $request->user()->addresses()->create($validated);

        return redirect()->back()
            ->with('success', 'Address added successfully.');
    }

    /**
     * Update an address.
     */
    public function update(Request $request, Address $address)
    {
        $this->authorize('update', $address);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'type' => 'required|string|in:home,office,other',
            'is_default' => 'boolean',
            'additional_info' => 'nullable|string'
        ]);

        if ($validated['is_default'] ?? false) {
            $request->user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($validated);

        return redirect()->back()
            ->with('success', 'Address updated successfully.');
    }

    /**
     * Delete an address.
     */
    public function destroy(Address $address)
    {
        $this->authorize('delete', $address);

        // If this was the default address and there are other addresses,
        // make another address the default
        if ($address->is_default) {
            $newDefault = Address::where('user_id', $address->user_id)
                ->where('id', '!=', $address->id)
                ->first();
            
            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        $address->delete();

        return redirect()->back()
            ->with('success', 'Address deleted successfully.');
    }

    /**
     * Make an address the default.
     */
    public function makeDefault(Address $address)
    {
        $this->authorize('update', $address);

        // Remove default from all other addresses
        Address::where('user_id', $address->user_id)
            ->where('id', '!=', $address->id)
            ->update(['is_default' => false]);

        $address->update(['is_default' => true]);

        return redirect()->back()
            ->with('success', 'Default address updated successfully.');
    }
} 