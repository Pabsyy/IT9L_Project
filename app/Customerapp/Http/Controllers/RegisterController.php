<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // Show the registration form
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Handle the registration form submission
    public function register(Request $request)
    {
        // Validate the request data (adjust rules as needed)
        $validatedData = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName'  => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:6|confirmed', // ensure your form includes a password_confirmation field
        ]);

        // Create the new user
        $user = User::create([
            'first_name' => $validatedData['firstName'],
            'last_name'  => $validatedData['lastName'],
            'email'      => $validatedData['email'],
            'password'   => Hash::make($validatedData['password']),
        ]);

        // Log in the user (optional)
        Auth::login($user);

        // Redirect to the welcome page (assuming you have a welcome route)
        return redirect()->route('welcome');
    }
}
