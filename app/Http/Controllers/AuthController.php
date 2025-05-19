<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Laravel\Socialite\Facades\Socialite;

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
            
            // If user is an admin trying to use customer login, redirect to admin login
            if (Auth::user()->is_admin) {
                Auth::logout();
                return redirect()->route('admin.login')
                    ->withErrors(['email' => 'Please use the admin login page.']);
            }
            
            return $this->authenticated($request, Auth::user());
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'loginError' => 'Invalid email or password.'
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
            ->with('openLogin', true);
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
        
        // If user is coming from checkout, redirect back to checkout
        if (url()->previous() === route('customer.checkout.shipping')) {
            return redirect()->route('customer.checkout.shipping');
        }
        
        // Default redirect for customers
        return redirect('/');
    }

    public function showAdminLogin()
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return view('authAdmin.login');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        try {
            // Find the user first
            $user = User::where('email', $credentials['email'])->first();

            if ($user) {
                // Check if the password hash starts with $2y$ (Bcrypt)
                if (!str_starts_with($user->password, '$2y$')) {
                    return back()
                        ->withInput($request->only('email'))
                        ->withErrors(['bcrypt' => 'This password does not use the Bcrypt algorithm.'])
                        ->with('warning', 'Your password needs to be updated to use the latest security standards.');
                }

                if (Auth::attempt($credentials)) {
                    $request->session()->regenerate();
                    
                    // Check if user is an admin
                    if (Auth::user()->is_admin) {
                        return redirect()->intended(route('admin.index'));
                    }
                    
                    // If not an admin, logout and redirect back with error
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'You do not have admin privileges.',
                    ]);
                }
            }

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        } catch (\Exception $e) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['error' => 'An error occurred during login. Please try again.']);
        }
    }

    public function adminLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function updateAdminProfile(Request $request)
    {
        $user = Auth::user();
        
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->first_name = $validatedData['first_name'];
        $user->last_name = $validatedData['last_name'];
        $user->email = $validatedData['email'];
        
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function userIndex()
    {
        $users = User::latest()->paginate(10);
        return view('authAdmin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('authAdmin.users.create');
    }

    public function storeUser(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,customer',
        ]);

        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'],
            'is_admin' => $validatedData['role'] === 'admin',
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        return view('authAdmin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,customer',
        ]);

        $user->first_name = $validatedData['first_name'];
        $user->last_name = $validatedData['last_name'];
        $user->email = $validatedData['email'];
        $user->role = $validatedData['role'];
        $user->is_admin = $validatedData['role'] === 'admin';
        
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    public function showAdminRegister()
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return view('authAdmin.register');
    }

    public function adminRegister(Request $request)
    {
        $messages = [
            'email.unique' => 'This email address is already registered. Please use a different email or login to your existing account.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
            'first_name.required' => 'Please enter your first name.',
            'last_name.required' => 'Please enter your last name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
        ];

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required',
        ], $messages);

        try {
            $user = User::create([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'admin',
                'is_admin' => true
            ]);

            Auth::login($user);

            return redirect()->route('admin.dashboard')
                ->with('success', 'Admin account created successfully!');
        } catch (\Exception $e) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['error' => 'An error occurred while creating your account. Please try again.']);
        }
    }

    public function showAdminForgotPassword()
    {
        return view('authAdmin.forgot-password');
    }

    public function showAdminResetPassword(Request $request, $token)
    {
        return view('authAdmin.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function adminForgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function adminResetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('admin.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function showForgotPassword()
    {
        return view('customer.auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPassword(Request $request, $token)
    {
        return view('customer.auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('customer.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('google_id', $googleUser->id)
                       ->orWhere('email', $googleUser->email)
                       ->first();

            if (!$user) {
                // Split the name into first and last name
                $nameParts = explode(' ', $googleUser->name);
                $firstName = $nameParts[0];
                $lastName = count($nameParts) > 1 ? end($nameParts) : '';

                $user = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'provider' => 'google',
                    'password' => Hash::make(Str::random(24)),
                    'role' => 'customer'
                ]);
            } elseif (empty($user->google_id)) {
                // If user exists but google_id is empty, update it
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'provider' => 'google'
                ]);
            }

            Auth::login($user);
            return $this->authenticated(request(), $user);
            
        } catch (\Exception $e) {
            return redirect()->route('login')
                           ->withErrors(['error' => 'Google login failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Redirect the user to the Facebook authentication page.
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handle the callback from Facebook.
     */
    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            
            $user = User::where('facebook_id', $facebookUser->id)
                       ->orWhere('email', $facebookUser->email)
                       ->first();

            if (!$user) {
                // Split the name into first and last name
                $nameParts = explode(' ', $facebookUser->name);
                $firstName = $nameParts[0];
                $lastName = count($nameParts) > 1 ? end($nameParts) : '';

                $user = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $facebookUser->email,
                    'facebook_id' => $facebookUser->id,
                    'avatar' => $facebookUser->avatar,
                    'provider' => 'facebook',
                    'password' => Hash::make(Str::random(24)),
                    'role' => 'customer'
                ]);
            } elseif (empty($user->facebook_id)) {
                // If user exists but facebook_id is empty, update it
                $user->update([
                    'facebook_id' => $facebookUser->id,
                    'avatar' => $facebookUser->avatar,
                    'provider' => 'facebook'
                ]);
            }

            Auth::login($user);
            return $this->authenticated(request(), $user);
            
        } catch (\Exception $e) {
            return redirect()->route('login')
                           ->withErrors(['error' => 'Facebook login failed: ' . $e->getMessage()]);
        }
    }
}
