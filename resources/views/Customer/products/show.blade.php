@extends('customer.layouts.app')

@section('head')
    <!-- Add CSRF Token meta tag in the head section -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <!-- Hero Section -->
    <div class="bg-gray-100">
        <div class="container mx-auto px-4 py-8">
            <!-- Breadcrumb -->
            <nav class="mb-6 text-sm">
                <ol class="list-none p-0 flex items-center space-x-2">
                    <li>
                        <a href="{{ route('welcome') }}" class="text-gray-600 hover:text-primary">Home</a>
                    </li>
                    <li class="text-gray-400">/</li>
                    <li>
                        <a href="{{ route('customer.products.index') }}" class="text-gray-600 hover:text-primary">Shop</a>
                    </li>
                    <li class="text-gray-400">/</li>
                    @if($product->category)
                    <li>
                        <a href="{{ route('customer.products.category', $product->category->id) }}" class="text-gray-600 hover:text-primary">
                            {{ $product->category->name }}
                        </a>
                    </li>
                    <li class="text-gray-400">/</li>
                    @endif
                    <li class="text-gray-900">{{ $product->name }}</li>
                </ol>
            </nav>

            <!-- Main Content -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col lg:flex-row gap-8">
                        <!-- Product Images -->
                        <div class="lg:w-1/2">
                            <div class="bg-white rounded-xl overflow-hidden mb-4 border border-gray-200">
                                <img src="{{ $product->getImageUrlAttribute() }}"
                                     alt="{{ $product->name }}"
                                     id="mainProductImage"
                                     class="w-full h-[400px] object-contain object-center p-4"/>
                            </div>
                            <div class="grid grid-cols-4 gap-4">
                                <!-- Main image thumbnail -->
                                <div class="bg-white rounded-xl overflow-hidden cursor-pointer hover:ring-2 hover:ring-indigo-600 transition duration-150 border border-gray-200"
                                     onclick="updateMainImage('{{ $product->getImageUrlAttribute() }}')">
                                    <img src="{{ $product->getImageUrlAttribute() }}"
                                         alt="{{ $product->name }} - Main"
                                         class="w-full h-24 object-contain object-center p-2"/>
                                </div>
                                
                                <!-- Additional images -->
                                @if($product->image_1)
                                <div class="bg-white rounded-xl overflow-hidden cursor-pointer hover:ring-2 hover:ring-indigo-600 transition duration-150 border border-gray-200"
                                     onclick="updateMainImage('{{ asset('storage/images/' . $product->image_1) }}')">
                                    <img src="{{ asset('storage/images/' . $product->image_1) }}"
                                         alt="{{ $product->name }} - Image 1"
                                         class="w-full h-24 object-contain object-center p-2"/>
                                </div>
                                @endif
                                
                                @if($product->image_2)
                                <div class="bg-white rounded-xl overflow-hidden cursor-pointer hover:ring-2 hover:ring-indigo-600 transition duration-150 border border-gray-200"
                                     onclick="updateMainImage('{{ asset('storage/images/' . $product->image_2) }}')">
                                    <img src="{{ asset('storage/images/' . $product->image_2) }}"
                                         alt="{{ $product->name }} - Image 2"
                                         class="w-full h-24 object-contain object-center p-2"/>
                                </div>
                                @endif
                                
                                @if($product->image_3)
                                <div class="bg-white rounded-xl overflow-hidden cursor-pointer hover:ring-2 hover:ring-indigo-600 transition duration-150 border border-gray-200"
                                     onclick="updateMainImage('{{ asset('storage/images/' . $product->image_3) }}')">
                                    <img src="{{ asset('storage/images/' . $product->image_3) }}"
                                         alt="{{ $product->name }} - Image 3"
                                         class="w-full h-24 object-contain object-center p-2"/>
                                </div>
                                @endif
                                
                                @if($product->image_4)
                                <div class="bg-white rounded-xl overflow-hidden cursor-pointer hover:ring-2 hover:ring-indigo-600 transition duration-150 border border-gray-200"
                                     onclick="updateMainImage('{{ asset('storage/images/' . $product->image_4) }}')">
                                    <img src="{{ asset('storage/images/' . $product->image_4) }}"
                                         alt="{{ $product->name }} - Image 4"
                                         class="w-full h-24 object-contain object-center p-2"/>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Product Details -->
                        <div class="lg:w-1/2">
                            <div class="flex flex-col h-full">
                                <!-- Basic Info -->
                                <div class="mb-6">
                                    @if($product->brand)
                                    <div class="text-sm text-gray-500 mb-2">{{ $product->brand->name }}</div>
                                    @endif
                                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
                                    
                                    <!-- Rating -->
                                    <div class="flex items-center mb-4">
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($product->average_rating))
                                                    <i class="fas fa-star"></i>
                                                @elseif($i - 0.5 <= $product->average_rating)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-sm text-gray-500 ml-2">({{ $product->review_count }} reviews)</span>
                                    </div>

                                    <!-- Price -->
                                    <div class="flex items-center mb-4">
                                        @if($product->discount_percentage > 0)
                                            <span class="text-3xl font-bold text-red-600">₱{{ number_format($product->discounted_price, 2) }}</span>
                                            <span class="text-lg text-gray-500 line-through ml-3">₱{{ number_format($product->original_price, 2) }}</span>
                                            <span class="ml-3 bg-red-100 text-red-600 px-2 py-1 rounded-full text-sm font-medium">
                                                Save {{ $product->discount_percentage }}%
                                            </span>
                                        @else
                                            <span class="text-3xl font-bold text-gray-900">₱{{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>

                                    <!-- Stock Status -->
                                    <div class="flex items-center mb-6">
                                        @if($product->isInStock())
                                            <div class="flex items-center text-green-600">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                <span>In Stock</span>
                                            </div>
                                            <span class="text-gray-500 ml-2">({{ $product->stock }} units available)</span>
                                        @else
                                            <div class="flex items-center text-red-600">
                                                <i class="fas fa-times-circle mr-2"></i>
                                                <span>Out of Stock</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Quick Actions -->
                                    <div class="flex items-center gap-4 mb-6">
                                        @auth
                                            <button onclick="toggleWishlist({{ $product->id }})" 
                                                    class="flex items-center justify-center w-12 h-12 rounded-full border border-gray-300 hover:border-primary hover:bg-primary/5 transition-colors">
                                                <i class="far fa-heart text-lg {{ $product->isInWishlist(auth()->id()) ? 'fas text-red-500' : '' }}"></i>
                                            </button>
                                        @endauth
                                        <button onclick="shareProduct()" 
                                                class="flex items-center justify-center w-12 h-12 rounded-full border border-gray-300 hover:border-primary hover:bg-primary/5 transition-colors">
                                            <i class="fas fa-share-alt text-lg"></i>
                                        </button>
                                    </div>

                                    <!-- Description -->
                                    <div class="prose prose-sm text-gray-600 mb-6">
                                        {!! nl2br(e($product->description)) !!}
                                    </div>

                                    <!-- Add to Cart Section -->
                                    <div class="mt-auto relative">
                                        <div class="flex items-center gap-4">
                                            @if($product->isInStock())
                                                <div class="flex items-center border rounded-xl bg-gray-50">
                                                    <button type="button" 
                                                            class="px-4 py-2 text-gray-600 hover:text-primary" 
                                                            onclick="decrementQuantity(event, this)">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" 
                                                           id="quantity" 
                                                           name="quantity" 
                                                           value="1" 
                                                           min="1" 
                                                           max="{{ $product->stock }}"
                                                           class="w-16 text-center border-0 bg-transparent focus:ring-0"
                                                           oninput="validateQuantity(this)">
                                                    <button type="button" 
                                                            class="px-4 py-2 text-gray-600 hover:text-primary" 
                                                            onclick="incrementQuantity(event, this)">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <button type="button"
                                                        onclick="addToCart({{ $product->id }})" 
                                                        class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700 transition duration-150 flex items-center justify-center">
                                                    <i class="fas fa-shopping-cart mr-2"></i>
                                                    Add to Cart
                                                </button>
                                            @else
                                                <button disabled 
                                                        class="w-full bg-gray-300 text-gray-500 px-6 py-3 rounded-xl cursor-not-allowed">
                                                    Out of Stock
                                                </button>
                                            @endif
                                        </div>
                                        <!-- Notification container -->
                                        <div id="notification-container" class="absolute left-1/2 transform -translate-x-1/2 -bottom-16 w-full"></div>
                                    </div>

                                    <!-- Additional Information -->
                                    <div class="mt-8 pt-8 border-t border-gray-200">
                                        <h2 class="text-lg font-medium text-gray-900 mb-4">Product Information</h2>
                                        <div class="grid grid-cols-2 gap-4">
                                            @if($product->category)
                                                <div class="text-sm text-gray-600">Category</div>
                                                <div class="text-sm font-medium">{{ $product->category->name }}</div>
                                            @endif
                                            
                                            @if($product->brand)
                                                <div class="text-sm text-gray-600">Brand</div>
                                                <div class="text-sm font-medium">{{ $product->brand->name }}</div>
                                            @endif
                                            
                                            @if($product->sku)
                                                <div class="text-sm text-gray-600">SKU</div>
                                                <div class="text-sm font-medium">{{ $product->sku }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Products</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($product->category->products->where('id', '!=', $product->id)->take(4) as $relatedProduct)
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden group">
                            <a href="{{ route('customer.products.show', $relatedProduct->id) }}" class="block">
                                <div class="aspect-w-1 aspect-h-1 w-full">
                                    <img src="{{ $relatedProduct->getImageUrlAttribute() }}" 
                                         alt="{{ $relatedProduct->name }}" 
                                         class="w-full h-48 object-contain object-center p-4">
                                </div>
                                <div class="p-4">
                                    <h3 class="text-sm font-medium text-gray-900 group-hover:text-primary transition-colors">
                                        {{ $relatedProduct->name }}
                                    </h3>
                                    <p class="mt-1 text-lg font-bold text-gray-900">
                                        ₱{{ number_format($relatedProduct->price, 2) }}
                                    </p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Share Modal -->
    <div x-data="{ open: false }" 
         x-show="open" 
         @keydown.escape.window="open = false"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                Share This Product
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <button onclick="shareOnFacebook()" class="flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    <i class="fab fa-facebook-f mr-2"></i>
                                    Facebook
                                </button>
                                <button onclick="shareOnTwitter()" class="flex items-center justify-center px-4 py-2 bg-blue-400 text-white rounded-lg hover:bg-blue-500">
                                    <i class="fab fa-twitter mr-2"></i>
                                    Twitter
                                </button>
                                <button onclick="shareOnWhatsApp()" class="flex items-center justify-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                                    <i class="fab fa-whatsapp mr-2"></i>
                                    WhatsApp
                                </button>
                                <button onclick="copyLink()" class="flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                                    <i class="fas fa-link mr-2"></i>
                                    Copy Link
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" 
                            @click="open = false"
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Define the cart routes for JavaScript
            window.cartRoutes = {
                add: '{{ route("customer.cart.add") }}'
            };
        });

        function toggleWishlist(productId) {
            @auth
                fetch('{{ route("customer.wishlist.toggle") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const wishlistIcon = document.querySelector('button[onclick*="toggleWishlist"] i');
                        if (data.in_wishlist) {
                            wishlistIcon.classList.remove('far');
                            wishlistIcon.classList.add('fas', 'text-red-500');
                        } else {
                            wishlistIcon.classList.remove('fas', 'text-red-500');
                            wishlistIcon.classList.add('far');
                        }
                        showNotification(data.in_wishlist ? 'Added to wishlist' : 'Removed from wishlist');
                    }
                });
            @else
                window.location.href = '{{ route("customer.login") }}';
            @endauth
        }

        function shareProduct() {
            const modal = document.querySelector('[x-data="{ open: false }"]').__x;
            modal.$data.open = true;
        }

        function shareOnFacebook() {
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}`, '_blank');
        }

        function shareOnTwitter() {
            window.open(`https://twitter.com/intent/tweet?url=${encodeURIComponent(window.location.href)}&text=${encodeURIComponent('{{ $product->name }}')}`, '_blank');
        }

        function shareOnWhatsApp() {
            window.open(`https://wa.me/?text=${encodeURIComponent('{{ $product->name }} - ' + window.location.href)}`, '_blank');
        }

        function copyLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                showNotification('Link copied to clipboard');
            });
        }

        function updateMainImage(imageUrl) {
            document.getElementById('mainProductImage').src = imageUrl;
        }

        function refreshHeader() {
            // Reload the entire page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 100);
        }

        function validateQuantity(input) {
            let value = parseInt(input.value);
            const max = parseInt(input.getAttribute('max'));
            const min = parseInt(input.getAttribute('min'));
            
            if (isNaN(value) || value < min) {
                input.value = min;
            } else if (value > max) {
                input.value = max;
            }
        }

        function incrementQuantity(event, button) {
            event.preventDefault();
            const input = button.parentElement.querySelector('input');
            const currentValue = parseInt(input.value);
            const max = parseInt(input.getAttribute('max'));
            
            if (currentValue < max) {
                input.value = currentValue + 1;
            }
        }

        function decrementQuantity(event, button) {
            event.preventDefault();
            const input = button.parentElement.querySelector('input');
            const currentValue = parseInt(input.value);
            const min = parseInt(input.getAttribute('min'));
            
            if (currentValue > min) {
                input.value = currentValue - 1;
            }
        }

        function addToCart(productId) {
            const quantity = parseInt(document.getElementById('quantity').value);
            const button = document.querySelector('button[onclick*="addToCart"]');
            const originalText = button.innerHTML;
            
            // Disable button and show loading state
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding...';
            button.disabled = true;

            fetch('{{ route("customer.cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    
                    // Update cart count if available
                    const cartCountElement = document.querySelector('.cart-count');
                    if (cartCountElement && data.cartCount !== undefined) {
                        cartCountElement.textContent = data.cartCount;
                    }

                    // Refresh the header
                    refreshHeader();
                } else {
                    throw new Error(data.message || 'Failed to add product to cart');
                }
            })
            .catch(error => {
                showNotification(error.message || 'An error occurred while adding to cart', 'error');
            })
            .finally(() => {
                // Restore button state
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }

        function showNotification(message, type = 'success') {
            const container = document.getElementById('notification-container');
            // Clear any existing notifications
            container.innerHTML = '';
            
            const notification = document.createElement('div');
            notification.className = `${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white px-4 py-3 rounded-lg shadow-lg flex items-center justify-center space-x-2 animate-fade-in`;
            
            const icon = document.createElement('i');
            icon.className = `fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}`;
            
            const text = document.createElement('span');
            text.textContent = message;
            
            notification.appendChild(icon);
            notification.appendChild(text);
            container.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.add('animate-fade-out');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }
    </script>
    <script src="{{ asset('js/product-interactions.js') }}"></script>
@endsection 