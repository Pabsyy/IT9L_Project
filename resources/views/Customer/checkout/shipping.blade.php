@extends('customer.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Progress Bar -->
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-8">
                <div class="flex items-center justify-center">
                    <div class="flex items-center space-x-16">
                        <!-- Step 1: Shipping -->
                        <div class="flex items-center relative">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-600 text-white font-semibold shadow-lg ring-4 ring-indigo-200">
                                1
                            </div>
                            <div class="absolute top-1/2 left-full w-16 h-0.5 bg-indigo-600 transform -translate-y-1/2"></div>
                            <span class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-sm font-medium text-indigo-600 whitespace-nowrap">Shipping</span>
                        </div>

                        <!-- Step 2: Payment -->
                        <div class="flex items-center relative">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 text-gray-400 font-semibold">
                                2
                            </div>
                            <div class="absolute top-1/2 left-full w-16 h-0.5 bg-gray-200 transform -translate-y-1/2"></div>
                            <span class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-sm font-medium text-gray-500 whitespace-nowrap">Payment</span>
                        </div>

                        <!-- Step 3: Review -->
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 text-gray-400 font-semibold">
                                3
                            </div>
                            <span class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-sm font-medium text-gray-500 whitespace-nowrap">Review</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Column - Shipping Form -->
            <div class="flex-1">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-8">Shipping Information</h2>
                        <form action="{{ route('customer.checkout.shipping.save') }}" method="POST" class="space-y-8" id="shipping-form">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="first_name" class="block text-sm font-medium text-gray-700">
                                        First Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="first_name" id="first_name" required
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200"
                                        aria-required="true"
                                        aria-describedby="first_name_error"
                                        value="{{ $defaultAddress ? explode(' ', $defaultAddress->name)[0] : old('first_name') }}">
                                    <div id="first_name_error" class="text-red-500 text-sm mt-1 hidden"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="last_name" class="block text-sm font-medium text-gray-700">
                                        Last Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="last_name" id="last_name" required
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200"
                                        aria-required="true"
                                        aria-describedby="last_name_error"
                                        value="{{ $defaultAddress ? (explode(' ', $defaultAddress->name)[1] ?? '') : old('last_name') }}">
                                    <div id="last_name_error" class="text-red-500 text-sm mt-1 hidden"></div>
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <label for="street_address" class="block text-sm font-medium text-gray-700">
                                        Street Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="street_address" id="street_address" required
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200"
                                        aria-required="true"
                                        aria-describedby="street_address_error"
                                        value="{{ $defaultAddress ? $defaultAddress->street_address : old('street_address') }}">
                                    <div id="street_address_error" class="text-red-500 text-sm mt-1 hidden"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="city" class="block text-sm font-medium text-gray-700">
                                        City <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="city" id="city" required
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200"
                                        aria-required="true"
                                        aria-describedby="city_error"
                                        value="{{ $defaultAddress ? $defaultAddress->city : old('city') }}">
                                    <div id="city_error" class="text-red-500 text-sm mt-1 hidden"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="state" class="block text-sm font-medium text-gray-700">
                                        State/Province <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="state" id="state" required
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200"
                                        aria-required="true"
                                        aria-describedby="state_error"
                                        value="{{ $defaultAddress ? $defaultAddress->state : old('state') }}">
                                    <div id="state_error" class="text-red-500 text-sm mt-1 hidden"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700">
                                        Postal Code <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="postal_code" id="postal_code" required
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200"
                                        aria-required="true"
                                        aria-describedby="postal_code_error"
                                        value="{{ $defaultAddress ? $defaultAddress->postal_code : old('postal_code') }}">
                                    <div id="postal_code_error" class="text-red-500 text-sm mt-1 hidden"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">
                                        Phone Number <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" name="phone" id="phone" required
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200"
                                        aria-required="true"
                                        aria-describedby="phone_error"
                                        value="{{ $defaultAddress ? $defaultAddress->phone_number : old('phone') }}">
                                    <div id="phone_error" class="text-red-500 text-sm mt-1 hidden"></div>
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <label for="delivery_instructions" class="block text-sm font-medium text-gray-700">Delivery Instructions (Optional)</label>
                                    <textarea name="delivery_instructions" id="delivery_instructions" rows="3"
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200"
                                        placeholder="Add any special instructions for delivery...">{{ $defaultAddress ? $defaultAddress->additional_info : old('delivery_instructions') }}</textarea>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-gray-900">Delivery Method</h3>
                                <div class="space-y-4">
                                    <label class="flex items-center p-6 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-100 transition-all duration-200">
                                        <input type="radio" name="delivery_method" value="delivery" class="h-5 w-5 text-primary focus:ring-primary border-gray-300" checked>
                                        <div class="ml-4">
                                            <span class="block text-base font-medium text-gray-900">Standard Delivery</span>
                                            <span class="block text-sm text-gray-500 mt-1">Delivery to your address (3-5 business days)</span>
                                        </div>
                                        <span class="ml-auto text-lg font-semibold text-gray-900">₱150.00</span>
                                    </label>
                                    <label class="flex items-center p-6 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-100 transition-all duration-200">
                                        <input type="radio" name="delivery_method" value="pickup" class="h-5 w-5 text-primary focus:ring-primary border-gray-300">
                                        <div class="ml-4">
                                            <span class="block text-base font-medium text-gray-900">Store Pickup</span>
                                            <span class="block text-sm text-gray-500 mt-1">Pick up from our store (Free)</span>
                                        </div>
                                        <span class="ml-auto text-lg font-semibold text-gray-900">Free</span>
                                    </label>
                                </div>
                            </div>

                            <div class="flex gap-4 pt-6">
                                <a href="{{ route('customer.cart.view') }}" 
                                    class="flex-1 bg-gray-100 text-gray-700 py-4 px-6 rounded-xl font-semibold text-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 text-center">
                                    Back to Cart
                                </a>
                                <button type="submit" 
                                    class="flex-1 bg-indigo-600 text-white py-4 px-6 rounded-xl font-semibold text-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                                    id="submit-button">
                                    <span class="flex items-center justify-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" id="loading-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Continue to Payment
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="lg:w-96">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden sticky top-8">
                    <div class="p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h2>
                        <div class="space-y-6">
                            @foreach($cartItems as $item)
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0 w-20 h-20">
                                    <img src="{{ asset('storage/images/' . ($item->options->image ?? 'default.png')) }}" alt="{{ $item->name }}" 
                                        class="w-full h-full object-cover rounded-xl shadow-sm">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-medium text-gray-900 truncate">{{ $item->name }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">Qty: {{ $item->qty }}</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">₱{{ number_format($item->price * $item->qty, 2) }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-200 mt-6 pt-6 space-y-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="text-gray-900 font-medium">₱{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Shipping</span>
                                <span class="text-gray-900 font-medium">₱{{ number_format($shipping, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax (12%)</span>
                                <span class="text-gray-900 font-medium">₱{{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-base font-bold pt-4 border-t border-gray-200">
                                <span class="text-gray-900">Total</span>
                                <span class="text-indigo-600">₱{{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <!-- Trust Indicators -->
                        <div class="mt-6 space-y-4">
                            <div class="flex items-center justify-center text-sm text-gray-500">
                                <i class="fas fa-shield-alt mr-2"></i>
                                Secure Checkout
                            </div>
                            <div class="flex items-center justify-center space-x-4 text-sm text-gray-500">
                                <span class="flex items-center">
                                    <i class="fas fa-lock mr-2"></i>
                                    SSL Encrypted
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-credit-card mr-2"></i>
                                    Secure Payment
                                </span>
                            </div>
                            <div class="text-center text-sm text-gray-500">
                                <a href="{{ route('customer.privacy-policy') }}" class="hover:text-indigo-600">Privacy Policy</a>
                                <span class="mx-2">•</span>
                                <a href="{{ route('customer.terms') }}" class="hover:text-indigo-600">Terms of Service</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('shipping-form');
    const submitButton = document.getElementById('submit-button');
    const loadingSpinner = document.getElementById('loading-spinner');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        submitButton.disabled = true;
        loadingSpinner.classList.remove('hidden');
        
        // Basic form validation
        let isValid = true;
        const requiredFields = ['first_name', 'last_name', 'street_address', 'city', 'state', 'postal_code', 'phone'];
        
        requiredFields.forEach(field => {
            const input = document.getElementById(field);
            const error = document.getElementById(`${field}_error`);
            
            if (!input.value.trim()) {
                isValid = false;
                error.textContent = 'This field is required';
                error.classList.remove('hidden');
                input.classList.add('border-red-500');
            } else {
                error.classList.add('hidden');
                input.classList.remove('border-red-500');
            }
        });

        // Phone number validation
        const phoneInput = document.getElementById('phone');
        const phoneError = document.getElementById('phone_error');
        const phoneRegex = /^\+?[\d\s-]{10,}$/;
        
        if (!phoneRegex.test(phoneInput.value)) {
            isValid = false;
            phoneError.textContent = 'Please enter a valid phone number';
            phoneError.classList.remove('hidden');
            phoneInput.classList.add('border-red-500');
        }

        if (isValid) {
            form.submit();
        } else {
            // Reset loading state if validation fails
            submitButton.disabled = false;
            loadingSpinner.classList.add('hidden');
        }
    });
});
</script>
@endpush
@endsection 