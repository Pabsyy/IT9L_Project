@extends('layouts.guest')

@section('content')
    <div class="bg-transparent min-h-screen flex items-center justify-center p-4">
        <div class="relative w-full max-w-4xl flex rounded-xl overflow-hidden shadow-2xl">
            <!-- Left side -->
            <div class="hidden md:block w-2/5 bg-primary relative overflow-hidden">
                <div class="absolute inset-0 opacity-20">
                    <div class="gear-animation absolute top-1/4 left-1/4 w-40 h-40 border-8 border-white rounded-full"></div>
                    <div class="gear-animation absolute bottom-1/4 right-1/4 w-32 h-32 border-8 border-white rounded-full" style="animation-direction: reverse;"></div>
                    <div class="gear-animation absolute top-1/2 right-1/3 w-24 h-24 border-8 border-white rounded-full" style="animation-duration: 15s;"></div>
                </div>
                <div class="relative h-full flex flex-col justify-center items-center text-white p-8 z-10">
                    <img src="{{ asset('images/UnderTheHoodLogo.png') }}" alt="UnderTheHood Logo" class="h-full w-auto rounded-lg object-contain">
                    <p class="text-center mb-8">Join our community! Create an admin account to start managing your system.</p>
                    <div class="flex flex-wrap justify-center gap-4 mb-8">
                        <div class="w-12 h-12 flex items-center justify-center bg-white bg-opacity-20 rounded-full">
                            <i class="ri-user-settings-line text-white ri-lg"></i>
                        </div>
                        <div class="w-12 h-12 flex items-center justify-center bg-white bg-opacity-20 rounded-full">
                            <i class="ri-shield-keyhole-line text-white ri-lg"></i>
                        </div>
                        <div class="w-12 h-12 flex items-center justify-center bg-white bg-opacity-20 rounded-full">
                            <i class="ri-settings-line text-white ri-lg"></i>
                        </div>
                    </div>
                    <p class="text-sm text-center opacity-80">Get started with user management, performance monitoring, and settings control.</p>
                </div>
            </div>

            <!-- Right side -->
            <div class="w-full md:w-3/5 bg-white p-8 flex flex-col justify-center relative">
                @if (session('success'))
                    <div id="success-alert" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <div id="register-form" class="form-container slide-in">
                    <div class="mb-8 text-center md:text-left">
                        <h3 class="text-2xl font-bold text-gray-800">Create Admin Account</h3>
                        <p class="text-gray-600">Create a new administrator account</p>
                    </div>
                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="ri-user-line"></i>
                                </span>
                                <x-text-input id="first_name" class="w-full pl-10 py-2 text-sm border border-gray-300 rounded-md focus:ring-primary focus:border-primary" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="given-name" placeholder="Enter your first name"/>
                                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="ri-user-line"></i>
                                </span>
                                <x-text-input id="last_name" name="last_name" class="w-full pl-10 py-2 text-sm border border-gray-300 rounded-md focus:ring-primary focus:border-primary" type="text" name="last_name" :value="old('last_name')" required autocomplete="family-name" placeholder="Enter your last name"/>
                                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="ri-mail-line"></i>
                                </span>
                                <x-text-input id="email" class="w-full pl-10 py-2 text-sm border border-gray-300 rounded-md focus:ring-primary focus:border-primary" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Enter your email"/>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="ri-lock-password-line"></i>
                                </span>
                                <x-text-input id="password" class="w-full pl-10 py-2 text-sm border border-gray-300 rounded-md focus:ring-primary focus:border-primary"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" placeholder="Enter your password" oninput="checkPasswordStrength()"/>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="ri-lock-password-line"></i>
                                </span>
                                <x-text-input id="password_confirmation" class="w-full pl-10 py-2 text-sm border border-gray-300 rounded-md focus:ring-primary focus:border-primary"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password"/>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                <p id="password-error" class="text-red-500 text-sm mt-1 hidden">Passwords do not match.</p>
                            </div>
                        </div>

                        <!-- Terms Checkbox -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5 mt-1">
                                <input type="checkbox" id="terms" name="terms" class="hidden">
                                <label for="terms" class="flex items-center cursor-pointer">
                                    <div class="w-5 h-5 border border-gray-300 rounded flex items-center justify-center mr-2 custom-checkbox bg-white">
                                        <i class="ri-check-line text-white text-xs hidden"></i>
                                    </div>
                                </label>
                            </div>
                            <div class="ml-2">
                                <label for="terms" class="text-sm text-gray-600 user-select-none">
                                    I acknowledge and accept the administrator <a href="#" class="text-primary hover:underline">Terms of Service</a> and <a href="#" class="text-primary hover:underline">Security Policy</a>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full h-12 bg-primary text-white font-medium rounded-button hover:bg-primary/90 transition-all duration-300 flex items-center justify-center whitespace-nowrap">
                            Create Account
                        </button>
                        <div class="text-center mt-4">
                            <p class="text-gray-600 text-sm">
                                Already have an account?
                                <a href="{{ route('login') }}" class="text-primary font-medium hover:text-primary/80">Sign In</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
