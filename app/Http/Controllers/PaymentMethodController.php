<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentMethodController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'card_holder_name' => 'required|string|max:255',
            'card_number' => 'required|string|size:16',
            'expiry_month' => 'required|string|size:2',
            'expiry_year' => 'required|string|size:4',
            'cvv' => 'required|string|size:3',
            'is_default' => 'boolean'
        ]);

        // If this is the first card or is_default is checked, unset other default cards
        if ($request->is_default || Auth::user()->paymentMethods()->count() === 0) {
            Auth::user()->paymentMethods()->update(['is_default' => false]);
        }

        // Determine card type based on first digit
        $cardType = match (substr($request->card_number, 0, 1)) {
            '4' => 'visa',
            '5' => 'mastercard',
            default => 'other',
        };

        Auth::user()->paymentMethods()->create([
            'card_type' => $cardType,
            'last_four' => substr($request->card_number, -4),
            'card_holder_name' => $request->card_holder_name,
            'expiry_month' => $request->expiry_month,
            'expiry_year' => $request->expiry_year,
            'is_default' => $request->is_default || Auth::user()->paymentMethods()->count() === 0,
        ]);

        return redirect()->back()->with('success', 'Payment method added successfully.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->user_id !== Auth::id()) {
            abort(403);
        }

        // If we're deleting the default card and there are other cards,
        // make the most recent one the default
        if ($paymentMethod->is_default) {
            $newDefault = Auth::user()->paymentMethods()
                ->where('id', '!=', $paymentMethod->id)
                ->latest()
                ->first();

            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        $paymentMethod->delete();

        return redirect()->back()->with('success', 'Payment method removed successfully.');
    }
} 