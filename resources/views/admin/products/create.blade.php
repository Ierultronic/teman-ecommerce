<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Create Product</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div class="flex items-center space-x-8">
                        <img src="{{ asset('images/logo.png') }}" alt="Store Logo" class="w-10 h-10 rounded-lg mr-3">
                        <h1 class="text-3xl font-bold text-gray-900">Admin Panel</h1>
                        <nav class="flex space-x-4">
                            <a href="{{ route('admin.products.index') }}" class="text-blue-600 font-medium">Products</a>
                            <a href="{{ route('admin.orders.index') }}" class="text-gray-600 hover:text-gray-900">Orders</a>
                        </nav>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('store.index') }}" class="text-gray-600 hover:text-gray-900">View Store</a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-900">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Create New Product</h3>
                </div>
                
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="px-4 py-5 sm:p-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Product Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Product Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3"
                                      class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Price *</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">RM</span>
                                </div>
                                <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" required
                                       class="pl-7 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">Product Image</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Variants Section -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Product Variants</label>
                            <div id="variants-container" class="space-y-3">
                                <!-- Variant template will be added here -->
                            </div>
                            <button type="button" onclick="addVariant()" 
                                    class="mt-3 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                + Add Variant
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <a href="{{ route('admin.products.index') }}" 
                           class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                            Create Product
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        let variantCount = 0;

        function addVariant() {
            const container = document.getElementById('variants-container');
            const variantDiv = document.createElement('div');
            variantDiv.className = 'flex space-x-3 p-3 border border-gray-200 rounded-md bg-gray-50';
            variantDiv.innerHTML = `
                <div class="flex-1">
                    <input type="text" name="variants[${variantCount}][variant_name]" placeholder="Variant name (e.g., Size, Color)" required
                           class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="w-24">
                    <input type="number" name="variants[${variantCount}][stock]" placeholder="Stock" min="0" required
                           class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button type="button" onclick="removeVariant(this)" 
                        class="text-red-600 hover:text-red-800 px-2 py-1">
                    Remove
                </button>
            `;
            container.appendChild(variantDiv);
            variantCount++;
        }

        function removeVariant(button) {
            button.parentElement.remove();
        }
    </script>
</body>
</html>
