<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class SuppliersController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Get the authenticated user
        $userInitials = strtoupper(substr($user->name, 0, 1)); // Get the first letter of the user's name
        $username = $user->username;

        $suppliers = Supplier::all();
        return view('suppliers', compact('suppliers','userInitials', 'username'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'SupplierName' => 'required|string|max:255',
            'ContactNumber' => 'required|string|max:15',
            'Email' => 'required|email|max:255',
        ]);

        Supplier::create($request->all());

        return redirect()->route('suppliers')->with('success', 'Supplier added successfully.');
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
