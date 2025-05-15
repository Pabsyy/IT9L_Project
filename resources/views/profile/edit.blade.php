@extends('layouts.app')
@section('content')
<div class="min-h-screen flex">
    <main class="p-6 w-full">
        <!-- Profile Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                <div class="relative profile-image-upload">
                    <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                        {{-- Show user's profile image or fallback --}}
                        <img src="{{ $user->profile_picture_url ? asset('images/' . $user->profile_picture_url) : asset('images/default-profile.png') }}" alt="{{ $user->first_name }} {{ $user->last_name }}" class="w-full h-full object-cover object-top">
                    </div>
                    <div class="upload-overlay absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center opacity-0 transition-opacity duration-200 cursor-pointer">
                        <div class="w-8 h-8 flex items-center justify-center text-white">
                            <i class="ri-camera-line"></i>
                        </div>
                    </div>
                    {{-- Profile image upload form --}}
                    <form id="profile-picture-form" method="POST" action="{{ route('profile.picture') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="file" class="hidden" id="profile-image-input" name="profile_picture" accept="image/*" onchange="document.getElementById('profile-picture-form').submit();">
                    </form>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h2 class="text-2xl font-bold text-gray-800">{{ $user->first_name }} {{ $user->last_name }}</h2>
                    <p class="text-gray-600 mb-2"> Administartor </p>
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 mb-3">
                        <div class="flex items-center text-sm text-gray-500">
                            <div class="w-4 h-4 flex items-center justify-center mr-1">
                                <i class="ri-time-line"></i>
                            </div>
                            <span>
                                Last login: 
                                {{ $user->last_login_at ? $user->last_login_at->format('F d, Y \a\t h:i A') : 'Never' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
<button onclick="window.location.href='{{ route('suppliers') }}'" class="flex items-center px-3 py-1.5 text-sm text-gray-700 bg-gray-100 rounded-full hover:bg-gray-200 cursor-pointer">
                            <div class="w-4 h-4 flex items-center justify-center mr-1.5">
                                <i class="ri-user-star-line"></i>
                            </div>
                            <span>Manage Suppliers</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tab Navigation -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex overflow-x-auto" aria-label="Tabs">
<button class="tab-button text-primary border-b-2 border-primary px-6 py-4 text-sm font-medium cursor-pointer" data-tab="personal-info">
                        Personal Information
                    </button>
                    <button class="tab-button text-gray-500 hover:text-gray-700 hover:border-gray-300 px-6 py-4 text-sm font-medium border-b-2 border-transparent cursor-pointer" data-tab="account-settings">
                        Account Settings
                    </button>
                    <button class="tab-button text-gray-500 hover:text-gray-700 hover:border-gray-300 px-6 py-4 text-sm font-medium border-b-2 border-transparent cursor-pointer" data-tab="activity-history">
                        Activity History
                    </button>
                    {{-- Removed tab-button class from this button as it uses onclick --}}
                    <button onclick="window.location.href='{{ route('suppliers') }}'" class="text-gray-500 hover:text-gray-700 hover:border-gray-300 px-6 py-4 text-sm font-medium border-b-2 border-transparent flex items-center cursor-pointer">
                        <div class="w-4 h-4 flex items-center justify-center mr-1.5">
                            <i class="ri-arrow-left-line"></i>
                        </div>
                        <span>Back to Suppliers</span>
                    </button>
                </nav>
            </div>
            <!-- Tab Content -->
            <div class="p-6">
                <!-- Personal Information Tab -->
                <div id="personal-info" class="tab-content">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                                <i class="ri-close-line"></i>
                            </button>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:ring-opacity-50 focus:outline-none">
                                </div>
                                <div class="mb-4">
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:ring-opacity-50 focus:outline-none">
                                </div>
                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:ring-opacity-50 focus:outline-none">
                                </div>
                                <div class="mb-4">
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <input type="tel" id="phone" name="contact_number" value="{{ old('contact_number', $user->contact_number) }}" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:ring-opacity-50 focus:outline-none">
                                </div>
                            </div>
                            <div>
                                <div class="mb-4">
                                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                    <input type="text" id="location" name="address" value="{{ old('address', $user->address) }}" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:ring-opacity-50 focus:outline-none">
                                </div>
                                <div class="mb-4">
                                    <label for="timezone" class="block text-sm font-medium text-gray-700 mb-1">Time Zone</label>
                                    <div class="relative">
                                        <select id="timezone" name="timezone" class="appearance-none w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:ring-opacity-50 focus:outline-none pr-8">
                                            @foreach($timezones as $key => $label)
                                                <option value="{{ $key }}" {{ old('timezone', $user->timezone) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <div class="w-4 h-4 flex items-center justify-center">
                                                <i class="ri-arrow-down-s-line"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="language" class="block text-sm font-medium text-gray-700 mb-1">Language</label>
                                    <div class="relative">
                                        <select id="language" name="language" class="appearance-none w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:ring-opacity-50 focus:outline-none pr-8">
                                            @foreach($languages as $key => $label)
                                                <option value="{{ $key }}" {{ old('language', $user->language) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <div class="w-4 h-4 flex items-center justify-center">
                                                <i class="ri-arrow-down-s-line"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                    <input type="text" id="role" name="role" value="{{ old('role', $user->role) }}" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:ring-opacity-50 focus:outline-none" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-button text-gray-700 mr-3 hover:bg-gray-50 whitespace-nowrap !rounded-button">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-button hover:bg-primary/90 whitespace-nowrap !rounded-button">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
                <!-- Account Settings Tab -->
                <div id="account-settings" class="tab-content hidden">
                    <div class="space-y-8">
                        <!-- Password Change Section -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>
                            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                <form method="POST" action="{{ route('profile.password') }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-4">
                                        <label for="currentPassword" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                                        <input type="password" id="currentPassword" name="current_password" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:ring-opacity-50 focus:outline-none">
                                    </div>
                                    <div class="mb-4">
                                        <label for="newPassword" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                        <input type="password" id="newPassword" name="password" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:ring-opacity-50 focus:outline-none">
                                    </div>
                                    <div class="mb-4">
                                        <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                        <input type="password" id="confirmPassword" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:ring-opacity-50 focus:outline-none">
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-button hover:bg-primary/90 whitespace-nowrap !rounded-button">
                                            Update Password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Notification Preferences -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Notification Preferences</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Email Notifications</p>
                                            <p class="text-xs text-gray-500">Receive email updates about your account activity</p>
                                        </div>
                                        <label class="custom-switch">
                                            <input type="checkbox" checked>
                                            <span class="switch-slider"></span>
                                        </label>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Supplier Updates</p>
                                            <p class="text-xs text-gray-500">Get notified when suppliers update their information</p>
                                        </div>
                                        <label class="custom-switch">
                                            <input type="checkbox" checked>
                                            <span class="switch-slider"></span>
                                        </label>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Order Status Changes</p>
                                            <p class="text-xs text-gray-500">Receive notifications when order status changes</p>
                                        </div>
                                        <label class="custom-switch">
                                            <input type="checkbox" checked>
                                            <span class="switch-slider"></span>
                                        </label>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Marketing Communications</p>
                                            <p class="text-xs text-gray-500">Receive promotional emails and updates</p>
                                        </div>
                                        <label class="custom-switch">
                                            <input type="checkbox">
                                            <span class="switch-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Two-Factor Authentication -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Two-Factor Authentication</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Enable Two-Factor Authentication</p>
                                        <p class="text-xs text-gray-500">Add an extra layer of security to your account</p>
                                    </div>
                                    <label class="custom-switch">
                                        <input type="checkbox">
                                        <span class="switch-slider"></span>
                                    </label>
                                </div>
                                <div class="text-sm text-gray-600">
                                    <p>Two-factor authentication adds an extra layer of security to your account by requiring more than just a password to sign in.</p>
                                </div>
                            </div>
                        </div>
                        <!-- Session Management -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Active Sessions</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="space-y-4">
                                    <div class="flex items-start justify-between pb-4 border-b border-gray-200">
                                        <div>
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 flex items-center justify-center mr-3 bg-blue-100 text-blue-600 rounded-full">
                                                    <i class="ri-computer-line"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-700">MacBook Pro - San Francisco</p>
                                                    <p class="text-xs text-gray-500">Current session - Last active: Just now</p>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="text-xs text-blue-600 hover:text-blue-800">This Device</button>
                                    </div>
                                    <div class="flex items-start justify-between pb-4 border-b border-gray-200">
                                        <div>
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 flex items-center justify-center mr-3 bg-blue-100 text-blue-600 rounded-full">
                                                    <i class="ri-smartphone-line"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-700">iPhone 15 - San Francisco</p>
                                                    <p class="text-xs text-gray-500">Last active: April 20, 2025</p>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="text-xs text-red-600 hover:text-red-800">Sign Out</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Account Deletion -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Delete Account</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 mb-4">Once you delete your account, there is no going back. Please be certain.</p>
                                <form method="POST" action="{{ route('profile.destroy') }}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="password" name="password" placeholder="Confirm your password" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:ring-opacity-50 focus:outline-none mb-2">
                                    <button type="submit" class="px-4 py-2 bg-white border border-red-300 text-red-600 rounded-button hover:bg-red-50 whitespace-nowrap !rounded-button">
                                        Delete Account
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Activity History Tab -->
                <div id="activity-history" class="tab-content hidden">
                    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                        <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
                        <div class="flex flex-wrap items-center gap-3">
                            <div class="relative">
                                <button class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-button text-gray-700 hover:bg-gray-50 whitespace-nowrap !rounded-button">
                                    <div class="w-4 h-4 flex items-center justify-center mr-2">
                                        <i class="ri-calendar-line"></i>
                                    </div>
                                    <span>Last 30 Days</span>
                                    <div class="w-4 h-4 flex items-center justify-center ml-2">
                                        <i class="ri-arrow-down-s-line"></i>
                                    </div>
                                </button>
                            </div>
                            <div class="relative">
                                <button class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-button text-gray-700 hover:bg-gray-50 whitespace-nowrap !rounded-button">
                                    <div class="w-4 h-4 flex items-center justify-center mr-2">
                                        <i class="ri-filter-3-line"></i>
                                    </div>
                                    <span>All Activities</span>
                                    <div class="w-4 h-4 flex items-center justify-center ml-2">
                                        <i class="ri-arrow-down-s-line"></i>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="relative">
                            <div class="absolute left-4 top-0 bottom-0 w-px bg-gray-200"></div>
                            <div class="space-y-6 pl-12 relative">
                                @forelse($activities as $activity)
                                    <div class="relative">
                                        <div class="absolute -left-12 mt-1 w-8 h-8 flex items-center justify-center {{ $activity->icon_bg }} {{ $activity->icon_color }} rounded-full z-10">
                                            <i class="{{ $activity->icon }}"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $activity->description }}</p>
                                            <p class="text-xs text-gray-500">{{ $activity->created_at->format('F d, Y \a\t h:i A') }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500">No recent activity.</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="mt-6 text-center">
                            <button class="px-4 py-2 bg-white border border-gray-200 rounded-button text-gray-700 hover:bg-gray-50 whitespace-nowrap !rounded-button">
                                Load More Activity
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@push('styles')
    <style>
        :where([class^="ri-"])::before { content: ""; }
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type="number"] {
            -moz-appearance: textfield;
        }
        .custom-checkbox {
            position: relative;
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid #cbd5e1;
            border-radius: 4px;
            cursor: pointer;
        }
        .custom-checkbox.checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        .custom-checkbox.checked::after {
            content: "";
            position: absolute;
            left: 5px;
            top: 2px;
            width: 6px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        .custom-switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }
        .custom-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .switch-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            transition: .4s;
            border-radius: 24px;
        }
        .switch-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .switch-slider {
            background-color: #3b82f6;
        }
        input:checked + .switch-slider:before {
            transform: translateX(20px);
        }
        .profile-image-upload:hover .upload-overlay {
            opacity: 1;
        }
    </style>
    @endpush
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button[data-tab]');
        const tabContents = document.querySelectorAll('.tab-content');

        // Ensure only the first tab content is visible initially
        // (Relying on the 'hidden' class being present on others in HTML)
        tabContents.forEach((content, index) => {
            if (index !== 0) {
                content.classList.add('hidden');
            } else {
                content.classList.remove('hidden'); // Make sure first one is visible
            }
        });

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetTabId = button.getAttribute('data-tab');

                // Deactivate all buttons
                tabButtons.forEach(btn => {
                    btn.classList.remove('text-primary', 'border-primary');
                    btn.classList.add('text-gray-500', 'border-transparent');
                });

                // Hide all content
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });

                // Activate the clicked button
                button.classList.add('text-primary', 'border-primary');
                button.classList.remove('text-gray-500', 'border-transparent');

                // Show the target content
                const targetContent = document.getElementById(targetTabId);
                if (targetContent) {
                    targetContent.classList.remove('hidden');
                } else {
                    console.error(`Tab content with ID '${targetTabId}' not found.`);
                }
            });
        });

        // --- Other scripts can be re-added here if needed ---
        // Example: Profile image upload (if still required)
        const profileImageUpload = document.querySelector('.profile-image-upload');
        const profileImageInput = document.getElementById('profile-image-input');
        if (profileImageUpload && profileImageInput) {
            profileImageUpload.addEventListener('click', (e) => {
                if (e.target !== profileImageInput) { // Avoid re-triggering
                    profileImageInput.click();
                }
            });
            // The onchange handler for form submission is directly on the input element in HTML
        }

        // Example: Custom checkbox (if still required)
        const customCheckboxes = document.querySelectorAll('.custom-checkbox');
        customCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('click', () => {
                checkbox.classList.toggle('checked');
                // Note: This only toggles appearance, actual form submission needs handling
            });
        });
        // --- End of other scripts ---

    });
    </script>
    @endpush
    @endsection
