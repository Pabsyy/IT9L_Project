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
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-600 text-white font-semibold shadow-lg ring-4 ring-indigo-200">
                                2
                            </div>
                            <div class="absolute top-1/2 left-full w-16 h-0.5 bg-indigo-600 transform -translate-y-1/2"></div>
                            <span class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-sm font-medium text-indigo-600 whitespace-nowrap">Payment</span>
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
            <!-- Left Column - Payment Form -->
            <div class="flex-1">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-8">Payment Information</h2>
                        <form action="{{ route('customer.checkout.payment.save') }}" method="POST" class="space-y-8" id="payment-form">
                            @csrf
                            <div class="space-y-6">
                                <div class="space-y-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Payment Method</h3>
                                    <div class="space-y-4">
                                        <label class="flex items-center p-6 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-100 transition-all duration-200">
                                            <input type="radio" name="payment_method" value="credit_card" class="h-5 w-5 text-primary focus:ring-primary border-gray-300" checked>
                                            <div class="ml-4">
                                                <span class="block text-base font-medium text-gray-900">Credit Card</span>
                                                <span class="block text-sm text-gray-500 mt-1">Pay with your credit card</span>
                                            </div>
                                            <div class="ml-auto">
                                                <img src="{{ asset('images/visa-mastercard.png') }}" alt="Visa/Mastercard" class="h-8">
                                            </div>
                                        </label>

                                        <label class="flex items-center p-6 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-100 transition-all duration-200">
                                            <input type="radio" name="payment_method" value="gcash" class="h-5 w-5 text-primary focus:ring-primary border-gray-300">
                                            <div class="ml-4">
                                                <span class="block text-base font-medium text-gray-900">GCash</span>
                                                <span class="block text-sm text-gray-500 mt-1">Pay using GCash</span>
                                            </div>
                                            <div class="ml-auto">
                                                <img src="{{ asset('images/gcash.png') }}" alt="GCash" class="h-8">
                                            </div>
                                        </label>

                                        <label class="flex items-center p-6 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-100 transition-all duration-200">
                                            <input type="radio" name="payment_method" value="paypal" class="h-5 w-5 text-primary focus:ring-primary border-gray-300">
                                            <div class="ml-4">
                                                <span class="block text-base font-medium text-gray-900">PayPal</span>
                                                <span class="block text-sm text-gray-500 mt-1">Pay using PayPal</span>
                                            </div>
                                            <div class="ml-auto">
                                                <img src="{{ asset('images/paypal.png') }}" alt="PayPal" class="h-8">
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Credit Card Fields (shown/hidden based on payment method) -->
                                <div id="credit-card-fields" class="space-y-6">
                                    <div class="space-y-2">
                                        <label for="card_number" class="block text-sm font-medium text-gray-700">
                                            Card Number <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="card_number" id="card_number"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200"
                                            placeholder="1234 5678 9012 3456"
                                            aria-describedby="card_number_error">
                                        <div id="card_number_error" class="text-red-500 text-sm mt-1 hidden"></div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label for="expiry_date" class="block text-sm font-medium text-gray-700">
                                                Expiry Date <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="expiry_date" id="expiry_date"
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200"
                                                placeholder="MM/YY"
                                                aria-describedby="expiry_date_error">
                                            <div id="expiry_date_error" class="text-red-500 text-sm mt-1 hidden"></div>
                                        </div>

                                        <div class="space-y-2">
                                            <label for="cvv" class="block text-sm font-medium text-gray-700">
                                                CVV <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="cvv" id="cvv"
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200"
                                                placeholder="123"
                                                aria-describedby="cvv_error">
                                            <div id="cvv_error" class="text-red-500 text-sm mt-1 hidden"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex gap-4 pt-6">
                                    <a href="{{ route('customer.checkout.shipping') }}" 
                                        class="flex-1 bg-gray-100 text-gray-700 py-4 px-6 rounded-xl font-semibold text-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 text-center">
                                        Back to Shipping
                                    </a>
                                    <button type="submit" 
                                        class="flex-1 bg-indigo-600 text-white py-4 px-6 rounded-xl font-semibold text-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                                        id="submit-button">
                                        <span class="flex items-center justify-center">
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" id="loading-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Continue to Review
                                        </span>
                                    </button>
                                </div>
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
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const loadingSpinner = document.getElementById('loading-spinner');
    const creditCardFields = document.getElementById('credit-card-fields');
    const paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');

    // Show/hide credit card fields based on payment method
    function toggleCreditCardFields() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
        creditCardFields.style.display = selectedMethod === 'credit_card' ? 'block' : 'none';
    }

    // Initial toggle
    toggleCreditCardFields();

    // Listen for payment method changes
    paymentMethodInputs.forEach(input => {
        input.addEventListener('change', toggleCreditCardFields);
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        submitButton.disabled = true;
        loadingSpinner.classList.remove('hidden');
        
        // Basic form validation
        let isValid = true;
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
        
        if (selectedMethod === 'credit_card') {
            const requiredFields = ['card_number', 'expiry_date', 'cvv'];
            
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

            // Card number validation (basic)
            const cardNumber = document.getElementById('card_number').value.replace(/\s/g, '');
            if (!/^\d{16}$/.test(cardNumber)) {
                isValid = false;
                document.getElementById('card_number_error').textContent = 'Please enter a valid 16-digit card number';
                document.getElementById('card_number_error').classList.remove('hidden');
                document.getElementById('card_number').classList.add('border-red-500');
            }

            // Expiry date validation
            const expiryDate = document.getElementById('expiry_date').value;
            if (!/^\d{2}\/\d{2}$/.test(expiryDate)) {
                isValid = false;
                document.getElementById('expiry_date_error').textContent = 'Please enter a valid expiry date (MM/YY)';
                document.getElementById('expiry_date_error').classList.remove('hidden');
                document.getElementById('expiry_date').classList.add('border-red-500');
            }

            // CVV validation
            const cvv = document.getElementById('cvv').value;
            if (!/^\d{3,4}$/.test(cvv)) {
                isValid = false;
                document.getElementById('cvv_error').textContent = 'Please enter a valid CVV';
                document.getElementById('cvv_error').classList.remove('hidden');
                document.getElementById('cvv').classList.add('border-red-500');
            }
        }

        if (isValid) {
            form.submit();
        } else {
            // Reset loading state if validation fails
            submitButton.disabled = false;
            loadingSpinner.classList.add('hidden');
        }
    });

    // Format card number with spaces
    document.getElementById('card_number').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '');
        if (value.length > 16) value = value.slice(0, 16);
        e.target.value = value.replace(/(\d{4})/g, '$1 ').trim();
    });

    // Format expiry date
    document.getElementById('expiry_date').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 4) value = value.slice(0, 4);
        if (value.length > 2) {
            value = value.slice(0, 2) + '/' + value.slice(2);
        }
        e.target.value = value;
    });

    // Format CVV
    document.getElementById('cvv').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 4) value = value.slice(0, 4);
        e.target.value = value;
    });
});
</script>
@endpush
@endsection 