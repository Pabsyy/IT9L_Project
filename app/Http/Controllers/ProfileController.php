<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if it exists
            if ($user->profile_picture_url) {
                Storage::disk('public')->delete('images/' . $user->profile_picture_url);
            }

            // Store the new image
            $imageName = time() . '.' . $request->profile_picture->extension();
            $request->profile_picture->storeAs('images', $imageName, 'public');
            
            $user->profile_picture_url = $imageName;
        }

        // Update user information
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'address' => $request->address
        ]);

        // If email was changed, require re-verification
        if ($user->wasChanged('email')) {
            $user->email_verified_at = null;
            $user->save();
            $user->sendEmailVerificationNotification();
        }

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password_confirmation' => 'required',
        ]);

        $user = $request->user();

        // Verify the password
        if (!Hash::check($request->password_confirmation, $user->password)) {
            return back()->withErrors([
                'password_confirmation' => 'The provided password is incorrect.'
            ]);
        }

        // Delete associated data
        $user->paymentMethods()->delete(); // Delete payment methods
        $user->orders()->delete(); // Delete orders
        
        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Your account has been permanently deleted.');
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if it exists
            if ($user->profile_picture_url) {
                Storage::disk('public')->delete('images/' . $user->profile_picture_url);
            }

            // Store the new image
            $imageName = time() . '.' . $request->profile_picture->extension();
            $request->profile_picture->storeAs('images', $imageName, 'public');

            // Update user profile picture URL
            $user->update(['profile_picture_url' => $imageName]);

            return redirect()->back()->with('success', 'Profile picture updated successfully');
        }

        return redirect()->back()->with('error', 'No image was uploaded');
    }
}
