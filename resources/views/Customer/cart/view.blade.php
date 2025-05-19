<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Cart - UnderTheHood</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
    <link href="{{ asset('css/tailwind-custom.css') }}" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com/3.4.5?plugins=forms@0.5.7,typography@0.5.13,aspect-ratio@0.4.2,container-queries@0.1.1"></script>
    <script src="{{ asset('js/tailwind-config.min.js') }}" data-color="#000000" data-border-radius="small"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
    @include('customer.layouts.header')

    <main class="flex-grow container mx-auto px-4 py-12 lg:py-24">
        <div class="max-w-7xl mx-auto">
            <div class="lg:grid lg:grid-cols-12 lg:gap-8">
                <!-- Cart Items Section -->
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-8">
                                <h1 class="text-2xl font-bold">Shopping Cart</h1>
                                <span class="text-gray-500">{{ $cartItems->count() }} {{ Str::plural('item', $cartItems->count()) }}</span>
                            </div>

                            @if(isset($cartItems) && $cartItems->count() > 0)
                                <div class="divide-y divide-gray-200">
                                    @foreach($cartItems as $item)
                                        <div class="py-6 flex flex-col sm:flex-row sm:items-center gap-4">
                                            <!-- Product Image -->
                                            <div class="flex-shrink-0">
                                                <img src="{{ $item->image_url }}" 
                                                     alt="{{ $item->name }}" 
                                                     class="w-24 h-24 object-cover rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200"/>
                                            </div>
                                            
                                            <!-- Product Details -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                                                    <div>
                                                        <h3 class="text-lg font-medium text-gray-900 hover:text-blue-600 transition-colors">
                                                            <a href="{{ route('customer.products.show', $item->id) }}">{{ $item->name }}</a>
                                                        </h3>
                                                        <p class="mt-1 text-lg font-semibold text-gray-900">₱{{ number_format($item->price, 2) }}</p>
                                                        
                                                        @if($item->product)
                                                            @php
                                                                $stockStatus = $item->product->getStockStatus();
                                                            @endphp
                                                            <div class="mt-2">
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $stockStatus['class'] }} bg-opacity-10">
                                                                    <i class="{{ $stockStatus['icon'] }} mr-1"></i>
                                                                    {{ $stockStatus['status'] }}
                                                                </span>
                                                            </div>
                                                        @else
                                                            <div class="mt-2">
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-600">
                                                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                                                    Product not available
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Quantity Controls -->
                                                    <div class="flex items-center gap-4">
                                                        <div class="flex items-center">
                                                            <form action="{{ route('customer.cart.update', $item->rowId) }}" 
                                                                  method="POST" 
                                                                  class="flex items-center"
                                                                  x-data="{ quantity: {{ $item->qty }} }">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="button" 
                                                                        onclick="this.parentNode.querySelector('input[type=number]').stepDown(); this.parentNode.querySelector('input[type=number]').dispatchEvent(new Event('change'))"
                                                                        class="w-8 h-8 rounded-l border border-gray-300 flex items-center justify-center hover:bg-gray-100 {{ (!$item->product || !$item->product->isInStock()) ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}"
                                                                        {{ !$item->product || !$item->product->isInStock() ? 'disabled' : '' }}>
                                                                    <i class="fas fa-minus text-gray-600"></i>
                                                                </button>
                                                                <input type="number" 
                                                                       name="quantity" 
                                                                       x-model="quantity"
                                                                       value="{{ $item->qty }}" 
                                                                       min="1" 
                                                                       max="{{ $item->product ? $item->product->stock : 1 }}" 
                                                                       class="w-14 h-8 border-y border-gray-300 text-center focus:ring-blue-500 focus:border-blue-500"
                                                                       {{ !$item->product || !$item->product->isInStock() ? 'disabled' : '' }}>
                                                                <button type="button"
                                                                        onclick="this.parentNode.querySelector('input[type=number]').stepUp(); this.parentNode.querySelector('input[type=number]').dispatchEvent(new Event('change'))"
                                                                        class="w-8 h-8 rounded-r border border-gray-300 flex items-center justify-center hover:bg-gray-100 {{ (!$item->product || !$item->product->isInStock()) ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}"
                                                                        {{ !$item->product || !$item->product->isInStock() ? 'disabled' : '' }}>
                                                                    <i class="fas fa-plus text-gray-600"></i>
                                                                </button>
                                                                <button type="submit" 
                                                                        class="ml-2 text-sm text-blue-600 hover:text-blue-800 font-medium"
                                                                        x-show="quantity != {{ $item->qty }}"
                                                                        {{ !$item->product || !$item->product->isInStock() ? 'disabled' : '' }}>
                                                                    Update
                                                                </button>
                                                            </form>
                                                        </div>
                                                        <form action="{{ route('customer.cart.remove', $item->rowId) }}" 
                                                              method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="text-sm text-red-600 hover:text-red-800 font-medium flex items-center gap-1">
                                                                <i class="fas fa-trash-alt"></i>
                                                                <span>Remove</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                                
                                                @if($item->product && !$item->product->hasStockFor($item->qty))
                                                    <div class="mt-2 text-sm text-red-600 bg-red-50 px-3 py-2 rounded-md">
                                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                                        Requested quantity exceeds available stock ({{ $item->product->stock }} available)
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="mb-4">
                                        <i class="fas fa-shopping-cart text-4xl text-gray-400"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Your cart is empty</h3>
                                    <p class="text-gray-500 mb-6">Looks like you haven't added anything to your cart yet.</p>
                                    <a href="{{ route('customer.products.index') }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Start Shopping
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Continue Shopping Link -->
                    <div class="mt-6">
                        <a href="{{ route('customer.products.index') }}" 
                           class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
                            <i class="fas fa-arrow-left"></i>
                            Continue Shopping
                        </a>
                    </div>
                </div>

                <!-- Order Summary Section -->
                @if(isset($cartItems) && $cartItems->count() > 0)
                    <div class="lg:col-span-4 mt-8 lg:mt-0">
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden sticky top-8">
                            <div class="p-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
                                
                                <div class="space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Subtotal</span>
                                        <span class="text-gray-900 font-medium">₱{{ number_format($total, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Shipping</span>
                                        <span class="text-gray-900">Calculated at checkout</span>
                                    </div>
                                    <div class="border-t border-gray-200 pt-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-base font-semibold text-gray-900">Total</span>
                                            <span class="text-2xl font-bold text-gray-900">₱{{ number_format($total, 2) }}</span>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $hasStockIssues = $cartItems->contains(function($item) {
                                        return !$item->product || !$item->product->hasStockFor($item->qty);
                                    });
                                @endphp

                                <button onclick="window.location.href='{{ route('customer.checkout.shipping') }}'"
                                        class="mt-6 w-full flex items-center justify-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white {{ $hasStockIssues ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700' }} transition-colors duration-200"
                                        {{ $hasStockIssues ? 'disabled' : '' }}>
                                    @if($hasStockIssues)
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        Please Resolve Stock Issues
                                    @else
                                        <i class="fas fa-lock mr-2"></i>
                                        Proceed to Checkout
                                    @endif
                                </button>
                                
                                @if($hasStockIssues)
                                    <div class="mt-3 p-3 bg-red-50 rounded-md">
                                        <p class="text-sm text-red-600">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            Some items in your cart have stock issues. Please update or remove them before proceeding.
                                        </p>
                                    </div>
                                @endif

                                <!-- Secure Checkout Message -->
                                <div class="mt-4 flex items-center justify-center text-sm text-gray-500">
                                    <i class="fas fa-shield-alt mr-2"></i>
                                    Secure Checkout
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>

    @include('customer.layouts.footer')
</body>
</html>
