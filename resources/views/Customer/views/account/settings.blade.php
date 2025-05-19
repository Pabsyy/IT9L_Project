@extends('customer.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Account Settings</h1>
            <p class="mt-2 text-sm text-gray-600">Manage your account preferences and information</p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1">
                <nav class="space-y-1">
                    <a href="#profile" class="bg-white text-indigo-600 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-user text-indigo-500 mr-3 flex-shrink-0"></i>
                        Profile Information
                    </a>
                    <a href="#security" class="text-gray-600 hover:bg-white hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-lock text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0"></i>
                        Security
                    </a>
                    <a href="#notifications" class="text-gray-600 hover:bg-white hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-bell text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0"></i>
                        Notifications
                    </a>
                    <a href="#preferences" class="text-gray-600 hover:bg-white hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-cog text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0"></i>
                        Preferences
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Profile Information -->
                <div id="profile" class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Profile Information</h3>
                        <div class="mt-6">
                            <form action="{{ route('account.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="space-y-6">
                                    <!-- Profile Photo -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Photo</label>
                                        <div class="mt-2 flex items-center">
                                            <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                                                <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                                            </span>
                                            <button type="button" class="ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Change
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Name -->
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                        <input type="text" name="name" id="name" value="{{ auth()->user()->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" name="email" id="email" value="{{ auth()->user()->email }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>

                                    <!-- Phone -->
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                        <input type="tel" name="phone" id="phone" value="{{ auth()->user()->phone }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Save Changes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Security -->
                <div id="security" class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Security</h3>
                        <div class="mt-6">
                            <form action="{{ route('account.profile.password') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="space-y-6">
                                    <div>
                                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                                        <input type="password" name="current_password" id="current_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>

                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                                        <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Update Password
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <div id="notifications" class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Notification Preferences</h3>
                        <div class="mt-6">
                            <form action="#" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" name="email_notifications" id="email_notifications" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="email_notifications" class="font-medium text-gray-700">Email Notifications</label>
                                            <p class="text-gray-500">Receive email notifications about your orders and account activity.</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" name="sms_notifications" id="sms_notifications" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="sms_notifications" class="font-medium text-gray-700">SMS Notifications</label>
                                            <p class="text-gray-500">Receive text messages about your orders and delivery updates.</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" name="marketing_emails" id="marketing_emails" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="marketing_emails" class="font-medium text-gray-700">Marketing Emails</label>
                                            <p class="text-gray-500">Receive emails about new products, special offers, and promotions.</p>
                                        </div>
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Save Preferences
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Preferences -->
                <div id="preferences" class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Account Preferences</h3>
                        <div class="mt-6">
                            <form action="#" method="POST">
                                @csrf
                                <div class="space-y-6">
                                    <div>
                                        <label for="language" class="block text-sm font-medium text-gray-700">Language</label>
                                        <select name="language" id="language" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option value="en">English</option>
                                            <option value="tl">Filipino</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="currency" class="block text-sm font-medium text-gray-700">Currency</label>
                                        <select name="currency" id="currency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option value="PHP">Philippine Peso (₱)</option>
                                            <option value="USD">US Dollar ($)</option>
                                        </select>
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Save Preferences
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Add smooth scrolling for navigation links
    document.querySelectorAll('nav a').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            targetElement.scrollIntoView({ behavior: 'smooth' });
            
            // Update active state
            document.querySelectorAll('nav a').forEach(a => {
                a.classList.remove('bg-white', 'text-indigo-600');
                a.classList.add('text-gray-600', 'hover:bg-white', 'hover:text-gray-900');
            });
            this.classList.remove('text-gray-600', 'hover:bg-white', 'hover:text-gray-900');
            this.classList.add('bg-white', 'text-indigo-600');
        });
    });
</script>
@endpush
@endsection 