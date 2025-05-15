@extends('layouts.app')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Content -->
        <div class="flex-1 overflow-auto p-6">
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
                <div class="relative w-96">
                    <div class="w-5 h-5 flex items-center justify-center absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="ri-search-line"></i>
                    </div>
                    <input type="text" placeholder="Search inventory..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </div>
                <button id="addNewItemBtn" class="flex items-center bg-primary text-white px-4 py-2 rounded-button font-medium hover:bg-primary/90 whitespace-nowrap">
                    <div class="w-5 h-5 flex items-center justify-center mr-2">
                        <i class="ri-add-line"></i>
                    </div>
                    Add New Item
                </button>
            </div>
            <!-- Filters -->
            <div class="bg-white p-4 rounded mb-6 shadow-sm">
                <form method="GET" action="/inventory">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-medium text-gray-700">Filters</h3>
                        <button type="submit" class="text-sm text-primary hover:text-primary/80 font-medium whitespace-nowrap !rounded-button">Apply Filters</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Category</label>
                            <select name="category" class="w-full border border-gray-300 rounded p-2 text-sm custom-select pr-8 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                <option value="">All Categories</option>
                                <option value="wipers" {{ request('category') == 'wipers' ? 'selected' : '' }}>Wipers</option>
                                <option value="filters" {{ request('category') == 'filters' ? 'selected' : '' }}>Filters</option>
                                <option value="oils" {{ request('category') == 'oils' ? 'selected' : '' }}>Oils & Fluids</option>
                                <option value="lighting" {{ request('category') == 'lighting' ? 'selected' : '' }}>Lighting</option>
                                <option value="engine" {{ request('category') == 'engine' ? 'selected' : '' }}>Engine Parts</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Brand</label>
                            <select name="brand" class="w-full border border-gray-300 rounded p-2 text-sm custom-select pr-8 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                <option value="">All Brands</option>
                                <option value="bosch" {{ request('brand') == 'bosch' ? 'selected' : '' }}>Bosch</option>
                                <option value="toyota" {{ request('brand') == 'toyota' ? 'selected' : '' }}>Toyota</option>
                                <option value="shell" {{ request('brand') == 'shell' ? 'selected' : '' }}>Shell</option>
                                <option value="honda" {{ request('brand') == 'honda' ? 'selected' : '' }}>Honda</option>
                                <option value="denso" {{ request('brand') == 'denso' ? 'selected' : '' }}>Denso</option>
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
                        <img src="{{ $product->image_url ? $product->image_url : asset('images/no-image.png') }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-48 object-cover object-top">
                        <div class="action-buttons absolute top-2 right-2 flex space-x-1">
                            <button class="w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center text-gray-600 hover:text-primary whitespace-nowrap !rounded-button edit-btn" 
                                onclick="openEditModal({{ $product }})">
                                <i class="ri-edit-line"></i>
                            </button>
                            <form id="deleteForm{{ $product->id }}" method="POST" action="/products/{{ $product->id }}" onsubmit="return confirm('Are you sure you want to delete this product?');">
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
                                <button class="w-7 h-7 bg-gray-100 rounded flex items-center justify-center text-gray-600 hover:bg-gray-200 whitespace-nowrap !rounded-button">
                                    <i class="ri-eye-line"></i>
                                </button>
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
                <div class="text-sm text-gray-600">
                    Showing {{ $products->firstItem() }} - {{ $products->lastItem() }} of {{ $products->total() }} items
                </div>
                {{ $products->links() }}
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600">Items per page:</span>
                    <select class="border border-gray-300 rounded p-1 text-sm custom-select pr-6 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        <option value="8" selected>8</option>
                        <option value="16">16</option>
                        <option value="24">24</option>
                        <option value="32">32</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add New Item Modal (Hidden by default) -->
