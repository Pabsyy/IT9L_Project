@extends('Customer.layouts.app')

@section('head')
    <!-- Add CSRF Token meta tag in the head section -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Add scripts in head -->
    <script>
        // Initialize cart routes immediately
        window.cartRoutes = {
            add: '{{ route("customer.cart.add") }}'
        };

        // Function to remove a specific filter
        function removeFilter(type, value = null) {
            const url = new URL(window.location.href);
            const params = new URLSearchParams(url.search);

            if (value) {
                // For array parameters like category[] and brand[]
                const values = params.getAll(type + '[]');
                const index = values.indexOf(value);
                if (index > -1) {
                    values.splice(index, 1);
                    params.delete(type + '[]');
                    values.forEach(v => params.append(type + '[]', v));
                }
            } else {
                // For single parameters like search, price_min, price_max
                params.delete(type);
            }

            url.search = params.toString();
            window.location.href = url.toString();
        }

        // Function to clear all filters
        function clearAllFilters() {
            window.location.href = '{{ route("customer.products.index") }}';
        }

        // Function to clear category filters
        function clearCategoryFilters() {
            const url = new URL(window.location.href);
            const params = new URLSearchParams(url.search);
            params.delete('category[]');
            url.search = params.toString();
            window.location.href = url.toString();
        }

        // Function to clear brand filters
        function clearBrandFilters() {
            const url = new URL(window.location.href);
            const params = new URLSearchParams(url.search);
            params.delete('brand[]');
            url.search = params.toString();
            window.location.href = url.toString();
        }

        // Function to clear price filters
        function clearPriceFilters() {
            const url = new URL(window.location.href);
            const params = new URLSearchParams(url.search);
            params.delete('price_min');
            params.delete('price_max');
            url.search = params.toString();
            window.location.href = url.toString();
        }

        // Function to toggle filters on mobile
        function toggleFilters() {
            const filters = document.querySelector('.filters-sidebar');
            filters.classList.toggle('hidden');
        }
    </script>
    <script src="{{ asset('js/product-interactions.js') }}"></script>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Filters Sidebar -->
        <div class="w-full md:w-1/4">
            <div class="bg-white rounded-lg shadow p-6 md:sticky md:top-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Filters</h2>
                    <button type="button" 
                            onclick="toggleFilters()"
                            class="md:hidden text-gray-500 hover:text-gray-700">
                        <i class="fas fa-sliders-h"></i>
                    </button>
                </div>
                
                <form id="filterForm" action="{{ route('customer.products.index') }}" method="GET" class="space-y-6">
                    <!-- Categories -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-medium">Categories</h3>
                            <button type="button" 
                                    onclick="clearCategoryFilters()"
                                    class="text-sm text-primary hover:text-primary-dark">
                                Clear
                            </button>
                        </div>
                        <div class="space-y-2 max-h-48 overflow-y-auto custom-scrollbar">
                            @foreach($categories as $category)
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="category-{{ $category->id }}" 
                                       name="category[]" 
                                       value="{{ $category->slug }}"
                                       class="rounded border-gray-300 text-primary focus:ring-primary"
                                       {{ in_array($category->slug, request()->category ?? []) ? 'checked' : '' }}>
                                <label for="category-{{ $category->id }}" class="ml-2 text-sm text-gray-600">
                                    {{ $category->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Brands -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-medium">Brands</h3>
                            <button type="button" 
                                    onclick="clearBrandFilters()"
                                    class="text-sm text-primary hover:text-primary-dark">
                                Clear
                            </button>
                        </div>
                        <div class="space-y-2 max-h-48 overflow-y-auto custom-scrollbar">
                            @foreach($brands as $brand)
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="brand-{{ $brand->id }}" 
                                       name="brand[]" 
                                       value="{{ $brand->slug }}"
                                       class="rounded border-gray-300 text-primary focus:ring-primary"
                                       {{ in_array($brand->slug, request()->brand ?? []) ? 'checked' : '' }}>
                                <label for="brand-{{ $brand->id }}" class="ml-2 text-sm text-gray-600">
                                    {{ $brand->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-medium">Price Range</h3>
                            <button type="button" 
                                    onclick="clearPriceFilters()"
                                    class="text-sm text-primary hover:text-primary-dark">
                                Clear
                            </button>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm text-gray-600">Min Price</label>
                                <input type="number" 
                                       name="price_min" 
                                       value="{{ request()->price_min }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="text-sm text-gray-600">Max Price</label>
                                <input type="number" 
                                       name="price_max" 
                                       value="{{ request()->price_max }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            </div>
                        </div>
                    </div>

                    <!-- Filter Actions -->
                    <div class="flex flex-col gap-2 sticky bottom-0 bg-white pt-4 border-t">
                        <button type="submit" 
                                class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition duration-200 flex items-center justify-center">
                            <i class="fas fa-filter mr-2"></i>
                            Apply Filters
                        </button>
                        <button type="button"
                                onclick="clearAllFilters()"
                                class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-200 transition duration-200 flex items-center justify-center">
                            <i class="fas fa-times mr-2"></i>
                            Clear All Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="w-full md:w-3/4">
            <!-- Sort and Search -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                <form action="{{ route('customer.products.index') }}" method="GET" class="w-full md:w-auto mb-4 md:mb-0 flex gap-2">
                    <input type="text" 
                           name="search" 
                           placeholder="Search products..."
                           value="{{ request()->search }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <div class="w-full md:w-auto">
                    <select name="sort" 
                            onchange="this.form.submit()"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        <option value="newest" {{ request()->sort == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="price_asc" {{ request()->sort == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request()->sort == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="name_asc" {{ request()->sort == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                    </select>
                </div>
            </div>

            <!-- Active Filters -->
            @if(request()->hasAny(['category', 'brand', 'price_min', 'price_max', 'search', 'sort']))
                <div class="mb-4 flex flex-wrap gap-2">
                    @foreach(request()->category ?? [] as $category)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800">
                            Category: {{ $categories->firstWhere('slug', $category)->name }}
                            <button type="button" onclick="removeFilter('category', '{{ $category }}')" class="ml-2 text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </span>
                    @endforeach

                    @foreach(request()->brand ?? [] as $brand)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800">
                            Brand: {{ $brands->firstWhere('slug', $brand)->name }}
                            <button type="button" onclick="removeFilter('brand', '{{ $brand }}')" class="ml-2 text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </span>
                    @endforeach

                    @if(request()->price_min)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800">
                            Min Price: ₱{{ request()->price_min }}
                            <button type="button" onclick="removeFilter('price_min')" class="ml-2 text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </span>
                    @endif

                    @if(request()->price_max)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800">
                            Max Price: ₱{{ request()->price_max }}
                            <button type="button" onclick="removeFilter('price_max')" class="ml-2 text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </span>
                    @endif

                    @if(request()->search)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800">
                            Search: {{ request()->search }}
                            <button type="button" onclick="removeFilter('search')" class="ml-2 text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </span>
                    @endif

                    <button type="button" 
                            onclick="clearAllFilters()"
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-800 hover:bg-gray-200">
                        <i class="fas fa-times mr-1"></i>
                        Clear All
                    </button>
                </div>
            @endif

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($products as $product)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <a href="{{ route('customer.products.show', $product) }}">
                        <img src="{{ url('storage/app/products/' . $product->main_image) }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-48 object-cover">
                    </a>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2">
                            <a href="{{ route('customer.products.show', $product) }}" 
                               class="text-gray-900 hover:text-primary">
                                {{ $product->name }}
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm mb-2">{{ $product->category->name }}</p>
                        
                        <!-- Rating -->
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($product->average_rating))
                                        <i class="fas fa-star text-sm"></i>
                                    @elseif($i - 0.5 <= $product->average_rating)
                                        <i class="fas fa-star-half-alt text-sm"></i>
                                    @else
                                        <i class="far fa-star text-sm"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-xs text-gray-500 ml-1">({{ $product->review_count }})</span>
                        </div>

                        <!-- Stock Status -->
                        <div class="flex items-center mb-2">
                            @if($product->isInStock())
                                <div class="flex items-center text-green-600">
                                    <i class="fas fa-check-circle text-sm mr-1"></i>
                                    <span class="text-sm">In Stock</span>
                                </div>
                                <span class="text-xs text-gray-500 ml-1">({{ $product->stock }} available)</span>
                            @else
                                <div class="flex items-center text-red-600">
                                    <i class="fas fa-times-circle text-sm mr-1"></i>
                                    <span class="text-sm">Out of Stock</span>
                                </div>
                            @endif
                        </div>

                        <div class="flex justify-between items-center mt-3">
                            <span class="text-primary font-semibold">₱{{ number_format($product->price, 2) }}</span>
                            @if($product->isInStock())
                                <button type="button"
                                        onclick="addToCart({{ $product->id }})" 
                                        class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition duration-200 flex items-center">
                                    <i class="fas fa-shopping-cart mr-2"></i>
                                    Add to Cart
                                </button>
                            @else
                                <button type="button"
                                        disabled 
                                        class="bg-gray-300 text-gray-500 py-2 px-4 rounded-md cursor-not-allowed flex items-center">
                                    <i class="fas fa-times-circle mr-2"></i>
                                    Out of Stock
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-500">No products found.</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                @if($products->hasPages())
                    <div class="flex flex-wrap justify-center gap-2">
                        {{-- Previous Page Link --}}
                        @if($products->onFirstPage())
                            <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-left mr-1"></i> Previous
                            </span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}" class="px-4 py-2 bg-white text-gray-700 rounded-lg hover:bg-gray-50 border border-gray-300 transition duration-150">
                                <i class="fas fa-chevron-left mr-1"></i> Previous
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            @if($page == $products->currentPage())
                                <span class="px-4 py-2 bg-primary text-white rounded-lg">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="px-4 py-2 bg-white text-gray-700 rounded-lg hover:bg-gray-50 border border-gray-300 transition duration-150">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" class="px-4 py-2 bg-white text-gray-700 rounded-lg hover:bg-gray-50 border border-gray-300 transition duration-150">
                                Next <i class="fas fa-chevron-right ml-1"></i>
                            </a>
                        @else
                            <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                Next <i class="fas fa-chevron-right ml-1"></i>
                            </span>
                        @endif
                    </div>

                    {{-- Page Info --}}
                    <div class="text-center mt-4 text-sm text-gray-600">
                        Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Custom scrollbar for filter sections */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 3px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #a0aec0;
    }

    /* Mobile filter panel */
    @media (max-width: 768px) {
        #filterForm {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }
    }
</style>
@endpush

@push('scripts')
<script>
// Toggle filters on mobile
function toggleFilters() {
    const filterForm = document.getElementById('filterForm');
    filterForm.classList.toggle('hidden');
}

// Clear all filters
function clearAllFilters() {
    const form = document.getElementById('filterForm');
    const url = new URL(window.location.href);
    
    // Clear all filter parameters
    ['category[]', 'brand[]', 'price_min', 'price_max', 'search', 'sort'].forEach(param => {
        url.searchParams.delete(param);
    });
    
    window.location.href = url.toString();
}

// Clear category filters
function clearCategoryFilters() {
    document.querySelectorAll('input[name="category[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
}

// Clear brand filters
function clearBrandFilters() {
    document.querySelectorAll('input[name="brand[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
}

// Clear price filters
function clearPriceFilters() {
    document.querySelector('input[name="price_min"]').value = '';
    document.querySelector('input[name="price_max"]').value = '';
}

// Remove individual filter
function removeFilter(type, value = null) {
    const form = document.getElementById('filterForm');
    const url = new URL(window.location.href);
    
    if (type === 'category' || type === 'brand') {
        // Remove specific value from array
        const currentValues = url.searchParams.getAll(type + '[]');
        const newValues = currentValues.filter(v => v !== value);
        url.searchParams.delete(type + '[]');
        newValues.forEach(v => url.searchParams.append(type + '[]', v));
    } else {
        // Remove single parameter
        url.searchParams.delete(type);
    }
    
    window.location.href = url.toString();
}

// Handle form submission
document.getElementById('filterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const url = new URL(window.location.href);
    
    // Clear existing parameters
    ['category[]', 'brand[]', 'price_min', 'price_max', 'search', 'sort'].forEach(param => {
        url.searchParams.delete(param);
    });
    
    // Add new parameters
    for (let [key, value] of formData.entries()) {
        if (value) { // Only add non-empty values
            url.searchParams.append(key, value);
        }
    }
    
    window.location.href = url.toString();
});
</script>
@endpush
@endsection 