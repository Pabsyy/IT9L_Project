<header class="bg-white shadow-sm">
    <div class="max-w-8xl mx-auto">
        <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('welcome') }}" class="flex items-center" ondblclick="window.location.href='{{ route('admin.login') }}'">
                    <img class="h-8 w-auto" src="{{ asset('images/Customerpanel/logo.webp') }}" alt="Under The Hood Supply">
                </a>
            </div>

            <!-- Navigation -->
            <nav class="hidden md:flex space-x-8">
                <a href="{{ route('welcome') }}" class="text-gray-600 hover:text-gray-900">Home</a>
                <a href="{{ route('customer.products.index') }}" class="text-gray-600 hover:text-gray-900">Shop</a>
                <a href="#" class="text-gray-600 hover:text-gray-900">About</a>
                <a href="#" class="text-gray-600 hover:text-gray-900">Contact</a>
            </nav>

            <!-- Right side buttons -->
            <div class="flex items-center space-x-4">
                <!-- Search -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-search"></i>
                    </button>
                    <div x-show="open" 
                         @click.away="open = false"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg p-4"
                         style="display: none;">
                        <form action="{{ route('customer.products.search') }}" method="GET" class="w-full max-w-lg relative group">
                            <div class="flex items-center border rounded-lg overflow-hidden">
                                <input type="text" 
                                       name="search" 
                                       placeholder="Search products..." 
                                       class="flex-1 px-4 py-2 focus:outline-none">
                                <button type="submit" class="bg-gray-100 px-4 py-2 text-gray-600 hover:text-gray-900">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Wishlist -->
                <a href="{{ route('customer.wishlist.index') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="far fa-heart"></i>
                </a>

                <!-- Cart -->
                <a href="{{ route('customer.cart.view') }}" class="text-gray-600 hover:text-gray-900 relative">
                    <i class="fas fa-shopping-cart"></i>
                    @if(Cart::count() > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center cart-count">
                            {{ Cart::count() }}
                        </span>
                    @endif
                </a>

                <!-- User Menu -->
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2">
                            <img src="{{ auth()->user()->profile_photo_url }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="h-8 w-8 rounded-full">
                            <span class="text-gray-700">{{ auth()->user()->name }}</span>
                        </button>
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2"
                             style="display: none;">
                            <a href="{{ route('account.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                Dashboard
                            </a>
                            <a href="{{ route('account.settings') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                Settings
                            </a>
                            <form method="POST" action="{{ route('customer.logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Login</a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header> 