@extends('layouts.app')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Content -->
        <div class="flex-1 overflow-auto p-6">
            <!-- Metrics Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Inventory Value -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-b-4 border-indigo-500">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm">Total Inventory Value</span>
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500">
                            <i class="ri-money-dollar-circle-line"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-1">₱{{ number_format($inventoryMetrics['total_value'], 2) }}</h3>
                </div>

                <!-- Low Stock Items -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-b-4 border-yellow-500">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm">Low Stock Items</span>
                        <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-500">
                            <i class="ri-alert-line"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-1">{{ $inventoryMetrics['low_stock'] }}</h3>
                </div>

                <!-- Out of Stock -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-b-4 border-red-500">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm">Out of Stock</span>
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-500">
                            <i class="ri-close-circle-line"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-1">{{ $inventoryMetrics['out_of_stock'] }}</h3>
                </div>

                <!-- Total Products -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-b-4 border-green-500">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm">Total Products</span>
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-500">
                            <i class="ri-shopping-bag-line"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-1">{{ $inventoryMetrics['total_products'] }}</h3>
                </div>
            </div>

            <!-- Notifications -->
            @if (session('success'))
            <div id="successAlert" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
                <span id="closeSuccessAlert" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">
                    <i class="ri-close-line"></i>
                </span>
            </div>
            @endif
            @if (session('error'))
            <div id="errorAlert" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
                <span id="closeErrorAlert" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">
                    <i class="ri-close-line"></i>
                </span>
            </div>
            @endif
            <!-- Search and Add -->
            <div class="flex justify-between items-center mb-6">
                <form method="GET" action="{{ route('admin.inventory') }}" class="relative w-96">
                    <div class="w-5 h-5 flex items-center justify-center absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="ri-search-line"></i>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search inventory..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    @if(request('search'))
                    <button type="button" 
                            onclick="window.location='{{ route('admin.inventory') }}'"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <i class="ri-close-line"></i>
                        </div>
                    </button>
                    @endif
                </form>
                <button id="addNewItemBtn" class="flex items-center bg-primary text-white px-4 py-2 rounded-button font-medium hover:bg-primary/90 whitespace-nowrap">
                    <div class="w-5 h-5 flex items-center justify-center mr-2">
                        <i class="ri-add-line"></i>
                    </div>
                    Add New Item
                </button>
            </div>
            <!-- Filters -->
            <div class="bg-white p-4 rounded mb-6 shadow-sm">
                <form method="GET" action="{{ route('admin.inventory') }}">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-medium text-gray-700">Filters</h3>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.inventory') }}" class="text-sm text-gray-600 hover:text-gray-800 font-medium whitespace-nowrap !rounded-button px-3 py-1 bg-gray-100 hover:bg-gray-200">
                                <i class="ri-refresh-line mr-1"></i>
                                Clear Filters
                            </a>
                            <button type="submit" class="text-sm text-primary hover:text-primary/80 font-medium whitespace-nowrap !rounded-button">Apply Filters</button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Category</label>
                            <select name="category" class="w-full border border-gray-300 rounded p-2 text-sm custom-select pr-8 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Brand</label>
                            <select name="brand" class="w-full border border-gray-300 rounded p-2 text-sm custom-select pr-8 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                <option value="">All Brands</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->slug }}" {{ request('brand') == $brand->slug ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Stock Status</label>
                            <select name="stock_status" class="w-full border border-gray-300 rounded p-2 text-sm custom-select pr-8 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                <option value="">All Status</option>
                                <option value="instock" {{ request('stock_status') == 'instock' ? 'selected' : '' }}>In Stock</option>
                                <option value="lowstock" {{ request('stock_status') == 'lowstock' ? 'selected' : '' }}>Low Stock</option>
                                <option value="outofstock" {{ request('stock_status') == 'outofstock' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Price Range</label>
                            <div class="flex items-center space-x-2">
                                <input name="price_min" type="number" value="{{ request('price_min') }}" placeholder="Min" class="w-full border border-gray-300 rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                <span class="text-gray-400">-</span>
                                <input name="price_max" type="number" value="{{ request('price_max') }}" placeholder="Max" class="w-full border border-gray-300 rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                @forelse ($products as $product)
                <div class="bg-white rounded shadow-sm overflow-hidden product-card group">
                    <div class="relative">
                        <img src="{{ $product->getImageUrlAttribute() }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-48 object-cover object-top">
                        <div class="action-buttons absolute top-2 right-2 flex space-x-1">
                            <button class="w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center text-gray-600 hover:text-primary whitespace-nowrap !rounded-button edit-btn" 
                                data-product="{{ json_encode($product) }}"
                                type="button">
                                <i class="ri-edit-line"></i>
                            </button>
                            <button class="w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center text-gray-600 hover:text-primary whitespace-nowrap !rounded-button view-btn" 
                                onclick="openViewModal({{ $product }})">
                                <i class="ri-eye-line"></i>
                            </button>
                            <form id="deleteForm{{ $product->id }}" method="POST" action="{{ route('admin.products.destroy', $product->id) }}" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="openDeleteModal({{ $product->id }})" class="w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center text-gray-600 hover:text-red-500 whitespace-nowrap !rounded-button delete-btn">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium text-gray-800 mb-1 truncate" title="{{ $product->name }}">{{ $product->name }}</h3>
                        <div class="flex items-center text-sm mb-2">
                            @if($product->stock > 10)
                                <div class="flex items-center text-green-600">
                                    <div class="w-4 h-4 flex items-center justify-center mr-1">
                                        <i class="ri-checkbox-circle-fill"></i>
                                    </div>
                                    <span>In Stock ({{ $product->stock }})</span>
                                </div>
                            @elseif($product->stock > 0)
                                <div class="flex items-center text-yellow-600">
                                    <div class="w-4 h-4 flex items-center justify-center mr-1">
                                        <i class="ri-error-warning-fill"></i>
                                    </div>
                                    <span>Low Stock ({{ $product->stock }})</span>
                                </div>
                            @else
                                <div class="flex items-center text-red-600">
                                    <div class="w-4 h-4 flex items-center justify-center mr-1">
                                        <i class="ri-close-circle-fill"></i>
                                    </div>
                                    <span>Out of Stock</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700 font-medium">₱{{ number_format($product->price, 2, '.', ',') }}</span>
                            <div class="flex space-x-1">
                                <a href="{{ route('admin.stock.in') }}" class="w-7 h-7 bg-gray-100 rounded flex items-center justify-center text-gray-600 hover:bg-gray-200 whitespace-nowrap !rounded-button" title="Manage Stock">
                                    <i class="ri-stack-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                @if ($errors->any())
                <div class="col-span-full flex flex-col items-center justify-center py-12">
                    <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-4">
                        <i class="ri-error-warning-line text-2xl text-red-500"></i>
                    </div>
                    <h3 class="text-lg font-medium text-red-600 mb-1">Error</h3>
                    <p class="text-sm text-gray-500 mb-4">An error occurred while fetching the products. Please check your filters or try again later.</p>
                </div>
                @elseif (request('category') && !$products->count())
                <div class="col-span-full flex flex-col items-center justify-center py-12">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <i class="ri-filter-line text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No products found in the selected category</h3>
                    <p class="text-sm text-gray-500 mb-4">Try selecting a different category.</p>
                </div>
                @elseif (request('brand') && !$products->count())
                <div class="col-span-full flex flex-col items-center justify-center py-12">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <i class="ri-filter-line text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No products found for the selected brand</h3>
                    <p class="text-sm text-gray-500 mb-4">Try selecting a different brand.</p>
                </div>
                @elseif (request('stock_status') && !$products->count())
                <div class="col-span-full flex flex-col items-center justify-center py-12">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <i class="ri-filter-line text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No products found with the selected stock status</h3>
                    <p class="text-sm text-gray-500 mb-4">Try selecting a different stock status.</p>
                </div>
                @elseif ((request('price_min') || request('price_max')) && !$products->count())
                <div class="col-span-full flex flex-col items-center justify-center py-12">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <i class="ri-filter-line text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No products found in the selected price range</h3>
                    <p class="text-sm text-gray-500 mb-4">Try adjusting the price range.</p>
                </div>
                @else
                <div class="col-span-full flex flex-col items-center justify-center py-12">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <i class="ri-shopping-bag-line text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No products found</h3>
                    <p class="text-sm text-gray-500 mb-4">Start by adjusting your filters or adding some products to your inventory.</p>
                    <button class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg flex items-center text-sm">
                        <i class="ri-add-line mr-2"></i>
                        Add New Item
                    </button>
                </div>
                @endif
                @endforelse
            </div>
            <!-- Pagination -->
            <div class="flex items-center justify-between bg-white p-4 rounded shadow-sm">
                <div class="flex items-center">
                    <div class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ $products->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $products->lastItem() ?? 0 }}</span> of <span class="font-medium">{{ $products->total() }}</span> items
                    </div>
                    <div class="ml-4 flex items-center space-x-2">
                        <span class="text-sm text-gray-600">Show:</span>
                        <select onchange="updatePerPage(this.value)" 
                                class="border border-gray-300 rounded p-1 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            @foreach([8, 16, 24, 32] as $perPage)
                                <option value="{{ $perPage }}" {{ request('per_page', 8) == $perPage ? 'selected' : '' }}>
                                    {{ $perPage }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="flex items-center justify-center space-x-2">
                    @if($products->onFirstPage())
                        <span class="px-3 py-1 bg-gray-50 text-gray-400 rounded cursor-not-allowed transition-colors duration-200">&laquo; Previous</span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}" class="px-3 py-1 bg-white text-indigo-600 hover:bg-indigo-50 rounded transition-colors duration-200">&laquo; Previous</a>
                    @endif

                    <div class="flex items-center space-x-1">
                        @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            @if($page == $products->currentPage())
                                <span class="px-3 py-1 bg-indigo-600 text-white rounded">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-1 bg-white hover:bg-indigo-50 text-gray-600 hover:text-indigo-600 rounded transition-colors duration-200">{{ $page }}</a>
                            @endif
                        @endforeach
                    </div>

                    @if($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" class="px-3 py-1 bg-white text-indigo-600 hover:bg-indigo-50 rounded transition-colors duration-200">Next &raquo;</a>
                    @else
                        <span class="px-3 py-1 bg-gray-50 text-gray-400 rounded cursor-not-allowed transition-colors duration-200">Next &raquo;</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add New Item Modal -->
<div id="addItemModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-auto">
        <form id="addItemForm" 
              method="POST" 
              action="{{ route('admin.products.store') }}" 
              enctype="multipart/form-data">
            @csrf
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Add New Product</h2>
                <button id="closeAddItemModal" type="button" class="text-gray-500 hover:text-gray-700">
                    <div class="w-6 h-6 flex items-center justify-center">
                        <i class="ri-close-line text-xl"></i>
                    </div>
                </button>
            </div>
            <div class="p-6">
                @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded">
                    <ul class="list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                        <input name="name" type="text" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                        <input name="sku" type="text" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <div class="relative">
                            <select name="category_id" id="categorySelect" class="w-full border border-gray-300 rounded p-2 custom-select pr-8 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                                <option value="" disabled selected>Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                                <option value="new">+ Add New Category</option>
                            </select>
                            <div id="newCategoryInput" class="hidden mt-2">
                                <input type="text" name="new_category" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" placeholder="Enter new category">
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                        <div class="relative">
                            <select name="brand_id" id="brandSelect" class="w-full border border-gray-300 rounded p-2 custom-select pr-8 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                                <option value="" disabled selected>Select a brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                                <option value="new">+ Add New Brand</option>
                            </select>
                            <div id="newBrandInput" class="hidden mt-2">
                                <input type="text" name="new_brand" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" placeholder="Enter new brand">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                        <select name="supplier_id" class="w-full border border-gray-300 rounded p-2 custom-select pr-8 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            <option value="">Select a supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price (₱)</label>
                        <input name="price" type="number" step="0.01" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
                    <input name="stock" type="number" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
                    <input name="image" type="file" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" accept="image/*" required>
                </div>
                <div class="flex items-center">
                    <input name="featured" type="checkbox" class="custom-checkbox" value="1">
                    <label class="ml-2 text-sm text-gray-700">Featured product</label>
                </div>
            </div>
            <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" 
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded border border-gray-300 hover:bg-gray-200 whitespace-nowrap !rounded-button" 
                        id="cancelAddItem">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-primary text-white rounded hover:bg-primary/90 whitespace-nowrap !rounded-button">
                    Add Product
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Product Modal -->
<div id="editModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg w-full max-w-5xl max-h-[90vh] overflow-auto">
        <form id="editForm" method="POST" action="" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!-- Header -->
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center z-10">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Edit Product</h2>
                    <p class="text-sm text-gray-500 mt-1">Update product information and images</p>
                </div>
                <button id="closeModal" type="button" class="text-gray-500 hover:text-gray-700 transition-colors">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column - Images -->
                    <div>
                        <div class="space-y-4">
                            <!-- Main Image -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Main Product Image</label>
                                <div class="relative aspect-square">
                                    <img id="mainImagePreview" src="" alt="Main product image" 
                                         class="w-full h-full object-contain p-4">
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity cursor-pointer">
                                        <label for="mainImage" class="cursor-pointer bg-white text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors flex items-center space-x-2">
                                            <i class="ri-camera-line"></i>
                                            <span>Change Image</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Images -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Images</label>
                                <div class="grid grid-cols-2 gap-4">
                                    @for($i = 1; $i <= 4; $i++)
                                    <div class="relative aspect-square border border-gray-200 rounded-xl overflow-hidden">
                                        <img id="additionalImage{{ $i }}Preview" src="" alt="Additional image {{ $i }}"
                                             class="w-full h-full object-contain p-2">
                                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity cursor-pointer">
                                            <label for="additionalImage{{ $i }}" class="cursor-pointer bg-white text-gray-700 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition-colors flex items-center space-x-1">
                                                <i class="ri-camera-line"></i>
                                                <span>Change</span>
                                            </label>
                                        </div>
                                        <input type="file" 
                                               id="additionalImage{{ $i }}" 
                                               name="additional_images[]" 
                                               class="hidden" 
                                               accept="image/*"
                                               onchange="previewImage(this, 'additionalImage{{ $i }}Preview')">
                                    </div>
                                    @endfor
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Upload up to 4 additional product images</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Product Details -->
                    <div class="space-y-6">
                        <!-- Basic Information Card -->
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                <h3 class="text-sm font-medium text-gray-700">Basic Information</h3>
                            </div>
                            <div class="p-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                                    <input id="editProductName" name="name" type="text" 
                                           class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                                    <input id="editSku" name="sku" type="text" 
                                           class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <textarea id="editDescription" name="description" rows="4" 
                                              class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Categories and Brand Card -->
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                <h3 class="text-sm font-medium text-gray-700">Classification</h3>
                            </div>
                            <div class="p-4 space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                        <select id="editCategory" name="category_id" 
                                                class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                                        <select id="editBrand" name="brand_id" 
                                                class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                                    <select id="editSupplier" name="supplier_id" 
                                            class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                        <option value="">Select a supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing and Stock Card -->
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                <h3 class="text-sm font-medium text-gray-700">Pricing & Stock</h3>
                            </div>
                            <div class="p-4 space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Price (₱)</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                                            <input id="editPrice" name="price" type="number" step="0.01" 
                                                   class="w-full pl-8 border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Stock</label>
                                        <div class="relative">
                                            <input type="text" id="editStockDisplay" 
                                                   class="w-full border border-gray-300 rounded-lg p-2.5 bg-gray-50" disabled>
                                            <input type="hidden" id="editStock" name="stock">
                                            <a id="editStockManagementLink" href="" 
                                               class="absolute right-2 top-1/2 -translate-y-1/2 text-primary hover:text-primary/80 flex items-center space-x-1">
                                                <i class="ri-stack-line"></i>
                                                <span>Manage</span>
                                            </a>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">Stock can only be modified through Stock In/Out management</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Settings Card -->
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                <h3 class="text-sm font-medium text-gray-700">Additional Settings</h3>
                            </div>
                            <div class="p-4">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input id="editFeatured" name="featured" type="checkbox" 
                                           class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary" value="1">
                                    <span class="text-sm text-gray-700">Feature this product on the homepage</span>
                                </label>
                            </div>
                        </div>

                        <!-- Image Upload Card -->
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                <h3 class="text-sm font-medium text-gray-700">Product Images</h3>
                            </div>
                            <div class="p-4 space-y-4">
                                <!-- Main Image -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Main Product Image</label>
                                    <div class="relative">
                                        <img id="mainImagePreview" 
                                             src="{{ $product->getImageUrlAttribute() }}" 
                                             alt="Main product image"
                                             class="w-full h-48 object-contain border border-gray-200 rounded-lg mb-2">
                                        <input type="file" 
                                               name="main_image" 
                                               id="mainImage" 
                                               class="hidden" 
                                               accept="image/*"
                                               onchange="previewImage(this, 'mainImagePreview')">
                                        <label for="mainImage" 
                                               class="absolute bottom-2 right-2 bg-white text-gray-700 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer flex items-center space-x-1">
                                            <i class="ri-camera-line"></i>
                                            <span>Change Image</span>
                                        </label>
                                    </div>
                                    <p class="text-sm text-gray-500">Leave empty to keep current image</p>
                                </div>

                                <!-- Additional Images -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Additional Images</label>
                                    <div class="grid grid-cols-2 gap-4">
                                        @for($i = 1; $i <= 4; $i++)
                                            <div class="relative">
                                                <img id="additionalImage{{ $i }}Preview" 
                                                     src="{{ $product->{"image_{$i}"} ? Storage::disk('public')->url($product->{"image_{$i}"}) : asset('images/products/default.jpg') }}" 
                                                     alt="Additional image {{ $i }}"
                                                     class="w-full h-32 object-contain border border-gray-200 rounded-lg">
                                                <input type="file" 
                                                       name="additional_images[]" 
                                                       id="additionalImage{{ $i }}" 
                                                       class="hidden" 
                                                       accept="image/*"
                                                       onchange="previewImage(this, 'additionalImage{{ $i }}Preview')">
                                                <label for="additionalImage{{ $i }}" 
                                                       class="absolute bottom-2 right-2 bg-white text-gray-700 px-2 py-1 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer flex items-center space-x-1">
                                                    <i class="ri-camera-line"></i>
                                                    <span>Change</span>
                                                </label>
                                            </div>
                                        @endfor
                                    </div>
                                    <p class="text-sm text-gray-500 mt-2">Upload up to 4 additional product images</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="sticky bottom-0 bg-white border-t border-gray-200 px-6 py-4 flex justify-end space-x-3">
                <button type="button" id="cancelEdit"
                        class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center space-x-2">
                    <i class="ri-close-line"></i>
                    <span>Cancel</span>
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors flex items-center space-x-2">
                    <i class="ri-save-line"></i>
                    <span>Save Changes</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal (Hidden by default) -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg w-full max-w-md">
        <div class="p-6">
            <div class="w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center text-red-500">
                <i class="ri-delete-bin-line text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 text-center mb-2">Delete Product</h3>
            <p class="text-gray-600 text-center mb-6">Are you sure you want to delete this product? This action cannot be undone.</p>
            <div class="flex justify-center space-x-3">
                <button id="cancelDelete" class="px-4 py-2 bg-gray-100 text-gray-700 rounded border border-gray-300 hover:bg-gray-200 whitespace-nowrap !rounded-button">Cancel</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button id="confirmDelete" type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 whitespace-nowrap !rounded-button">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- View Product Modal -->
<div id="viewProductModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="min-h-screen px-4 text-center">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="inline-block h-screen align-middle" aria-hidden="true">&#8203;</span>
        <div class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Product Details</h3>
                <button type="button" onclick="closeViewModal()" class="text-gray-400 hover:text-gray-500">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 md:col-span-1">
                    <img id="viewProductImage" src="" alt="Product Image" class="w-full h-48 object-cover rounded">
                </div>
                <div class="col-span-2 md:col-span-1 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Product Name</label>
                        <p id="viewProductName" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <p id="viewProductCategory" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Brand</label>
                        <p id="viewProductBrand" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                </div>
                <div class="col-span-2">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price</label>
                            <p id="viewProductPrice" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Current Stock</label>
                            <p id="viewProductStock" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                    </div>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <p id="viewProductDescription" class="mt-1 text-sm text-gray-900"></p>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <a id="viewStockManagementLink" href="" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <i class="ri-stack-line mr-2"></i>
                    Manage Stock
                </a>
                <button type="button" onclick="closeViewModal()" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-transparent rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteModal = document.getElementById('deleteModal');
        const confirmDeleteBtn = document.getElementById('confirmDelete');
        const cancelDeleteBtn = document.getElementById('cancelDelete');
        const deleteForm = document.querySelector('#deleteModal form');

        // Category and Brand new input handling
        const categorySelect = document.getElementById('categorySelect');
        const brandSelect = document.getElementById('brandSelect');
        const newCategoryInput = document.getElementById('newCategoryInput');
        const newBrandInput = document.getElementById('newBrandInput');

        categorySelect.addEventListener('change', function() {
            if (this.value === 'new') {
                newCategoryInput.classList.remove('hidden');
                const input = newCategoryInput.querySelector('input');
                input.required = true;
                this.required = false;
            } else {
                newCategoryInput.classList.add('hidden');
                const input = newCategoryInput.querySelector('input');
                input.required = false;
                this.required = true;
            }
        });

        brandSelect.addEventListener('change', function() {
            if (this.value === 'new') {
                newBrandInput.classList.remove('hidden');
                const input = newBrandInput.querySelector('input');
                input.required = true;
                this.required = false;
            } else {
                newBrandInput.classList.add('hidden');
                const input = newBrandInput.querySelector('input');
                input.required = false;
                this.required = true;
            }
        });

        // Reset new inputs when modal is closed
        const resetNewInputs = () => {
            newCategoryInput.classList.add('hidden');
            newBrandInput.classList.add('hidden');
            newCategoryInput.querySelector('input').value = '';
            newBrandInput.querySelector('input').value = '';
            categorySelect.required = true;
            brandSelect.required = true;
            categorySelect.value = '';
            brandSelect.value = '';
        };

        // ADD ITEM MODAL
        const addItemModal = document.getElementById('addItemModal');
        const addItemForm = document.getElementById('addItemForm');
        const addNewItemBtn = document.getElementById('addNewItemBtn');
        const closeAddItemModal = document.getElementById('closeAddItemModal');
        const cancelAddItem = document.getElementById('cancelAddItem');

        if(addNewItemBtn) {
            addNewItemBtn.addEventListener('click', () => {
                addItemModal.classList.remove('hidden');
            });
        }

        if(closeAddItemModal) {
            closeAddItemModal.addEventListener('click', () => {
                addItemModal.classList.add('hidden');
                addItemForm.reset();
                resetNewInputs();
            });
        }

        if(cancelAddItem) {
            cancelAddItem.addEventListener('click', () => {
                addItemModal.classList.add('hidden');
                addItemForm.reset();
                resetNewInputs();
            });
        }

        if(addItemModal) {
            addItemModal.addEventListener('click', function(e) {
                if (e.target === addItemModal) {
                    addItemModal.classList.add('hidden');
                    addItemForm.reset();
                    resetNewInputs();
                }
            });
        }

        // DELETE MODAL
        window.openDeleteModal = function(productId) {
            deleteModal.classList.remove('hidden');
            deleteForm.action = "{{ url('/admin/products') }}/" + productId;
        };

        cancelDeleteBtn.addEventListener('click', () => {
            deleteModal.classList.add('hidden');
        });

        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) {
                deleteModal.classList.add('hidden');
            }
        });

        confirmDeleteBtn.addEventListener('click', () => {
            deleteForm.submit();
        });

        // Edit modal functionality
        const editModal = document.getElementById('editModal');
        const editForm = document.getElementById('editForm');
        const cancelEdit = document.getElementById('cancelEdit');
        const closeModal = document.getElementById('closeModal');

        // Add form submission handling
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                // Debug logs
                console.log('Submitting to URL:', this.action);
                console.log('CSRF Token:', csrfToken);
                
                // Add _method field for PUT request
                formData.append('_method', 'PUT');
                
                fetch(this.action, {
                    method: 'POST', // Keep as POST, _method field will make it PUT
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    console.log('Response status:', response.status); // Debug log
                    console.log('Response headers:', response.headers); // Debug log
                    
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.error('Error response:', text);
                            try {
                                // Try to parse as JSON
                                const json = JSON.parse(text);
                                throw new Error(json.message || 'Update failed');
                            } catch (e) {
                                // If not JSON, return generic error
                                throw new Error('Server error occurred. Please try again.');
                            }
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success response:', data); // Debug log
                    if (data.success) {
                        window.location.reload();
                    } else {
                        throw new Error(data.message || 'Update failed');
                    }
                })
                .catch(error => {
                    console.error('Submission error:', error);
                    alert('Failed to update product: ' + (error.message || 'Unknown error'));
                });
            });
        }
        
        // Add click event listener to all edit buttons
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                const productData = JSON.parse(this.getAttribute('data-product'));
                openEditModal(productData);
            });
        });

        window.previewImage = function(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        window.openEditModal = function(product) {
            console.log('Opening edit modal with product:', product);
            
            if (!editModal || !editForm) {
                console.error('Edit modal or form elements not found!');
                return;
            }

            try {
                // Show the modal
                editModal.classList.remove('hidden');
                
                // Set the form action with Laravel route - using absolute path
                const baseUrl = window.location.origin;
                const productUrl = `${baseUrl}/admin/products/${product.id}`;
                editForm.action = productUrl;
                
                console.log('Form action URL:', productUrl); // Debug log
                
                // Set basic product information
                const elements = {
                    'editProductName': product.name,
                    'editDescription': product.description,
                    'editPrice': parseFloat(product.price).toFixed(2),
                    'editStockDisplay': product.stock + " units",
                    'editStock': product.stock,
                    'editSku': product.sku || '',
                    'editCategory': product.category_id,
                    'editBrand': product.brand_id,
                    'editSupplier': product.supplier_id || '',
                    'editFeatured': product.featured
                };

                // Update each element if it exists
                Object.entries(elements).forEach(([id, value]) => {
                    const element = document.getElementById(id);
                    if (element) {
                        if (element.type === 'checkbox') {
                            element.checked = value === 1 || value === true;
                        } else {
                            element.value = value;
                        }
                    } else {
                        console.warn(`Element with id ${id} not found`);
                    }
                });

                // Set the main image preview
                const mainImagePreview = document.getElementById('mainImagePreview');
                if (mainImagePreview) {
                    mainImagePreview.src = product.main_image 
                        ? `{{ Storage::url('') }}${product.main_image}` 
                        : '{{ asset('images/no-image.png') }}';
                }

                // Set additional image previews
                for (let i = 1; i <= 4; i++) {
                    const previewElement = document.getElementById(`additionalImage${i}Preview`);
                        if (previewElement) {
                        const imageField = `image_${i}`;
                        previewElement.src = product[imageField] 
                            ? `{{ Storage::url('') }}${product[imageField]}` 
                            : '{{ asset('images/no-image.png') }}';
                        }
                }

                // Set stock management link
                const stockLink = document.getElementById('editStockManagementLink');
                if (stockLink) {
                    stockLink.href = `{{ url('/admin/stock/in') }}?product=${product.id}`;
                }

            } catch (error) {
                console.error('Error in openEditModal:', error);
                alert('There was an error opening the edit modal. Please try again.');
            }
        };

        if (cancelEdit) {
            cancelEdit.addEventListener('click', () => {
                editModal.classList.add('hidden');
            });
        }

        if (closeModal) {
            closeModal.addEventListener('click', () => {
                editModal.classList.add('hidden');
            });
        }

        if (editModal) {
            editModal.addEventListener('click', function(e) {
                if (e.target === editModal) {
                    editModal.classList.add('hidden');
                }
            });
        }

        // Close success alert
        const successAlert = document.getElementById('successAlert');
        const closeSuccessAlert = document.getElementById('closeSuccessAlert');
        if (successAlert) {
            setTimeout(() => {
                successAlert.classList.add('hidden');
            }, 3000);
            if (closeSuccessAlert) {
                closeSuccessAlert.addEventListener('click', () => {
                    successAlert.classList.add('hidden');
                });
            }
        }

        // Close error alert
        const errorAlert = document.getElementById('errorAlert');
        const closeErrorAlert = document.getElementById('closeErrorAlert');
        if (errorAlert) {
            setTimeout(() => {
                errorAlert.classList.add('hidden');
            }, 3000);
            if (closeErrorAlert) {
                closeErrorAlert.addEventListener('click', () => {
                    errorAlert.classList.add('hidden');
                });
            }
        }

        // Automatic filter submission
        const filterForm = document.querySelector('form[action="{{ route('admin.inventory') }}"]');
        const filterInputs = filterForm.querySelectorAll('select, input');

        filterInputs.forEach(input => {
            input.addEventListener('change', () => {
                filterForm.submit();
            });
        });

        function updatePerPage(value) {
            // Get current URL
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search);
            
            // Update or add per_page parameter
            params.set('per_page', value);
            
            // Keep existing parameters
            if (params.has('search')) {
                params.set('search', params.get('search'));
            }
            if (params.has('category')) {
                params.set('category', params.get('category'));
            }
            if (params.has('brand')) {
                params.set('brand', params.get('brand'));
            }
            
            // Redirect to new URL
            window.location.href = `${url.pathname}?${params.toString()}`;
        }

        function openViewModal(product) {
            document.getElementById('viewProductImage').src = product.image_url ? '/images/' + product.image_url : '/images/no-image.png';
            document.getElementById('viewProductName').textContent = product.name;
            document.getElementById('viewProductCategory').textContent = product.category ? product.category.name : 'N/A';
            document.getElementById('viewProductBrand').textContent = product.brand ? product.brand.name : 'N/A';
            document.getElementById('viewProductPrice').textContent = '₱' + parseFloat(product.price).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('viewProductStock').textContent = product.stock;
            document.getElementById('viewProductDescription').textContent = product.description || 'No description available';
            document.getElementById('viewStockManagementLink').href = '/admin/stock/in?product=' + product.id;
            document.getElementById('viewProductModal').classList.remove('hidden');
        }

        function closeViewModal() {
            document.getElementById('viewProductModal').classList.add('hidden');
        }
    });
</script>
@endsection
