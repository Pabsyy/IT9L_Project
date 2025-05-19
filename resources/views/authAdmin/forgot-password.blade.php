@extends('layouts.guest')

@section('content')
    <div class="bg-transparent min-h-screen flex items-center justify-center p-4">
        <div class="relative w-full max-w-4xl flex rounded-xl overflow-hidden shadow-2xl">
            <!-- Left side - decorative area -->
            <div class="hidden md:block w-2/5 bg-primary relative overflow-hidden">
                <div class="absolute inset-0 opacity-20">
                    <div class="gear-animation absolute top-1/4 left-1/4 w-40 h-40 border-8 border-white rounded-full"></div>
                    <div class="gear-animation absolute bottom-1/4 right-1/4 w-32 h-32 border-8 border-white rounded-full" style="animation-direction: reverse;"></div>
                    <div class="gear-animation absolute top-1/2 right-1/3 w-24 h-24 border-8 border-white rounded-full" style="animation-duration: 15s;"></div>
                </div>
                <div class="relative h-full flex flex-col justify-center items-center text-white p-8 z-10">
                    <img src="{{ asset('images/UnderTheHoodLogo.png') }}" alt="UnderTheHood Logo" class="h-full w-auto rounded-lg object-contain">
                    <p class="text-center mb-8">Forgot your password? No worries, we'll send you a reset link.</p>
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
                    <p class="text-sm text-center opacity-80">Enter your email address to receive a password reset link.</p>
                </div>
            </div>

            <!-- Right side - form area -->
            <div class="w-full md:w-3/5 bg-white p-8 flex flex-col justify-center relative">
                <!-- Forgot Password Form -->
                <div id="forgot-form" class="form-container slide-in">
                    <div class="mb-8 text-center md:text-left">
                        <h3 class="text-2xl font-bold text-gray-800">Reset Password</h3>
                        <p class="text-gray-600">Enter your email to receive a password reset link</p>
                    </div>
                    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                        @csrf

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="ri-mail-line"></i>
                                </span>
                                <x-text-input 
                                    id="email" 
                                    class="w-full pl-10 py-2 text-sm border border-gray-300 rounded-md focus:ring-primary focus:border-primary transition duration-200" 
                                    type="email" 
                                    name="email" 
                                    :value="old('email')" 
                                    required 
                                    autofocus 
                                    autocomplete="username" 
                                    placeholder="Enter your email" 
                                />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 ml-1 text-sm text-red-600" />
                        </div>

                        <button class="w-full h-12 bg-primary text-white font-medium rounded-button hover:bg-primary/90 transition-all duration-300 flex items-center justify-center whitespace-nowrap">
                            {{ __('Send Reset Link') }}
                        </button>

                        <div class="text-center mt-4">
                            <a href="{{ route('customer.login') }}" class="text-primary font-medium hover:text-primary/80">
                                Back to login
                            </a>
                        </div>
                    </form>
                    <!-- Success message (hidden by default) -->
                    @if (session('status'))
                        <div id="reset-success" class="text-center py-8">
                            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
                                <i class="ri-check-line text-green-500 ri-2x"></i>
                            </div>
                            <h3 class="text-xl font-medium text-gray-800 mb-2">Email Sent</h3>
                            <p class="text-gray-600 mb-6">{{ session('status') }}</p>
                            <a href="{{ route('customer.login') }}" class="text-primary font-medium hover:text-primary/80">
                                Return to Login
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
