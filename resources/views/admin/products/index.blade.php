@extends('admin.layouts.app')

@section('title', 'Products')
@section('page-title', 'Products Management')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i data-feather="package" class="w-6 h-6"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Products</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $products->total() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i data-feather="check-circle" class="w-6 h-6"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Active Products</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $products->where('deleted_at', null)->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <i data-feather="layers" class="w-6 h-6"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Variants</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $products->sum(function($p) { return $p->variants->count(); }) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                <i data-feather="plus" class="w-6 h-6"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">This Month</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $products->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Actions Bar -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div class="mb-4 sm:mb-0">
            <h3 class="text-lg font-medium text-gray-900">Products List</h3>
            <p class="text-sm text-gray-500">Manage your product catalog</p>
        </div>
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
            <a href="{{ route('admin.products.create') }}"
                class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                <i data-feather="plus" class="w-4 h-4 mr-2"></i>
                Add Product
            </a>
        </div>
    </div>
</div>

<!-- Products List -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <!-- Mobile Cards View -->
    <div class="block lg:hidden">
        @forelse($products as $product)
        <div class="p-6 border-b border-gray-200 last:border-b-0">
            <div class="flex items-start space-x-4">
                @if($product->image)
                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                @else
                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i data-feather="image" class="w-8 h-8 text-gray-400"></i>
                </div>
                @endif

                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="text-lg font-medium text-gray-900">{{ $product->name }}</h4>
                            <p class="text-sm text-gray-500 mt-1">{{ Str::limit($product->description, 80) }}</p>
                            <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                <span>ID: #{{ $product->id }}</span>
                                <span>•</span>
                                <span class="font-medium text-gray-900">RM{{ number_format($product->price, 2) }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col items-end space-y-2">
                            @if($product->deleted_at)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i data-feather="trash-2" class="w-3 h-3 mr-1"></i>
                                Deleted
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i data-feather="check" class="w-3 h-3 mr-1"></i>
                                Active
                            </span>
                            @endif

                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $product->variants->count() }} variant{{ $product->variants->count() !== 1 ? 's' : '' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <div class="flex items-center text-sm text-gray-500">
                            <div class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center mr-2">
                                <i data-feather="user" class="w-3 h-3 text-gray-600"></i>
                            </div>
                            <span>{{ $product->creator->name ?? 'Unknown' }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $product->created_at->format('M d, Y') }}</span>
                        </div>

                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.products.edit', $product) }}"
                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                <i data-feather="edit-3" class="w-3 h-3 mr-1"></i>
                                Edit
                            </a>
                            @if($product->deleted_at)
                            <div class="flex items-center space-x-2">
                                <form action="{{ route('admin.products.restore', $product) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-green-300 shadow-sm text-xs font-medium rounded text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                        <i data-feather="refresh-cw" class="w-3 h-3 mr-1"></i>
                                        Restore
                                    </button>
                                </form>
                                <form action="{{ route('admin.products.force-delete', $product) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to PERMANENTLY delete this product? This action cannot be undone and will remove all order history related to this product.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-red-600 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 transition-colors">
                                        <i data-feather="trash-2" class="w-3 h-3 mr-1"></i>
                                        Delete Forever
                                    </button>
                                </form>
                            </div>
                            @else
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                    <i data-feather="trash-2" class="w-3 h-3 mr-1"></i>
                                    Delete
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i data-feather="package" class="w-8 h-8 text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
                <p class="text-gray-500 mb-4">Get started by creating your first product</p>
                <a href="{{ route('admin.products.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <i data-feather="plus" class="w-4 h-4 mr-2"></i>
                    Create Product
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Desktop Table View -->
    <div class="hidden lg:block">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variants</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded-lg mr-4">
                                @else
                                <div class="w-12 h-12 bg-gray-200 rounded-lg mr-4 flex items-center justify-center">
                                    <i data-feather="image" class="w-6 h-6 text-gray-400"></i>
                                </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</div>
                                    <div class="text-xs text-gray-400">ID: #{{ $product->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">RM{{ number_format($product->price, 2) }}</div>
                            <!-- @if($product->variants->count() > 0)
                                        <div class="text-xs text-gray-500">
                                            {{ $product->variants->min('price') }} - {{ $product->variants->max('price') }}
                                        </div>
                                    @endif -->
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <span class="text-center inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $product->variants->count() }} variant{{ $product->variants->count() !== 1 ? 's' : '' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($product->deleted_at)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i data-feather="trash-2" class="w-3 h-3 mr-1"></i>
                                Deleted
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i data-feather="check" class="w-3 h-3 mr-1"></i>
                                Active
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                    <i data-feather="user" class="w-4 h-4 text-gray-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $product->creator->name ?? 'Unknown' }}</div>
                                    <div class="text-xs text-gray-500">{{ $product->created_at->format('M d, Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                    <i data-feather="edit-3" class="w-3 h-3 mr-1"></i>
                                    Edit
                                </a>
                                @if($product->deleted_at)
                                <div class="flex items-center space-x-2">
                                    <form action="{{ route('admin.products.restore', $product) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-green-300 shadow-sm text-xs font-medium rounded text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                            <i data-feather="refresh-cw" class="w-3 h-3 mr-1"></i>
                                            Restore
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.products.force-delete', $product) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to PERMANENTLY delete this product? This action cannot be undone and will remove all order history related to this product.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-red-600 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 transition-colors">
                                            <i data-feather="trash-2" class="w-3 h-3 mr-1"></i>
                                            Delete Forever
                                        </button>
                                    </form>
                                </div>
                                @else
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                        <i data-feather="trash-2" class="w-3 h-3 mr-1"></i>
                                        Delete
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i data-feather="package" class="w-8 h-8 text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
                                <p class="text-gray-500 mb-4">Get started by creating your first product</p>
                                <a href="{{ route('admin.products.create') }}"
                                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                    <i data-feather="plus" class="w-4 h-4 mr-2"></i>
                                    Create Product
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($products->hasPages())
    <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection