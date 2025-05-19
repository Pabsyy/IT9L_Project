@extends('layouts.app')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Content -->
        <div class="flex-1 overflow-auto p-6">
            <!-- Breadcrumb -->
            <div class="flex items-center mb-6">
                <a href="{{ route('admin.inventory') }}" class="text-gray-600 hover:text-primary">
                    <i class="ri-store-2-line mr-1"></i>
                    Inventory
                </a>
                <i class="ri-arrow-right-s-line mx-2 text-gray-400"></i>
                <span class="text-gray-800">Stock Out</span>
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

            <!-- Stock Out Form -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Remove Stock</h2>
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-500">
                        <i class="ri-arrow-down-circle-line text-xl"></i>
                    </div>
                </div>
                
                <form action="{{ route('admin.stock.process-out') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Product Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                            <select name="product_id" class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                                <option value="">Select a product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }} (Available Stock: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Reference Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reference Number</label>
                            <input type="text" name="reference_number" class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-primary/30 focus:border-primary" placeholder="SO-2024-001">
                        </div>

                        <!-- Reason -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                            <select name="reason" class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                                <option value="">Select a reason</option>
                                <option value="sale">Sale</option>
                                <option value="damage">Damage/Loss</option>
                                <option value="return">Return to Supplier</option>
                                <option value="adjustment">Inventory Adjustment</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <!-- Quantity -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                            <div class="flex items-center">
                                <input type="number" name="quantity" min="1" max="99999" class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                                <div class="ml-2 text-gray-500">
                                    <i class="ri-shopping-basket-2-line"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea name="notes" rows="1" class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-primary/30 focus:border-primary" placeholder="Optional notes about this stock removal"></textarea>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button type="submit" class="bg-primary text-white px-6 py-2 rounded font-medium hover:bg-primary/90 transition-colors flex items-center justify-center">
                            <i class="ri-subtract-line mr-2"></i>
                            Remove Stock
                        </button>
                    </div>
                </form>
            </div>

            <!-- Recent Stock Movements -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Recent Stock Removals</h2>
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                        <i class="ri-history-line text-xl"></i>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($recentMovements as $movement)
                        <div class="border border-gray-100 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-medium text-gray-800">
                                    {{ $movement->product ? $movement->product->name : 'Deleted Product' }}
                                </span>
                                <span class="text-red-600 flex items-center">
                                    <i class="ri-arrow-down-circle-fill mr-1"></i>
                                    {{ $movement->quantity }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span>{{ $movement->moved_at->diffForHumans() }}</span>
                                <span class="text-gray-400">by {{ $movement->user ? $movement->user->name : 'Unknown User' }}</span>
                            </div>
                            @if($movement->reference_number || $movement->notes)
                                <div class="mt-2 text-sm">
                                    @if($movement->reference_number)
                                        <span class="text-blue-600 bg-blue-50 px-2 py-1 rounded-full text-xs">
                                            {{ $movement->reference_number }}
                                        </span>
                                    @endif
                                    @if($movement->type !== 'sale')
                                        <span class="text-orange-600 bg-orange-50 px-2 py-1 rounded-full text-xs ml-1">
                                            {{ ucfirst($movement->type) }}
                                        </span>
                                    @endif
                                    @if($movement->notes)
                                        <span class="text-gray-400 block mt-1 truncate max-w-[200px]" title="{{ $movement->notes }}">
                                            {{ Str::limit($movement->notes, 30) }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-8">
                            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                                <i class="ri-inbox-line text-2xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-500">No recent stock removals</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Close alert functionality
    document.addEventListener('DOMContentLoaded', function() {
        const closeSuccess = document.getElementById('closeSuccessAlert');
        const closeError = document.getElementById('closeErrorAlert');
        const successAlert = document.getElementById('successAlert');
        const errorAlert = document.getElementById('errorAlert');

        if (closeSuccess && successAlert) {
            closeSuccess.addEventListener('click', function() {
                successAlert.style.display = 'none';
            });
        }

        if (closeError && errorAlert) {
            closeError.addEventListener('click', function() {
                errorAlert.style.display = 'none';
            });
        }
    });
</script>
@endpush

@endsection 