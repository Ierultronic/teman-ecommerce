@extends('admin.layouts.app')

@section('title', 'Create Product')

@section('page-title', 'Create New Product')

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Product Information</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Enter the details for your new product</p>
    </div>
    
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="px-4 py-5 sm:p-6">
        @csrf
        
        <div class="grid grid-cols-1 gap-6">
            <!-- Product Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Product Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3"
                          class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-primary-500 focus:border-primary-500">{{ old('description') }}</textarea>
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
                           class="pl-12 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                </div>
                @error('price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Product Image</label>
                <input type="file" name="image" id="image" accept="image/*"
                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
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
                        class="mt-3 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <i data-feather="plus" class="w-4 h-4 mr-2"></i>
                    Add Variant
                </button>
            </div>
        </div>

        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ route('admin.products.index') }}" 
               class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="text-white px-4 py-2 rounded-md bg-primary-600 hover:bg-primary-700 transition-colors">
                <i data-feather="save" class="w-4 h-4 mr-2 inline"></i>
                Create Product
            </button>
        </div>
    </form>
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
                       class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div class="w-24">
                <input type="number" name="variants[${variantCount}][stock]" placeholder="Stock" min="0" required
                       class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
            </div>
            <button type="button" onclick="removeVariant(this)" 
                    class="text-red-600 hover:text-red-800 px-2 py-1">
                <i data-feather="trash-2" class="w-4 h-4"></i>
            </button>
        `;
        container.appendChild(variantDiv);
        variantCount++;
        
        // Reinitialize Feather icons for the new variant
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }

    function removeVariant(button) {
        button.parentElement.remove();
    }
</script>
@endsection
