<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Include Alpine.js -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <!-- Add ECharts -->
        <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>

        <style>
            .date-picker-dropdown,
            .status-dropdown-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                margin-top: 0.5rem;
                z-index: 50;
                box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            }

            .date-picker.active .date-picker-dropdown,
            .status-dropdown.active .status-dropdown-menu {
                display: block;
            }
        </style>

        @stack('styles')
    </head>
    <body class="font-sans antialiased m-0 p-0">
        <div class="min-h-screen bg-gray-100 flex">
            <!-- Sidebar -->
            @include('sidebar', ['activeRoute' => Route::currentRouteName()])

            <!-- Main Content Wrapper -->
            <div class="flex-1 flex flex-col ml-56">
                @php
                    $title = '';
                    switch (Route::currentRouteName()) {
                        case 'dashboard':
                            $title = 'Dashboard Overview';
                            break;
                        case 'inventory':
                            $title = 'Inventory Management';
                            break;
                        case 'orders':
                            $title = 'Orders & Transactions';
                            break;
                        case 'suppliers':
                            $title = 'Suppliers Management ';
                            break;
                        case 'analytics':
                            $title = 'Analytics & Reports Management';
                            break;
                        default:
                            $title = 'Admin Management';
                            break;
                    }
                @endphp

                <!-- Fixed Header -->
                <div class="sticky top-0 z-10">
                    @include('partials.header', ['title' => $title])
                </div>

                <!-- Page Content -->
                <main id="main-content" class="flex-1 p-6">
                    @yield('content')
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
