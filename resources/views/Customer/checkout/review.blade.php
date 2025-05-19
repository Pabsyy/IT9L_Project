@extends('customer.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Error Messages -->
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded">
            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-600 text-white font-semibold shadow-lg ring-4 ring-indigo-200">
                                3
                            </div>
                            <span class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-sm font-medium text-indigo-600 whitespace-nowrap">Review</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Column - Order Review -->
            <div class="flex-1">
                <div class="space-y-8">
                    <!-- Shipping Information -->
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        <div class="p-8">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-2xl font-bold text-gray-900">Shipping Information</h2>
                                <a href="{{ route('customer.checkout.shipping') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                                    Edit
                                </a>
                            </div>
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Name</p>
                                        <p class="text-base font-medium text-gray-900">{{ $shippingInfo['first_name'] }} {{ $shippingInfo['last_name'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Phone</p>
                                        <p class="text-base font-medium text-gray-900">{{ $shippingInfo['phone'] }}</p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Address</p>
                                    <p class="text-base font-medium text-gray-900">
                                        {{ $shippingInfo['street_address'] }}<br>
                                        {{ $shippingInfo['city'] }}, {{ $shippingInfo['state'] }} {{ $shippingInfo['postal_code'] }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Delivery Method</p>
                                    <p class="text-base font-medium text-gray-900">
                                        {{ ucfirst($shippingInfo['delivery_method']) }}
                                        @if($shippingInfo['delivery_method'] === 'delivery')
                                            (₱150.00)
                                        @else
                                            (Free)
                                        @endif
                                    </p>
                                </div>
                                @if(isset($shippingInfo['delivery_instructions']) && $shippingInfo['delivery_instructions'])
                                    <div>
                                        <p class="text-sm text-gray-500">Delivery Instructions</p>
                                        <p class="text-base font-medium text-gray-900">{{ $shippingInfo['delivery_instructions'] }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        <div class="p-8">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-2xl font-bold text-gray-900">Payment Information</h2>
                                <a href="{{ route('customer.checkout.payment') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                                    Edit
                                </a>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500">Payment Method</p>
                                    <p class="text-base font-medium text-gray-900">
                                        @switch($paymentInfo['payment_method'])
                                            @case('credit_card')
                                                Credit Card (**** **** **** {{ substr($paymentInfo['card_number'], -4) }})
                                                @break
                                            @case('gcash')
                                                GCash
                                                @break
                                            @case('paypal')
                                                PayPal
                                                @break
                                        @endswitch
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <form action="{{ route('customer.checkout.process') }}" method="POST">
                        @csrf
                        <button type="submit" 
                            class="w-full bg-indigo-600 text-white py-4 px-6 rounded-xl font-semibold text-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                            id="submit-button">
                            <span class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" id="loading-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Place Order
                            </span>
                        </button>
                    </form>
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
    const form = document.querySelector('form');
    const submitButton = document.getElementById('submit-button');
    const loadingSpinner = document.getElementById('loading-spinner');

    form.addEventListener('submit', function(e) {
        // Show loading state
        submitButton.disabled = true;
        loadingSpinner.classList.remove('hidden');
        
        // Let the form submit naturally
        // No need to prevent default and submit again
    });
});
</script>
@endpush
@endsection 