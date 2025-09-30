<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - {{ $websiteSettings->shop_name }}</title>
    <link rel="icon" type="image/png" href="{{ $websiteSettings->favicon_url }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @livewireStyles
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }
        .sidebar-open {
            transform: translateX(0);
        }
        .sidebar-closed {
            transform: translateX(-100%);
        }
        @media (min-width: 768px) {
            .sidebar-closed {
                transform: translateX(0);
            }
        }
        /* Ensure consistent sidebar width and sticky positioning */
        #sidebar {
            width: 16rem; /* 256px - equivalent to w-64 */
            min-width: 16rem;
            max-width: 16rem;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
        }
        
        /* Ensure main content has proper margin for fixed sidebar */
        @media (min-width: 768px) {
            .main-content {
                margin-left: 16rem; /* 256px - same as sidebar width */
            }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div id="sidebar" class="fixed inset-y-0 left-0 z-50 bg-white shadow-lg sidebar-transition sidebar-closed md:translate-x-0">
            <!-- Logo and Brand -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <img src="{{ $websiteSettings->logo_url }}" alt="{{ $websiteSettings->shop_name }} Logo" class="w-10 h-10 rounded-lg">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $websiteSettings->shop_name }}</h1>
                        <p class="text-sm text-gray-500">Admin Panel</p>
                    </div>
                </div>
                <button id="closeSidebar" class="md:hidden text-gray-400 hover:text-gray-600">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="mt-6 px-3">
                <div class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-feather="home" class="w-5 h-5 mr-3"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-feather="package" class="w-5 h-5 mr-3"></i>
                        Products
                    </a>
                    <a href="{{ route('admin.orders.index') }}" 
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-feather="shopping-cart" class="w-5 h-5 mr-3"></i>
                        Orders
                    </a>
                    
                    <!-- Discounts & Promotions Section -->
                    <div class="mt-4">
                        <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Discounts & Promotions</p>
                        <div class="space-y-1 ml-2">
                            <a href="{{ route('admin.vouchers.index') }}" 
                               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.vouchers.index') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <i data-feather="tag" class="w-4 h-4 mr-3"></i>
                                Vouchers
                            </a>
                            <a href="{{ route('admin.discounts.index') }}" 
                               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.discounts.index') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <i data-feather="percent" class="w-4 h-4 mr-3"></i>
                                Discounts
                            </a>
                            <a href="{{ route('admin.promotions.index') }}" 
                               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.promotions.index') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <i data-feather="gift" class="w-4 h-4 mr-3"></i>
                                Promotions
                            </a>
                        </div>
                    </div>
                    
                    <!-- Settings Section -->
                    <div class="mt-4">
                        <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Settings</p>
                        <div class="space-y-1 ml-2">
                            <a href="{{ route('admin.branding.index') }}" 
                               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.branding.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <i data-feather="settings" class="w-4 h-4 mr-3"></i>
                                Branding
                            </a>
                        </div>
                    </div>
                    
                    <a href="{{ route('store.index') }}" 
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors text-gray-700 hover:bg-gray-50 hover:text-gray-900">
                        <i data-feather="external-link" class="w-5 h-5 mr-3"></i>
                        View Store
                    </a>
                </div>

                <!-- User Section -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center px-3 py-2">
                        <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                            <i data-feather="user" class="w-4 h-4 text-primary-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'Admin' }}</p>
                            <p class="text-xs text-gray-500">Administrator</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-lg transition-colors">
                            <i data-feather="log-out" class="w-4 h-4 mr-3"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col main-content">
            <!-- Top Bar -->
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-4 py-4 md:px-6">
                    <div class="flex items-center">
                        <button id="openSidebar" class="md:hidden text-gray-500 hover:text-gray-700 mr-3">
                            <i data-feather="menu" class="w-6 h-6"></i>
                        </button>
                        <h2 class="text-xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h2>
                    </div>

                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 p-4 md:p-6">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
                        <i data-feather="check-circle" class="w-5 h-5 mr-2 text-green-600"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center">
                        <i data-feather="alert-circle" class="w-5 h-5 mr-2 text-red-600"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-6 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg flex items-center">
                        <i data-feather="alert-triangle" class="w-5 h-5 mr-2 text-yellow-600"></i>
                        {{ session('warning') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden hidden"></div>

    <!-- Confirmation Modal Component -->
    <x-confirmation-modal />
    
    <!-- Receipt Modal Component -->
    <x-receipt-modal />

    <script>
        // Initialize Feather icons
        function initializeFeatherIcons() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            initializeFeatherIcons();
            
            // Sidebar toggle functionality
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const openSidebarBtn = document.getElementById('openSidebar');
            const closeSidebarBtn = document.getElementById('closeSidebar');

            function openSidebar() {
                sidebar.classList.remove('sidebar-closed');
                sidebar.classList.add('sidebar-open');
                sidebarOverlay.classList.remove('hidden');
            }

            function closeSidebar() {
                sidebar.classList.remove('sidebar-open');
                sidebar.classList.add('sidebar-closed');
                sidebarOverlay.classList.add('hidden');
            }

            openSidebarBtn.addEventListener('click', openSidebar);
            closeSidebarBtn.addEventListener('click', closeSidebar);
            sidebarOverlay.addEventListener('click', closeSidebar);

            // Close sidebar on window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('sidebar-closed', 'sidebar-open');
                    sidebarOverlay.classList.add('hidden');
                } else {
                    sidebar.classList.add('sidebar-closed');
                }
            });
            
            // Initialize sidebar state based on screen size
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('sidebar-closed', 'sidebar-open');
            } else {
                sidebar.classList.add('sidebar-closed');
            }
        });

        // Initialize Feather icons for Livewire
        document.addEventListener('livewire:init', () => {
            // Initialize icons after initial render
            initializeFeatherIcons();
            
            // Initialize icons after any Livewire updates
            Livewire.hook('morph.updated', ({ component }) => {
                setTimeout(initializeFeatherIcons, 10);
            });
        });

        // Backup initialization for any missed cases
        document.addEventListener('livewire:navigated', initializeFeatherIcons);
    </script>
    @livewireScripts
</body>
</html>
