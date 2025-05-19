@extends('layouts.app')

@section('content')
<div x-data="{ 
    addModalOpen: false,
    editModalOpen: false,
    addCategoryInputVisible: false,
    editCategoryInputVisible: false,
    showAddCategory: function() {
        this.addCategoryInputVisible = true;
        setTimeout(() => document.getElementById('newCategoryName').focus(), 100);
    },
    showEditCategory: function() {
        this.editCategoryInputVisible = true;
        setTimeout(() => document.getElementById('editNewCategoryName').focus(), 100);
    },
    openEditModal: function() {
        this.editModalOpen = true;
    },
    closeEditModal: function() {
        this.editModalOpen = false;
    }
}">
    <!-- Action Bar -->
    <div class="flex flex-wrap items-center justify-between mb-6 gap-4">
        <div class="relative w-full md:w-2/5">
            <form action="{{ route('admin.suppliers') }}" method="GET" class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <div class="w-5 h-5 flex items-center justify-center text-gray-400">
                        <i class="ri-search-line"></i>
                    </div>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded focus:ring-2 focus:ring-primary focus:ring-opacity-50 focus:outline-none" 
                       placeholder="Search suppliers...">
                @if(request('search'))
                <button type="button" 
                        onclick="window.location='{{ route('admin.suppliers') }}'"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                    <div class="w-5 h-5 flex items-center justify-center">
                        <i class="ri-close-line"></i>
                    </div>
                </button>
                @endif
            </form>
        </div>
        <div class="flex flex-wrap items-center gap-4">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        type="button"
                        class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-button text-gray-700 hover:bg-gray-50 whitespace-nowrap">
                    <span>Category Filter</span>
                    <div class="w-5 h-5 flex items-center justify-center ml-2">
                        <i class="ri-arrow-down-s-line"></i>
                    </div>
                </button>
                <div x-show="open"
                     @click.outside="open = false"
                     class="absolute right-0 mt-2 py-2 w-48 bg-white rounded-lg shadow-xl z-20">
                    <form action="{{ route('admin.suppliers') }}" method="GET">
                        @foreach($categories as $category)
                        <label class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            <input type="checkbox" 
                                   name="categories[]" 
                                   value="{{ $category->id }}"
                                   {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}
                                   onchange="this.form.submit()"
                                   class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2">{{ $category->name }}</span>
                        </label>
                        @endforeach
                    </form>
                </div>
            </div>
            <button @click="addModalOpen = true" class="flex items-center px-4 py-2 bg-primary text-white rounded-button hover:bg-primary/90 whitespace-nowrap">
                <div class="w-5 h-5 flex items-center justify-center mr-2">
                    <i class="ri-add-line"></i>
                </div>
                <span>Add New Supplier</span>
            </button>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Suppliers -->
        <div class="metric-card bg-white rounded-lg shadow-sm p-6 border-b-4 border-indigo-500">
            <div class="flex justify-between items-center mb-2">
                <span class="text-gray-600 text-sm">Total Suppliers</span>
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                    <i class="ri-user-star-line"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold mb-1">{{ $stats['total_suppliers']['value'] }}</h3>
            <div class="flex items-center text-sm">
                <span class="{{ $stats['total_suppliers']['changeType'] === 'increase' ? 'text-green-500' : 'text-red-500' }} flex items-center mr-2">
                    <i class="ri-arrow-{{ $stats['total_suppliers']['changeType'] === 'increase' ? 'up' : 'down' }}-line mr-1"></i>
                    {{ $stats['total_suppliers']['change'] }}%
                </span>
                <span class="text-gray-500">vs. last month</span>
            </div>
        </div>

        <!-- Active Suppliers -->
        <div class="metric-card bg-white rounded-lg shadow-sm p-6 border-b-4 border-green-500">
            <div class="flex justify-between items-center mb-2">
                <span class="text-gray-600 text-sm">Active Suppliers</span>
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-500">
                    <i class="ri-check-double-line"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold mb-1">{{ $stats['active_suppliers']['value'] }}</h3>
            <div class="flex items-center text-sm">
                <span class="{{ $stats['active_suppliers']['changeType'] === 'increase' ? 'text-green-500' : 'text-red-500' }} flex items-center mr-2">
                    <i class="ri-arrow-{{ $stats['active_suppliers']['changeType'] === 'increase' ? 'up' : 'down' }}-line mr-1"></i>
                    {{ $stats['active_suppliers']['change'] }}%
                </span>
                <span class="text-gray-500">vs. last month</span>
            </div>
        </div>

        <!-- Average Response Time -->
        <div class="metric-card bg-white rounded-lg shadow-sm p-6 border-b-4 border-yellow-500">
            <div class="flex justify-between items-center mb-2">
                <span class="text-gray-600 text-sm">Avg. Response Time</span>
                <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-500">
                    <i class="ri-time-line"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold mb-1">{{ $stats['avg_response_time']['value'] }} days</h3>
            <div class="flex items-center text-sm">
                <span class="{{ $stats['avg_response_time']['changeType'] === 'increase' ? 'text-green-500' : 'text-red-500' }} flex items-center mr-2">
                    <i class="ri-arrow-{{ $stats['avg_response_time']['changeType'] === 'increase' ? 'up' : 'down' }}-line mr-1"></i>
                    {{ $stats['avg_response_time']['change'] }} days
                </span>
                <span class="text-gray-500">vs. last month</span>
            </div>
        </div>

        <!-- On-time Delivery Rate -->
        <div class="metric-card bg-white rounded-lg shadow-sm p-6 border-b-4 border-blue-500">
            <div class="flex justify-between items-center mb-2">
                <span class="text-gray-600 text-sm">On-time Delivery</span>
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                    <i class="ri-truck-line"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold mb-1">{{ $stats['on_time_delivery']['value'] }}</h3>
            <div class="flex items-center text-sm">
                <span class="{{ $stats['on_time_delivery']['changeType'] === 'increase' ? 'text-green-500' : 'text-red-500' }} flex items-center mr-2">
                    <i class="ri-arrow-{{ $stats['on_time_delivery']['changeType'] === 'increase' ? 'up' : 'down' }}-line mr-1"></i>
                    {{ $stats['on_time_delivery']['change'] }}%
                </span>
                <span class="text-gray-500">vs. last month</span>
            </div>
        </div>
    </div>

    <style>
    .metric-card {
        transition: box-shadow 0.2s, transform 0.2s;
    }
    .metric-card:hover {
        box-shadow: 0 8px 32px 0 rgba(99, 102, 241, 0.15), 0 1.5px 6px 0 rgba(0,0,0,0.08);
        transform: translateY(-4px) scale(1.025);
        z-index: 1;
    }
    </style>

    <!-- Suppliers Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
        @forelse($suppliers as $supplier)
        <div class="bg-white rounded shadow p-6 transition-all duration-200 supplier-card">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full {{ $supplier->getIconBackground() }} flex items-center justify-center mr-3">
                        <i class="{{ $supplier->getIcon() }} {{ $supplier->getIconColor() }} ri-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $supplier->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $supplier->contact_person }}</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="px-2 py-1 text-xs font-medium {{ $supplier->getStatusClasses() }} rounded-full">
                        {{ $supplier->status }}
                    </span>
                </div>
            </div>
            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-gray-600">
                    <div class="w-4 h-4 flex items-center justify-center mr-2">
                        <i class="ri-phone-line"></i>
                    </div>
                    <span>{{ $supplier->phone }}</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <div class="w-4 h-4 flex items-center justify-center mr-2">
                        <i class="ri-mail-line"></i>
                    </div>
                    <span>{{ $supplier->email }}</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <div class="w-4 h-4 flex items-center justify-center mr-2">
                        <i class="ri-box-3-line"></i>
                    </div>
                    <span>{{ $supplier->products_count }} products supplied</span>
                </div>
            </div>
            
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Performance Rating</p>
                    <div class="flex">
                        @for($i = 1; $i <= 5; $i++)
                            <div class="w-4 h-4 flex items-center justify-center {{ $i <= $supplier->rating ? 'text-yellow-400' : 'text-gray-300' }}">
                                <i class="ri-star-fill"></i>
                            </div>
                        @endfor
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">On-time Delivery</p>
                    <p class="font-medium text-gray-800">{{ $supplier->on_time_delivery_rate }}%</p>
                </div>
            </div>
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <div class="flex flex-wrap gap-2">
                    @foreach($supplier->categories as $category)
                    <span class="px-2 py-1 text-xs {{ $category->getClasses() }} rounded">
                        {{ $category->name }}
                    </span>
                    @endforeach
                </div>
                <div class="flex space-x-1">
                    <button onclick="openEditModal({{ $supplier->id }})" 
                            class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                        <i class="ri-pencil-line text-gray-600"></i>
                    </button>
                    <form action="{{ route('admin.suppliers.destroy', $supplier) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this supplier?');"
                          class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                            <i class="ri-delete-bin-line text-gray-600"></i>
                        </button>
                    </form>
                    @if($selectedProduct)
                    <a href="{{ route('admin.purchase-orders.create', ['supplier_id' => $supplier->id, 'product_id' => $selectedProduct->id]) }}" 
                       class="inline-flex items-center px-3 py-1 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700">
                        Create Order
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full flex flex-col items-center justify-center py-12">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                <i class="ri-user-star-line text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No suppliers found</h3>
            <p class="text-sm text-gray-500 mb-4">Try adjusting your search or filters.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between bg-white p-4 rounded shadow-sm mt-6">
        <div class="flex items-center">
            <div class="text-sm text-gray-700">
                Showing <span class="font-medium">{{ $suppliers->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $suppliers->lastItem() ?? 0 }}</span> of <span class="font-medium">{{ $suppliers->total() }}</span> suppliers
            </div>
            <div class="ml-4 flex items-center space-x-2">
                <span class="text-sm text-gray-600">Show:</span>
                <select onchange="updatePerPage(this.value)" 
                        class="border border-gray-300 rounded p-1 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    @foreach([12, 24, 36, 48] as $perPage)
                        <option value="{{ $perPage }}" {{ request('per_page', 12) == $perPage ? 'selected' : '' }}>
                            {{ $perPage }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="flex items-center justify-center space-x-2">
            @if($suppliers->onFirstPage())
                <span class="px-3 py-1 bg-gray-50 text-gray-400 rounded cursor-not-allowed transition-colors duration-200">&laquo; Previous</span>
            @else
                <a href="{{ $suppliers->previousPageUrl() }}" class="px-3 py-1 bg-white text-indigo-600 hover:bg-indigo-50 rounded transition-colors duration-200">&laquo; Previous</a>
            @endif

            <div class="flex items-center space-x-1">
                @foreach($suppliers->getUrlRange(1, $suppliers->lastPage()) as $page => $url)
                    @if($page == $suppliers->currentPage())
                        <span class="px-3 py-1 bg-indigo-600 text-white rounded">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1 bg-white hover:bg-indigo-50 text-gray-600 hover:text-indigo-600 rounded transition-colors duration-200">{{ $page }}</a>
                    @endif
                @endforeach
            </div>

            @if($suppliers->hasMorePages())
                <a href="{{ $suppliers->nextPageUrl() }}" class="px-3 py-1 bg-white text-indigo-600 hover:bg-indigo-50 rounded transition-colors duration-200">Next &raquo;</a>
            @else
                <span class="px-3 py-1 bg-gray-50 text-gray-400 rounded cursor-not-allowed transition-colors duration-200">Next &raquo;</span>
            @endif
        </div>
    </div>

    <!-- Add New Supplier Modal -->
    <div id="addSupplierModal" 
         x-show="addModalOpen"
         x-cloak
         @keydown.escape.window="addModalOpen = false"
         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-auto"
             @click.outside="addModalOpen = false">
            <form id="addSupplierForm" 
                  method="POST" 
                  action="{{ route('admin.suppliers.store') }}"
                  @submit.prevent="$el.submit();">
                @csrf
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Add New Supplier</h2>
                    <button type="button" 
                            @click="addModalOpen = false" 
                            class="text-gray-500 hover:text-gray-700">
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier Name</label>
                            <input name="name" type="text" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                            <input name="contact_person" type="text" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input name="email" type="email" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input name="phone" type="text" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea name="address" rows="2" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <div class="space-y-2">
                                <select name="categories[]" 
                                        id="categorySelect" 
                                        class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" 
                                        required>
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                
                                <div class="flex items-center">
                                    <button type="button" 
                                            @click="showAddCategory()"
                                            class="text-sm text-primary hover:text-primary/80 flex items-center">
                                        <i class="ri-add-line mr-1"></i>
                                        Add New Category
                                    </button>
                                </div>

                                <div x-show="addCategoryInputVisible" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     x-transition:leave="transition ease-in duration-100"
                                     x-transition:leave-start="opacity-100 transform scale-100"
                                     x-transition:leave-end="opacity-0 transform scale-95"
                                     class="mt-2">
                                    <div class="flex gap-2">
                                        <input type="text" 
                                               id="newCategoryName" 
                                               placeholder="Enter category name"
                                               @keydown.escape="addCategoryInputVisible = false"
                                               @keydown.enter.prevent="addNewCategory('add')"
                                               class="flex-1 border border-gray-300 rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                        <button type="button"
                                                @click="addNewCategory('add')"
                                                class="px-3 py-2 bg-primary text-white rounded hover:bg-primary/90 text-sm whitespace-nowrap">
                                            Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" 
                            @click="addModalOpen = false"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded border border-gray-300 hover:bg-gray-200 whitespace-nowrap !rounded-button">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-primary text-white rounded hover:bg-primary/90 whitespace-nowrap !rounded-button">
                        Add Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Supplier Modal -->
    <div id="editSupplierModal" 
         x-show="editModalOpen"
         x-cloak
         @keydown.escape.window="closeEditModal()"
         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-auto"
             @click.outside="closeEditModal()">
            <form id="editSupplierForm" method="POST" onsubmit="return handleEditSubmit(event);">
                @csrf
                @method('PUT')
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Edit Supplier</h2>
                    <button type="button" 
                            @click="closeEditModal()"
                            class="text-gray-500 hover:text-gray-700">
                        <div class="w-6 h-6 flex items-center justify-center">
                            <i class="ri-close-line text-xl"></i>
                        </div>
                    </button>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier Name</label>
                            <input id="editName" name="name" type="text" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                            <input id="editContactPerson" name="contact_person" type="text" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input id="editEmail" name="email" type="email" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input id="editPhone" name="phone" type="text" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea id="editAddress" name="address" rows="2" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="editStatus" name="status" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <div class="space-y-2">
                                <select name="categories[]" 
                                        id="editCategorySelect" 
                                        class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" 
                                        required>
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                
                                <div class="flex items-center">
                                    <button type="button" 
                                            @click="showEditCategory()"
                                            class="text-sm text-primary hover:text-primary/80 flex items-center">
                                        <i class="ri-add-line mr-1"></i>
                                        Add New Category
                                    </button>
                                </div>

                                <div x-show="editCategoryInputVisible" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     x-transition:leave="transition ease-in duration-100"
                                     x-transition:leave-start="opacity-100 transform scale-100"
                                     x-transition:leave-end="opacity-0 transform scale-95"
                                     class="mt-2">
                                    <div class="flex gap-2">
                                        <input type="text" 
                                               id="editNewCategoryName" 
                                               placeholder="Enter category name"
                                               @keydown.escape="editCategoryInputVisible = false"
                                               @keydown.enter.prevent="addNewCategory('edit')"
                                               class="flex-1 border border-gray-300 rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                        <button type="button"
                                                @click="addNewCategory('edit')"
                                                class="px-3 py-2 bg-primary text-white rounded hover:bg-primary/90 text-sm whitespace-nowrap">
                                            Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" 
                            @click="closeEditModal()"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded border border-gray-300 hover:bg-gray-200 whitespace-nowrap !rounded-button">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-primary text-white rounded hover:bg-primary/90 whitespace-nowrap !rounded-button">
                        Update Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="flex-1 overflow-auto p-6">
        @if($selectedProduct)
        <!-- Selected Product Info -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Reorder Product</h2>
                <a href="{{ route('admin.suppliers') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="ri-close-line text-xl"></i>
                </a>
            </div>
            <div class="flex items-center space-x-4">
                <div class="w-24 h-24 rounded-lg overflow-hidden">
                    <img src="{{ asset($selectedProduct->image_url) }}" alt="{{ $selectedProduct->name }}" 
                         class="w-full h-full object-cover">
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ $selectedProduct->name }}</h3>
                    <p class="text-sm text-gray-500 mb-2">SKU: {{ $selectedProduct->sku }}</p>
                    <div class="flex items-center text-sm">
                        <span class="text-gray-600 mr-4">Current Stock: {{ $selectedProduct->stock }}</span>
                        <span class="text-gray-600">Price: ₱{{ number_format($selectedProduct->price, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <p>Below are suppliers that can provide this product based on its category. Click on a supplier to create a purchase order.</p>
            </div>
        </div>
        @endif
        <!-- Metrics Overview -->
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show edit modal
    window.openEditModal = async function(supplierId) {
        try {
            console.log('Fetching supplier data for ID:', supplierId);
            const response = await fetch(`/admin/suppliers/${supplierId}/edit`);
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('Response not OK:', {
                    status: response.status,
                    statusText: response.statusText,
                    responseText: errorText
                });
                throw new Error(`HTTP error! status: ${response.status} - ${errorText}`);
            }
            
            const supplier = await response.json();
            console.log('Received supplier data:', supplier);
            
            // Update form action
            const formAction = `/admin/suppliers/${supplierId}`;
            document.getElementById('editSupplierForm').action = formAction;
            console.log('Updated form action to:', formAction);
            
            // Fill form fields
            document.getElementById('editName').value = supplier.name;
            document.getElementById('editContactPerson').value = supplier.contact_person;
            document.getElementById('editEmail').value = supplier.email;
            document.getElementById('editPhone').value = supplier.phone;
            document.getElementById('editAddress').value = supplier.address || '';
            document.getElementById('editStatus').value = supplier.status;
            
            // Set selected categories
            const categorySelect = document.getElementById('editCategorySelect');
            if (supplier.categories && supplier.categories.length > 0) {
                const categoryIds = supplier.categories.map(cat => cat.id);
                categorySelect.value = categoryIds[0]; // Set first category
                console.log('Set category to:', categoryIds[0]);
            }
            
            // Show modal using Alpine.js
            const modalElement = document.querySelector('#editSupplierModal');
            if (modalElement) {
                const alpineData = Alpine.evaluate(modalElement, 'openEditModal()');
            }
            
            console.log('Opened modal successfully');
        } catch (error) {
            console.error('Detailed error:', error);
            alert(`Failed to load supplier data: ${error.message}`);
        }
    };

    // Handle edit form submission
    window.handleEditSubmit = function(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);

        console.log('Submitting form to:', form.action);
        console.log('Form data:', Object.fromEntries(formData));

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async response => {
            if (!response.ok) {
                const errorText = await response.text();
                console.error('Update failed:', {
                    status: response.status,
                    statusText: response.statusText,
                    responseText: errorText
                });
                throw new Error(`Failed to update: ${response.status} - ${errorText}`);
            }
            window.location.reload();
        })
        .catch(error => {
            console.error('Submission error:', error);
            alert(`Failed to update supplier: ${error.message}`);
        });

        return false;
    };

    // Add New Category functions
    window.addNewCategory = async function(mode = 'add') {
        const nameInput = document.getElementById(mode === 'edit' ? 'editNewCategoryName' : 'newCategoryName');
        const name = nameInput.value.trim();
        
        if (!name) {
            alert('Please enter a category name');
            return;
        }

        try {
            const response = await fetch('{{ route('admin.categories.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name })
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Failed to create category');
            }

            const data = await response.json();
            
            // Add new option to both selects
            const addSelect = document.getElementById('categorySelect');
            const editSelect = document.getElementById('editCategorySelect');
            
            // Add to both selects
            [addSelect, editSelect].forEach(select => {
                if (select) {
                    const option = new Option(data.name, data.id);
                    select.add(option);
                    
                    // Select the new option in the current modal
                    if ((mode === 'add' && select === addSelect) || 
                        (mode === 'edit' && select === editSelect)) {
                        select.value = data.id;
                    }
                }
            });

            // Clear input and hide the input field
            nameInput.value = '';
            
            // Use Alpine.js to update state
            const modal = document.querySelector('[x-data]').__x;
            if (mode === 'edit') {
                modal.$data.editCategoryInputVisible = false;
            } else {
                modal.$data.addCategoryInputVisible = false;
            }

        } catch (error) {
            alert(error.message || 'Failed to create category. Please try again.');
            console.error('Error:', error);
        }
    };

    // Allow Enter key to submit new category
    document.getElementById('newCategoryName')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addNewCategory('add');
        }
    });

    document.getElementById('editNewCategoryName')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addNewCategory('edit');
        }
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
        if (params.has('categories[]')) {
            let categories = params.getAll('categories[]');
            categories.forEach(category => {
                params.append('categories[]', category);
            });
        }
        
        // Redirect to new URL
        window.location.href = `${url.pathname}?${params.toString()}`;
    }
});
</script>
@endpush

@push('styles')
<style>
.supplier-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}
[x-cloak] { display: none !important; }
</style>
@endpush
@endsection