<div id="addItemModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-auto">
        <form id="addItemForm" method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
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
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                        <input name="ProductName" type="text" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                        <input name="SKU" type="text" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="Category" class="w-full border border-gray-300 rounded p-2 custom-select pr-8 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                            <option value="" disabled selected>Select a category</option>
                            <option value="wipers">Wipers</option>
                            <option value="filters">Filters</option>
                            <option value="oils">Oils & Fluids</option>
                            <option value="lighting">Lighting</option>
                            <option value="engine">Engine Parts</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                        <select name="Brand" class="w-full border border-gray-300 rounded p-2 custom-select pr-8 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                            <option value="" disabled selected>Select a brand</option>
                            <option value="bosch">Bosch</option>
                            <option value="toyota">Toyota</option>
                            <option value="shell">Shell</option>
                            <option value="honda">Honda</option>
                            <option value="denso">Denso</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price (₱)</label>
                        <input name="Price" type="number" step="0.01" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
                        <input name="Quantity" type="number" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="Description" rows="3" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
                    <input name="Image" type="file" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" accept="image/*">
                </div>
                <div class="flex items-center">
                    <input name="Featured" type="checkbox" class="custom-checkbox" value="1">
                    <label class="ml-2 text-sm text-gray-700">Featured product</label>
                </div>
            </div>
            <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" class="px-4 py-2 bg-gray-100 text-gray-700 rounded border border-gray-300 hover:bg-gray-200 whitespace-nowrap !rounded-button" id="cancelAddItem">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded hover:bg-primary/90 whitespace-nowrap !rounded-button">Add Product</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Product Modal (Hidden by default) -->
<div id="editModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-auto">
        <form id="editForm" method="POST" action="" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Edit Product</h2>
                <button id="closeModal" type="button" class="text-gray-500 hover:text-gray-700">
                    <div class="w-6 h-6 flex items-center justify-center">
                        <i class="ri-close-line text-xl"></i>
                    </div>
                </button>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                        <input id="editProductName" name="ProductName" type="text" class="w-full border border-gray-300 rounded p-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                        <input id="editSKU" name="SKU" type="text" class="w-full border border-gray-300 rounded p-2" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select id="editCategory" name="Category" class="w-full border border-gray-300 rounded p-2 custom-select pr-8" required>
                            <option value="wipers">Wipers</option>
                            <option value="filters">Filters</option>
                            <option value="oils">Oils & Fluids</option>
                            <option value="lighting">Lighting</option>
                            <option value="engine">Engine Parts</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                        <select id="editBrand" name="Brand" class="w-full border border-gray-300 rounded p-2 custom-select pr-8" required>
                            <option value="bosch">Bosch</option>
                            <option value="toyota">Toyota</option>
                            <option value="shell">Shell</option>
                            <option value="honda">Honda</option>
                            <option value="denso">Denso</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price (₱)</label>
                        <input id="editPrice" name="Price" type="number" step="0.01" class="w-full border border-gray-300 rounded p-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
                        <input id="editStock" name="Quantity" type="number" class="w-full border border-gray-300 rounded p-2" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="editDescription" name="Description" rows="3" class="w-full border border-gray-300 rounded p-2" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
                    <input id="editImage" name="Image" type="file" class="w-full border border-gray-300 rounded p-2" accept="image/*">
                </div>
                <div class="flex items-center">
                    <input id="editFeatured" name="Featured" type="checkbox" class="custom-checkbox" value="1">
                    <label class="ml-2 text-sm text-gray-700">Featured product</label>
                </div>
            </div>
            <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" class="px-4 py-2 bg-gray-100 text-gray-700 rounded border border-gray-300 hover:bg-gray-200 whitespace-nowrap !rounded-button" id="cancelEdit">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded hover:bg-primary/90 whitespace-nowrap !rounded-button">Save Changes</button>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteModal = document.getElementById('deleteModal');
        const confirmDeleteBtn = document.getElementById('confirmDelete');
        const cancelDeleteBtn = document.getElementById('cancelDelete');
        const deleteForm = document.querySelector('#deleteModal form');

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
                addItemForm.reset(); // Reset form fields
            });
        }

        if(cancelAddItem) {
            cancelAddItem.addEventListener('click', () => {
                addItemModal.classList.add('hidden');
                addItemForm.reset(); // Reset form fields
            });
        }

        if(addItemModal) {
            addItemModal.addEventListener('click', function(e) {
                if (e.target === addItemModal) {
                    addItemModal.classList.add('hidden');
                    addItemForm.reset(); // Reset form fields
                }
            });
        }

        // DELETE MODAL
        window.openDeleteModal = function(productId) {
            deleteModal.classList.remove('hidden');
            deleteForm.action = `/products/${productId}`;
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

        window.openEditModal = function(product) {
            editModal.classList.remove('hidden');
            editForm.action = `/products/${product.id}`;
            document.getElementById('editProductName').value = product.name;
            document.getElementById('editSKU').value = product.sku;
            document.getElementById('editCategory').value = product.category;
            document.getElementById('editBrand').value = product.brand;
            document.getElementById('editPrice').value = parseFloat(product.price).toFixed(2);
            document.getElementById('editStock').value = product.stock;
            document.getElementById('editDescription').value = product.description;
            document.getElementById('editFeatured').checked = Boolean(product.featured);
        };

        cancelEdit.addEventListener('click', () => {
            editModal.classList.add('hidden');
        });

        closeModal.addEventListener('click', () => {
            editModal.classList.add('hidden');
        });

        editModal.addEventListener('click', function(e) {
            if (e.target === editModal) {
                editModal.classList.add('hidden');
            }
        });

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
        const filterForm = document.querySelector('form[action="/inventory"]');
        const filterInputs = filterForm.querySelectorAll('select, input');

        filterInputs.forEach(input => {
            input.addEventListener('change', () => {
                filterForm.submit();
            });
        });
    });
</script>
@endsection
