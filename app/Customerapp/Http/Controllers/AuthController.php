<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->authenticated($request, Auth::user());
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'loginError' => 'Invalid email or password.', 
                'keepModalOpen' => true
            ]);
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'first_name' => $validatedData['firstName'],
            'last_name' => $validatedData['lastName'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => 'customer'
        ]);

        return redirect('/')
            ->with('success', 'Account created successfully! Please login.')
            ->with('openLogin', true); // Add this line to explicitly trigger login modal
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($request->has('intended')) {
            return redirect($request->input('intended'));
        }
        return redirect()->intended('/');
    }
}
