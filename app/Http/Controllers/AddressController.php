<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = auth()->user()->addresses()->orderBy('is_default', 'desc')->get();
        return view('Customer.views.account.addresses', compact('addresses'));
    }

    /**
     * Store a new address.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'boolean',
        ]);

        if ($request->is_default) {
            Auth::user()->addresses()->update(['is_default' => false]);
        }

        $address = Auth::user()->addresses()->create($validated);

        return redirect()->route('account.addresses')
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
            'phone' => 'required|string|max:20',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'boolean',
        ]);

        if ($request->is_default) {
            Auth::user()->addresses()->update(['is_default' => false]);
        }

        $address->update($validated);

        return redirect()->route('account.addresses')
            ->with('success', 'Address updated successfully.');
    }

    /**
     * Delete an address.
     */
    public function destroy(Address $address)
    {
        $this->authorize('delete', $address);
        
        $address->delete();

        return redirect()->route('account.addresses')
            ->with('success', 'Address deleted successfully.');
    }

    /**
     * Make an address the default.
     */
    public function makeDefault(Address $address)
    {
        $this->authorize('update', $address);

        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return redirect()->route('account.addresses')
            ->with('success', 'Default address updated successfully.');
    }
} 