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
                    <p class="text-center mb-8">Welcome back! Sign in to access the admin dashboard and manage your system.</p>
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
                    <p class="text-sm text-center opacity-80">Manage users, monitor performance, and control settings with ease.</p>
                </div>
            </div>

            <!-- Right side - form area -->
            <div class="w-full md:w-3/5 bg-white p-8 flex flex-col justify-center relative">
                <!-- Login Form -->
                <div id="login-form" class="form-container slide-in">
                    <div class="mb-8 text-center md:text-left">
                        <h3 class="text-2xl font-bold text-gray-800">Admin Login</h3>
                        <p class="text-gray-600">Sign in to access admin dashboard</p>
                    </div>

                    <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
                        @csrf

                        @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-error-warning-line text-red-500 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium">Error</p>
                                    <p class="text-sm">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if (session('warning'))
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-alert-line text-yellow-500 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium">Warning</p>
                                    <p class="text-sm">{{ session('warning') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @error('bcrypt')
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-error-warning-line text-red-500 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium">Password Error</p>
                                    <p class="text-sm">This password does not use the Bcrypt algorithm. Please reset your password or contact the administrator.</p>
                                </div>
                            </div>
                        </div>
                        @enderror

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                    <i class="ri-mail-line text-gray-400"></i>
                                </div>
                                <x-text-input id="email" class="block w-full pl-10 pr-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Enter your email"/>
                            </div>
                            @if ($errors->has('email'))
                                <div class="mt-2 text-sm text-red-600 bg-red-50 border border-red-100 rounded-md p-2">
                                    <div class="flex items-center gap-2">
                                        <i class="ri-alert-fill"></i>
                                        <span>{{ $errors->first('email') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Password Field -->
                        <div>
                            <div class="flex justify-between mb-1">
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                    <i class="ri-lock-line text-gray-400"></i>
                                </div>
                                <x-text-input id="password" class="block w-full pl-10 pr-10 py-2 border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200"
                                              type="password"
                                              name="password"
                                              required autocomplete="current-password" placeholder="Enter your password"/>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer toggle-password z-10">
                                    <i class="ri-eye-off-line text-gray-400"></i>
                                </div>
                            </div>
                            @if ($errors->has('password'))
                                <div class="mt-2 text-sm text-red-600 bg-red-50 border border-red-100 rounded-md p-2">
                                    <div class="flex items-center gap-2">
                                        <i class="ri-alert-fill"></i>
                                        <span>{{ $errors->first('password') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" id="remember-me" name="remember" class="hidden">
                                <label for="remember-me" class="flex items-center cursor-pointer">
                                    <div class="w-5 h-5 border border-gray-300 rounded flex items-center justify-center mr-2 custom-checkbox bg-white">
                                        <i class="ri-check-line text-white text-xs hidden"></i>
                                    </div>
                                    <span class="text-sm text-gray-600">{{ __('Remember me') }}</span>
                                </label>
                            </div>
                            @if (Route::has('admin.password.request'))
                                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('admin.password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </div>

                        <button class="w-full h-12 bg-primary text-white font-medium rounded-button hover:bg-primary/90 transition-all duration-300 flex items-center justify-center whitespace-nowrap">
                            {{ __('Sign In') }}
                        </button>

                        <div class="text-center mt-6">
                            <p class="text-gray-600 text-sm">
                                Don't have an account?
                                <a class="text-primary font-medium hover:text-primary/80" href="{{ route('admin.register') }}">Register Now</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
