<aside class="sidebar w-56 bg-white shadow-md flex-shrink-0">
    <div class="p-4 flex items-center justify-center space-x-2">
        <img src="{{ asset('images/Logo2.png') }}" alt="Logo 1" class="h-10">
    </div>
    <div class="mt-6">
        <ul>
            <li class="px-3 py-2">
                <a href="#" class="text-gray-700 text-sm">
                    MAIN
                </a>
            </li>
            <li class="sidebar-item px-3 py-4 {{ Route::is('dashboard') ? 'active text-primary font-medium' : '' }}">
                <a href="{{ route('dashboard') }}" class="flex items-center {{ Route::is('dashboard') ? 'text-primary font-medium' : 'text-gray-700' }}">
                    <div class="w-6 h-6 flex items-center justify-center mr-3">
                        <i class="ri-dashboard-line"></i>
                    </div>
                    <span class="text-lg">Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item px-3 py-4 {{ Route::is('inventory') ? 'active text-primary font-medium' : '' }}">
                <a href="{{ route('inventory') }}" class="flex items-center {{ Route::is('inventory') ? 'text-primary font-medium' : 'text-gray-700' }}">
                    <div class="w-6 h-6 flex items-center justify-center mr-3">
                        <i class="ri-store-2-line"></i>
                    </div>
                    <span class="text-lg">Inventory</span>
                </a>
            </li>
            <li class="sidebar-item px-3 py-4 {{ Route::is('orders') ? 'active text-primary font-medium' : '' }}">
                <a href="{{ route('orders') }}" class="flex items-center {{ Route::is('orders') ? 'text-primary font-medium' : 'text-gray-700' }}">
                    <div class="w-6 h-6 flex items-center justify-center mr-3">
                        <i class="ri-shopping-cart-line"></i>
                    </div>
                    <span class="text-lg">Orders</span>
                </a>
            </li>
            <li class="sidebar-item px-3 py-4 {{ Route::is('suppliers') ? 'active text-primary font-medium' : '' }}">
                <a href="{{ route('suppliers') }}" class="flex items-center {{ Route::is('suppliers') ? 'text-primary font-medium' : 'text-gray-700' }}">
                    <div class="w-6 h-6 flex items-center justify-center mr-3">
                        <i class="ri-user-star-line"></i>
                    </div>
                    <span class="text-lg">Suppliers</span>
                </a>
            </li>
            <li class="sidebar-item px-3 py-4 {{ Route::is('analytics') ? 'active text-primary font-medium' : '' }}">
                <a href="{{ route('analytics') }}" class="flex items-center {{ Route::is('analytics') ? 'text-primary font-medium' : 'text-gray-700' }}">
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
    border: 1px solid #e2e8f0; /* Added outline */
}
.sidebar-item {
    transition: all 0.2s ease;
}
.sidebar-item:hover {
    background-color: rgba(99, 102, 241, 0.1); /* indigo-500 */
}
.sidebar-item.active {
    background-color: rgba(99, 102, 241, 0.15); /* indigo-500 */
    border-left: 3px solid #6366F1; /* indigo-500 */
}
/* Override Tailwind's text-primary if needed */
.text-primary {
    color: #6366F1 !important; /* indigo-500 */
}
</style>
