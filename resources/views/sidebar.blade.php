<aside class="sidebar fixed h-screen overflow-y-auto w-56 bg-white shadow-md flex-shrink-0">
    <div class="p-4 flex items-center justify-center space-x-2">
        <img src="{{ asset('images/Logo2.png') }}" alt="Logo 1" class="h-15">
    </div>
    <div class="mt-6">
        <ul>
            <li class="px-3 py-2">
                <a href="#" class="text-gray-700 text-sm">
                    MAIN
                </a>
            </li>
            <li class="sidebar-item px-3 py-4 {{ Route::is('admin.dashboard') ? 'active text-primary font-medium' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center {{ Route::is('admin.dashboard') ? 'text-primary font-medium' : 'text-gray-700' }}">
                    <div class="w-6 h-6 flex items-center justify-center mr-3">
                        <i class="ri-dashboard-line"></i>
                    </div>
                    <span class="text-lg">Dashboard</span>
                </a>
            </li>
            
            <!-- Inventory Section with Submenu -->
            <div x-data="{ inventoryOpen: {{ Route::is('admin.inventory') || Route::is('admin.stock.*') ? 'true' : 'false' }} }">
                <li class="sidebar-item px-3 py-4 {{ Route::is('admin.inventory') || Route::is('admin.stock.*') ? 'active text-primary font-medium' : '' }}">
                    <button @click="inventoryOpen = !inventoryOpen" 
                            class="flex items-center justify-between w-full {{ Route::is('admin.inventory') || Route::is('admin.stock.*') ? 'text-primary font-medium' : 'text-gray-700' }}">
                        <div class="flex items-center">
                            <div class="w-6 h-6 flex items-center justify-center mr-3">
                                <i class="ri-store-2-line"></i>
                            </div>
                            <span class="text-lg">Inventory</span>
                        </div>
                        <i class="ri-arrow-down-s-line transition-transform" :class="{ 'transform rotate-180': inventoryOpen }"></i>
                    </button>
                </li>
                
                <!-- Submenu -->
                <div x-show="inventoryOpen" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform -translate-y-2"
                     class="pl-12">
                    <a href="{{ route('admin.inventory') }}" 
                       class="block py-3 px-2 text-gray-700 hover:text-primary transition-colors {{ Route::is('admin.inventory') ? 'text-primary font-medium' : '' }}">
                        Overview
                    </a>
                    <a href="{{ route('admin.stock.in') }}" 
                       class="block py-3 px-2 text-gray-700 hover:text-primary transition-colors {{ Route::is('admin.stock.in') ? 'text-primary font-medium' : '' }}">
                        Stock In
                    </a>
                    <a href="{{ route('admin.stock.out') }}" 
                       class="block py-3 px-2 text-gray-700 hover:text-primary transition-colors {{ Route::is('admin.stock.out') ? 'text-primary font-medium' : '' }}">
                        Stock Out
                    </a>
                </div>
            </div>

            <li class="sidebar-item px-3 py-4 {{ Route::is('admin.orders') ? 'active text-primary font-medium' : '' }}">
                <a href="{{ route('admin.orders') }}" class="flex items-center {{ Route::is('admin.orders') ? 'text-primary font-medium' : 'text-gray-700' }}">
                    <div class="w-6 h-6 flex items-center justify-center mr-3">
                        <i class="ri-shopping-cart-line"></i>
                    </div>
                    <span class="text-lg">Orders</span>
                </a>
            </li>
            <li class="sidebar-item px-3 py-4 {{ Route::is('admin.suppliers') ? 'active text-primary font-medium' : '' }}">
                <a href="{{ route('admin.suppliers') }}" class="flex items-center {{ Route::is('admin.suppliers') ? 'text-primary font-medium' : 'text-gray-700' }}">
                    <div class="w-6 h-6 flex items-center justify-center mr-3">
                        <i class="ri-user-star-line"></i>
                    </div>
                    <span class="text-lg">Suppliers</span>
                </a>
            </li>
            <li class="sidebar-item px-3 py-4 {{ Route::is('admin.analytics') ? 'active text-primary font-medium' : '' }}">
                <a href="{{ route('admin.analytics') }}" class="flex items-center {{ Route::is('admin.analytics') ? 'text-primary font-medium' : 'text-gray-700' }}">
                    <div class="w-6 h-6 flex items-center justify-center mr-3">
                        <i class="ri-bar-chart-line"></i>
                    </div>
                    <span class="text-lg">Analytics</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<style>
body {
    font-family: 'Inter', sans-serif;
    background-color: #f8fafc;
}
.sidebar {
    transition: all 0.3s ease;
    background-color: #6366F1;  /* Indigo-500 - lighter than previous */
}
.sidebar-item {
    transition: all 0.2s ease;
}
.sidebar-item:hover {
    background-color: #A5B4FC;  /* Indigo-300 - lighter hover state */
    color: #1F2937;
}
.sidebar-item.active {
    background-color: #4F46E5;  /* Previous primary color now as active state */
    border-left: 3px solid #C7D2FE;  /* Indigo-200 - lighter border */
}
/* Text colors */
.sidebar a, .sidebar button {
    color: #F3F4F6;
}
.sidebar .text-gray-700 {
    color: #F3F4F6;
}
.text-primary {
    color: #fff !important;
}
/* Submenu styles */
.sidebar .pl-12 a {
    font-size: 0.95rem;
    opacity: 0.9;
}
.sidebar .pl-12 a:hover {
    opacity: 1;
    background-color: #A5B4FC;
    color: #1F2937;
}
.sidebar .pl-12 a.text-primary {
    opacity: 1;
}
</style>
