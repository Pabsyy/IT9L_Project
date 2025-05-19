<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>UnderTheHood</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
    <link href="{{ asset('css/tailwind-custom.css') }}" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com/3.4.5?plugins=forms@0.5.7,typography@0.5.13,aspect-ratio@0.4.2,container-queries@0.1.1"></script>
    <script src="{{ asset('js/tailwind-config.min.js') }}" data-color="#000000" data-border-radius="small"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
    @keyframes pulse-border {
        0% { box-shadow: 0 0 0 0 rgba(var(--custom-rgb), 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(var(--custom-rgb), 0); }
        100% { box-shadow: 0 0 0 0 rgba(var(--custom-rgb), 0); }
    }

    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }

    @keyframes shine {
        0% { transform: translateX(-100%) rotate(45deg); }
        100% { transform: translateX(200%) rotate(45deg); }
    }

    .bg-custom {
        --custom-rgb: 79, 70, 229;
        background-color: rgb(var(--custom-rgb));
    }

    .shine-effect {
        position: relative;
        overflow: hidden;
    }

    .shine-effect::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            45deg,
            transparent 45%,
            rgba(255, 255, 255, 0.13) 50%,
            transparent 55%
        );
        animation: shine 3s infinite;
    }

    .pulse-effect {
        animation: pulse-border 2s infinite;
    }

    .hero-gradient {
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(0, 0, 0, 0.95) 100%);
    }

    .card-hover {
        transition: all 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .floating-animation {
        animation: float 6s ease-in-out infinite;
    }
    </style>
</head>
<body class="bg-gray-50">
    @include('customer.layouts.header')

    <main class="mt-16">
        <section class="relative bg-gray-900 h-[650px] overflow-hidden">
            <div class="absolute inset-0">
                <img src="{{ asset('images/Customerpanel/background.webp') }}" class="w-full h-full object-cover object-center scale-105" alt="Hero Background"/>
                <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-black/40"></div>
            </div>
            <div class="relative max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
                <div class="max-w-2xl">
                    <h1 class="text-5xl font-bold tracking-tight text-white sm:text-6xl md:text-7xl drop-shadow-lg">
                        Welcome to <span class="text-custom">UnderTheHood</span> Supply
                    </h1>
                    <p class="mt-8 text-xl text-white max-w-xl leading-relaxed drop-shadow-lg font-medium">
                        Discover our extensive collection of high-quality automotive parts and accessories. Engineered for excellence, designed for your vehicle.
                    </p>
                    <div class="mt-12 flex space-x-6">
                        <button onclick="document.getElementById('products').scrollIntoView({ behavior: 'smooth' });" 
                            class="group relative overflow-hidden bg-custom text-white px-12 py-5 !rounded-xl font-semibold text-lg transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl shine-effect pulse-effect focus:outline-none focus:ring-4 focus:ring-custom/50">
                            <span class="relative z-10 flex items-center">
                                Shop Now
                                <span class="ml-2 group-hover:translate-x-1 transition-transform duration-200">→</span>
                            </span>
                        </button>
                        <a href="{{ route('customer.products.index') }}" 
                            class="bg-white/10 backdrop-blur-sm text-white px-8 py-5 !rounded-xl font-medium inline-block transition-all duration-300 hover:bg-white/20 border border-white/20">
                            Find Parts
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section id="products" class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <h2 class="text-4xl font-bold text-gray-900 mb-12 text-center">Popular Categories</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
                @foreach($categories as $category)
                <a href="{{ route('customer.products.category', ['category' => $category->slug]) }}" class="group block card-hover bg-white rounded-2xl p-6">
                    <div class="aspect-w-1 aspect-h-1 rounded-xl bg-gray-100 overflow-hidden mb-4">
                        <img src="{{ asset('images/Customerpanel/' . Str::slug($category->name) . '.webp') }}" 
                             class="object-center object-cover group-hover:scale-110 transition-transform duration-500" 
                             alt="{{ $category->name }}"
                             onerror="this.src='{{ asset('images/Customerpanel/default-category.webp') }}'" />
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-custom transition-colors">{{ $category->name }}</h3>
                </a>
                @endforeach
            </div>
        </section>

        <section class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-24 bg-white rounded-3xl shadow-sm my-8">
            <div class="flex justify-between items-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900">Featured Products</h2>
                <a href="#" class="text-custom font-semibold hover:text-custom/80 transition-colors flex items-center">
                    View All 
                    <span class="ml-2">→</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($products->take(4) as $product)
                <div class="group relative bg-white rounded-2xl p-4 card-hover border border-gray-100">
                    <a href="{{ route('customer.products.show', $product->id) }}" class="block">
                        <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-xl bg-gray-200 lg:aspect-none group-hover:opacity-90 lg:h-80">
                            <img src="{{ asset('storage/images/' . $product->image_url) }}" alt="{{ $product->name }}"
                                class="h-full w-full object-cover object-center lg:h-full lg:w-full transition-transform duration-500 group-hover:scale-110">
                        </div>
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
                            <p class="mt-2 text-2xl font-bold text-custom">₱{{ number_format($product->price, 2) }}</p>
                        </div>
                    </a>
                    <form action="{{ route('customer.cart.add') }}" method="POST" class="mt-4 add-to-cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="button" onclick="addToCart({{ $product->id }}, this)" 
                                class="w-full bg-gray-900 text-white py-3 rounded-xl hover:bg-gray-800 transition-colors flex items-center justify-center space-x-2">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Add to Cart</span>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        </section>

        <section id="featured-brands" class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <h2 class="text-4xl font-bold text-gray-900 mb-12 text-center">Featured Brands</h2>
            <div class="overflow-hidden relative">
                <div class="flex gap-8 animate-slide whitespace-nowrap">
                    <div class="bg-white p-8 rounded-2xl shadow-sm flex items-center justify-center hover:shadow-lg transition-all duration-300 min-w-[220px] card-hover">
                        <img src="{{ asset('images/Customerpanel/Enkei-logo.png') }}" alt="Wheel Rim" class="h-32 object-contain grayscale hover:grayscale-0 transition-all duration-500"/>
                    </div>
                    <div class="bg-white p-8 rounded-2xl shadow-sm flex items-center justify-center hover:shadow-lg transition-all duration-300 min-w-[220px] card-hover">
                        <img src="{{ asset('images/Customerpanel/mercedez.webp') }}" alt="Mercedez Benz" class="h-32 object-contain grayscale hover:grayscale-0 transition-all duration-500"/>
                    </div>
                    <div class="bg-white p-8 rounded-2xl shadow-sm flex items-center justify-center hover:shadow-lg transition-all duration-300 min-w-[220px] card-hover">
                        <img src="{{ asset('images/Customerpanel/toyoya.webp') }}" alt="Toyota" class="h-32 object-contain grayscale hover:grayscale-0 transition-all duration-500"/>
                    </div>
                    <div class="bg-white p-8 rounded-2xl shadow-sm flex items-center justify-center hover:shadow-lg transition-all duration-300 min-w-[220px] card-hover">
                        <img src="{{ asset('images/Customerpanel/bilstein.webp') }}" alt="Bilstein" class="h-32 object-contain grayscale hover:grayscale-0 transition-all duration-500"/>
                    </div>
                    <div class="bg-white p-8 rounded-2xl shadow-sm flex items-center justify-center hover:shadow-lg transition-all duration-300 min-w-[220px] card-hover">
                        <img src="{{ asset('images/Customerpanel/ZF.webp') }}" alt="Zf" class="h-32 object-contain grayscale hover:grayscale-0 transition-all duration-500"/>
                    </div>
                    <div class="bg-white p-8 rounded-2xl shadow-sm flex items-center justify-center hover:shadow-lg transition-all duration-300 min-w-[220px] card-hover">
                        <img src="{{ asset('images/Customerpanel/brembo.webp') }}" alt="Brembo" class="h-32 object-contain grayscale hover:grayscale-0 transition-all duration-500"/>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-white py-16">
            <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <h2 class="text-3xl font-semibold text-gray-900">Our Locations</h2>
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-xl font-medium text-gray-900">Main Store</h3>
                                <p class="text-gray-600">123 Auto Parts Street<br/>Los Angeles, CA 90001<br/>Phone: (555) 123-4567</p>
                            </div>
                            <div>
                                <h3 class="text-xl font-medium text-gray-900">Service Center</h3>
                                <p class="text-gray-600">456 Mechanic Avenue<br/>Los Angeles, CA 90002<br/>Phone: (555) 987-6543</p>
                            </div>
                        </div>
                    </div>
                    <div class="h-96 bg-gray-200 rounded-lg">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d423286.27405770525!2d-118.69192113701541!3d34.02016130653294!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80c2c75ddc27da13%3A0xe22fdf6f254608f4!2sLos%20Angeles%2C%20CA!5e0!3m2!1sen!2sus!4v1656541745051!5m2!1sen!2sus" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-gray-100 py-16">
            <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="text-center">
                        <i class="fas fa-truck text-4xl text-custom mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900">Free Shipping</h3>
                        <p class="mt-2 text-sm text-gray-500">On orders over $99</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-shield-alt text-4xl text-custom mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900">Secure Payment</h3>
                        <p class="mt-2 text-sm text-gray-500">100% secure payment</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-undo text-4xl text-custom mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900">Easy Returns</h3>
                        <p class="mt-2 text-sm text-gray-500">30 day return policy</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-headset text-4xl text-custom mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900">24/7 Support</h3>
                        <p class="mt-2 text-sm text-gray-500">Dedicated support</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('customer.layouts.footer')
</body>
</html> 