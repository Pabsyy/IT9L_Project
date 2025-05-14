<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'timezones' => [
                'pacific' => 'Pacific Time (PT)',
                'mountain' => 'Mountain Time (MT)',
                'central' => 'Central Time (CT)',
                'eastern' => 'Eastern Time (ET)',
            ],
            'languages' => [
                'english' => 'English',
                'spanish' => 'Spanish',
                'french' => 'French',
                'german' => 'German',
            ],
            'activities' => [],
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user(); // Ensure $user is an instance of the User model
        if (!($user instanceof \App\Models\User)) {
            throw new \RuntimeException('Authenticated user is not an instance of the User model.');
        }

        logger('User object: ' . json_encode($user));
        logger('User ID: ' . $user->id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id . ',id',
            'contact_number' => 'nullable|string|max:255',
            'address'    => 'nullable|string|max:255',
            'role'       => 'nullable|string|max:255',
        ]);

        $user->update($validated);

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
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.edit')->with('status', 'Password updated successfully.');
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
