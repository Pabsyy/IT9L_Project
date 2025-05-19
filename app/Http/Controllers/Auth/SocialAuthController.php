<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')
                ->with(['prompt' => 'select_account'])
                ->redirect();
        } catch (Exception $e) {
            Log::error('Google OAuth Redirect Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Unable to connect to Google. Please try again later.');
        }
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            
            $finduser = User::where('google_id', $user->id)
                           ->orWhere('email', $user->email)
                           ->first();

            if ($finduser) {
                // Update existing user's Google ID if they're logging in with Google for the first time
                if (empty($finduser->google_id)) {
                    $finduser->update([
                        'google_id' => $user->id,
                        'avatar' => $user->avatar,
                        'provider' => 'google'
                    ]);
                }
                
                Auth::login($finduser);
                return redirect()->intended('/account/dashboard');
            } else {
                // Create new user
                $names = explode(' ', $user->name);
                $firstName = $names[0] ?? '';
                $lastName = $names[1] ?? '';
                
                $newUser = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => bcrypt(Str::random(16)),
                    'avatar' => $user->avatar,
                    'provider' => 'google',
                    'email_verified_at' => now(), // Google accounts are already verified
                ]);

                Auth::login($newUser);
                return redirect()->intended('/account/dashboard')
                    ->with('success', 'Welcome! Your account has been created successfully.');
            }
        } catch (Exception $e) {
            Log::error('Google OAuth Callback Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Something went wrong with Google login. Please try again later.');
        }
    }

    public function redirectToFacebook()
    {
        try {
            return Socialite::driver('facebook')->redirect();
        } catch (Exception $e) {
            Log::error('Facebook OAuth Redirect Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Unable to connect to Facebook. Please try again later.');
        }
    }

    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            
            $finduser = User::where('facebook_id', $user->id)
                           ->orWhere('email', $user->email)
                           ->first();

            if ($finduser) {
                // Update existing user's Facebook ID if they're logging in with Facebook for the first time
                if (empty($finduser->facebook_id)) {
                    $finduser->update([
                        'facebook_id' => $user->id,
                        'avatar' => $user->avatar,
                        'provider' => 'facebook'
                    ]);
                }
                
                Auth::login($finduser);
                return redirect()->intended('/account/dashboard');
            } else {
                // Create new user
                $names = explode(' ', $user->name);
                $firstName = $names[0] ?? '';
                $lastName = $names[1] ?? '';
                
                $newUser = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $user->email,
                    'facebook_id' => $user->id,
                    'password' => bcrypt(Str::random(16)),
                    'avatar' => $user->avatar,
                    'provider' => 'facebook',
                    'email_verified_at' => now(), // Facebook accounts are already verified
                ]);

                Auth::login($newUser);
                return redirect()->intended('/account/dashboard')
                    ->with('success', 'Welcome! Your account has been created successfully.');
            }
        } catch (Exception $e) {
            Log::error('Facebook OAuth Callback Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Something went wrong with Facebook login. Please try again later.');
        }
    }
} 