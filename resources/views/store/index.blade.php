<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Teman</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
    @livewireStyles
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div class="flex items-center">
                        <!-- Store Logo -->
                        <img src="/images/logo.png" alt="Store Logo" class="w-10 h-10 mr-3 rounded-lg">
                        <h1 class="text-3xl font-bold text-gray-900">Teman</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">Admin Panel</a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @livewire('store-page')
        </main>
    </div>

    @livewireScripts
</body>
</html>
