@extends('layouts.app')

@section('content')
<div class="relative min-h-screen flex items-center justify-center bg-gray-100 overflow-hidden">
    <!-- Background Design -->
    <div class="absolute inset-0 z-0 flex items-center justify-center opacity-10">
        <h1 class="text-9xl font-extrabold text-gray-300 tracking-widest">UnderTheHood</h1>
    </div>

    <div class="relative z-10 bg-white p-10 rounded-lg shadow-lg w-full max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white text-lg font-medium rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 transition-transform duration-300 hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>
        <h2 class="text-3xl font-bold text-gray-800 mb-8 flex items-center">
            <i class="fas fa-user-cog text-indigo-600 mr-3"></i> Account Settings
        </h2>

        <!-- Profile Completion Prompt -->
        <div class="mb-6">
            @if(is_null(Auth::user()->address) || is_null(Auth::user()->contact_number))
                <div class="p-4 mb-6 text-sm text-yellow-800 bg-yellow-100 border border-yellow-300 rounded-lg" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i> Please finish your profile by adding your address and contact number.
                </div>
            @endif
        </div>

        <!-- Profile Picture Section -->
        <div class="mb-10">
            <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-camera text-indigo-600 mr-3"></i> Profile Picture
            </h3>
            <div class="flex items-center">
                <img src="{{ Auth::user()->profile_picture_url ? asset('images/' . Auth::user()->profile_picture_url) : asset('images/default-avatar.png') }}" alt="Profile Picture" class="w-24 h-24 rounded-full border-2 border-gray-300 mr-6 transition-transform duration-300 hover:scale-110">
                <form method="POST" action="{{ route('profile.update.picture') }}" enctype="multipart/form-data" class="flex flex-col items-start">
                    @csrf
                    @method('PUT')
                    <label for="profile_picture" class="px-5 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg shadow-md cursor-pointer hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-transform duration-300 hover:scale-105">
                        Choose File
                    </label>
                    <input type="file" name="profile_picture" id="profile_picture" class="hidden">
                    <button type="submit" class="mt-4 px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-transform duration-300 hover:scale-105">
                        Update Picture
                    </button>
                </form>
            </div>
        </div>

        <!-- Account Information Section -->
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')
            <div class="mb-10">
                <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-user text-indigo-600 mr-3"></i> Account Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', Auth::user()->first_name) }}" class="mt-2 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-base">
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', Auth::user()->last_name) }}" class="mt-2 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-base">
                    </div>
                </div>
                <div class="mt-6">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}" class="mt-2 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-base">
                </div>
                <div class="mt-6">
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" name="address" id="address" value="{{ old('address', Auth::user()->address) }}" class="mt-2 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-base">
                </div>
                <div class="mt-6">
                    <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                    <input type="text" name="contact_number" id="contact_number" value="{{ old('contact_number', Auth::user()->contact_number) }}" class="mt-2 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-base">
                </div>
            </div>

            <!-- Password Update Section -->
            <div class="mb-10">
                <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-lock text-indigo-600 mr-3"></i> Change Password
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input type="password" name="current_password" id="current_password" class="mt-2 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-base">
                    </div>
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" name="new_password" id="new_password" class="mt-2 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-base">
                    </div>
                </div>
                <div class="mt-6">
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="mt-2 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-base">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-transform duration-300 hover:scale-105">
                    Save Changes
                </button>
            </div>
        </form>

        <!-- Delete Account Section -->
        <div class="mt-12">
            <h3 class="text-xl font-semibold text-red-600 mb-6 flex items-center">
                <i class="fas fa-trash text-red-600 mr-3"></i> Delete Account
            </h3>
            <p class="text-sm text-gray-600 mb-6">Once you delete your account, there is no going back. Please be certain.</p>
            <button id="delete-account-button" class="px-6 py-3 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-transform duration-300 hover:scale-105">
                Delete Account
            </button>
        </div>
    </div>
</div>

<!-- Delete Account Confirmation Modal -->
<div id="delete-account-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md animate-fade-in">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Are you sure?</h3>
        <p class="text-sm text-gray-600 mb-6">This action cannot be undone. This will permanently delete your account.</p>
        <form method="POST" action="{{ route('profile.delete') }}">
            @csrf
            @method('DELETE')
            <div class="flex justify-end space-x-4">
                <button type="button" id="cancel-delete-button" class="px-5 py-2 bg-gray-300 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Confirm
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const deleteAccountButton = document.getElementById('delete-account-button');
    const deleteAccountModal = document.getElementById('delete-account-modal');
    const cancelDeleteButton = document.getElementById('cancel-delete-button');

    deleteAccountButton.addEventListener('click', () => {
        deleteAccountModal.classList.remove('hidden');
    });

    cancelDeleteButton.addEventListener('click', () => {
        deleteAccountModal.classList.add('hidden');
    });

    // Close modal if clicked outside
    deleteAccountModal.addEventListener('click', (event) => {
        if (event.target === deleteAccountModal) {
            deleteAccountModal.classList.add('hidden');
        }
    });
</script>
@endsection
