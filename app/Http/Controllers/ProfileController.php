<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::user()->UserID . ',UserID',
            'address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:15',
        ]);

        $user = Auth::user();
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->address = $request->input('address');
        $user->contact_number = $request->input('contact_number');
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's profile picture.
     */
    public function updatePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();

        // Delete the old profile picture if it exists
        if ($user->profile_picture_url && file_exists(public_path('images/' . $user->profile_picture_url))) {
            unlink(public_path('images/' . $user->profile_picture_url));
        }

        // Store the new profile picture
        $imageName = time() . '.' . $request->profile_picture->extension();
        $request->profile_picture->move(public_path('images'), $imageName);

        // Update the user's profile picture URL
        $user->profile_picture_url = $imageName;
        $user->save();

        return redirect()->back()->with('status', 'Profile picture updated successfully.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // Delete the user's profile picture if it exists
        if ($user->profile_picture_url) {
            Storage::delete($user->profile_picture_url);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('status', 'Your account has been deleted successfully.');
    }
}
