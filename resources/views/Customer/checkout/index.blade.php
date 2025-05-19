<!DOCTYPE html>
@php
    if (!Auth::check()) {
        return redirect()->route('welcome')
            ->with('openLoginModal', true)
            ->with('intended', url()->current());
    }
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#8B5CF6',
                        secondary: '#6D28D9'
                    },
                    borderRadius: {
                        'none': '0px',
                        'sm': '4px',
                        DEFAULT: '8px',
                        'md': '12px',
                        'lg': '16px',
                        'xl': '20px',
                        '2xl': '24px',
                        '3xl': '32px',
                        'full': '9999px',
                        'button': '8px'
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        :where([class^="ri-"])::before { content: "\f3c2"; }
        input:focus, select:focus {
            outline: none;
            border-color: #8B5CF6;
            box-shadow: 0 0 0 1px #8B5CF6;
        }
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .order-progress {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        .progress-step {
            display: flex;
            align-items: center;
        }
        .progress-step-number {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            background-color: #E5E7EB;
            color: #6B7280;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 0.5rem;
        }
        .progress-step.active .progress-step-number {
            background-color: #8B5CF6;
            color: white;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Progress Bar -->
        <div class="order-progress mb-8">
            <div class="progress-step active">
                <div class="progress-step-number">1</div>
                <span class="text-sm font-medium">Shipping</span>
            </div>
            <div class="h-px bg-gray-200 flex-1 mx-4"></div>
            <div class="progress-step">
                <div class="progress-step-number">2</div>
                <span class="text-sm font-medium">Payment</span>
            </div>
            <div class="h-px bg-gray-200 flex-1 mx-4"></div>
            <div class="progress-step">
                <div class="progress-step-number">3</div>
                <span class="text-sm font-medium">Confirmation</span>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-8">
            <!-- Left Column - Checkout Form -->
            <div class="flex-1 space-y-8">
                <!-- Account Section -->
                <div class="bg-white rounded p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Account</h2>
                    </div>
                    <p class="text-gray-600">{{ Auth::user()->email }}</p>
                </div>

                <!-- Delivery Section -->
                <div class="bg-white rounded p-6 shadow-sm">
                    <h2 class="text-xl font-semibold mb-6">Delivery Information</h2>
                    <form id="checkoutForm" action="{{ route('customer.checkout.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="space-y-4">
                            <!-- Delivery Options -->
                            <div class="flex flex-col space-y-3">
                                <label class="flex items-center p-4 border rounded cursor-pointer">
                                    <input type="radio" name="delivery_method" value="delivery" class="hidden peer" checked>
                                    <div class="w-5 h-5 flex items-center justify-center border rounded-full mr-3 relative peer-checked:border-primary">
                                        <div class="w-3 h-3 bg-primary rounded-full peer-checked:bg-primary"></div>
                                    </div>
                                    <span>Delivery to Address</span>
                                    <div class="ml-auto w-6 h-6 flex items-center justify-center text-gray-500">
                                        <i class="ri-truck-line"></i>
                                    </div>
                                </label>
                                <label class="flex items-center p-4 border rounded cursor-pointer">
                                    <input type="radio" name="delivery_method" value="pickup" class="hidden peer">
                                    <div class="w-5 h-5 flex items-center justify-center border rounded-full mr-3 peer-checked:border-primary">
                                        <div class="w-3 h-3 bg-white rounded-full peer-checked:bg-primary"></div>
                                    </div>
                                    <span>Pickup in Store</span>
                                    <div class="ml-auto w-6 h-6 flex items-center justify-center text-gray-500">
                                        <i class="ri-store-line"></i>
                                    </div>
                                </label>
                            </div>

                            <!-- Contact Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                    <input type="text" name="first_name" class="w-full p-3 border rounded" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                    <input type="text" name="last_name" class="w-full p-3 border rounded" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <input type="text" name="address" class="w-full p-3 border rounded" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <div class="relative">
                                    <input type="tel" name="phone" class="w-full p-3 border rounded pr-10" required>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                        <i class="ri-phone-line text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Section -->
                            <div class="mt-8">
                                <h2 class="text-xl font-semibold mb-4">Payment Method</h2>
                                <p class="text-gray-600 mb-6">All transactions are secure and encrypted.</p>
                                <div class="flex space-x-2 mb-6">
                                    <div class="w-8 h-6 flex items-center justify-center bg-violet-600 rounded">
                                        <i class="ri-visa-fill text-white"></i>
                                    </div>
                                    <div class="w-8 h-6 flex items-center justify-center bg-red-500 rounded">
                                        <i class="ri-mastercard-fill text-white"></i>
                                    </div>
                                    <div class="w-8 h-6 flex items-center justify-center bg-blue-400 rounded">
                                        <i class="ri-paypal-fill text-white"></i>
                                    </div>
                                    <div class="w-8 h-6 flex items-center justify-center bg-green-500 rounded">
                                        <span class="text-white text-xs font-bold">GCash</span>
                                    </div>
                                </div>
                                <button type="submit" class="w-full bg-primary text-white py-4 px-6 rounded-button font-medium hover:bg-violet-700 transition duration-200">
                                    Place Order
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="w-full md:w-96 bg-white rounded shadow-sm p-6 h-fit">
                <h2 class="text-xl font-semibold mb-6">Order Summary</h2>
                <div class="space-y-6">
                    @foreach($cartItems as $item)
                    <div class="flex items-start">
                        <div class="w-16 h-16 flex-shrink-0 bg-gray-100 rounded overflow-hidden mr-4">
                            <img src="{{ asset('images/Customerpanel/' . ($item->options['image'] ?? 'default.jpg')) }}" 
                                 alt="{{ $item->name }}" 
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="font-medium">{{ $item->name }}</h3>
                                    <p class="text-sm text-gray-500">Qty: {{ $item->qty }}</p>
                                </div>
                            </div>
                            <p class="font-medium mt-1">₱{{ number_format($item->qty * $item->price, 2) }}</p>
                        </div>
                    </div>
                    @endforeach

                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span>₱{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Shipping</span>
                            <span>₱{{ number_format($shipping, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Tax (12%)</span>
                            <span>₱{{ number_format($tax, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-medium pt-2 border-t">
                            <span>Total</span>
                            <div class="text-right">
                                <span class="text-sm text-gray-500 block">PHP</span>
                                <span>₱{{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 