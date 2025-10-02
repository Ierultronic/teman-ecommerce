@extends('admin.layouts.app')

@section('title', 'Categories')
@section('page-title', 'Categories Management' . (request('name') || request('status') ? ' (Filtered)' : ''))

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i data-feather="folder" class="w-6 h-6"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Filtered Results</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $categories->total() }}</p>
                @if(request('name') || request('status'))
                <p class="text-xs text-blue-600">Showing filtered results</p>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i data-feather="check-circle" class="w-6 h-6"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Active Categories</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $categories->where('deleted_at', null)->where('is_active', true)->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <i data-feather="package" class="w-6 h-6"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Products</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $categories->sum('products_count') }}</p>
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
                <p class="text-2xl font-semibold text-gray-900">{{ $categories->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Actions Bar -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div class="mb-4 sm:mb-0">
            <h3 class="text-lg font-medium text-gray-900">Categories List</h3>
            <p class="text-sm text-gray-500">Manage your product categories</p>
        </div>
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
            <a href="{{ route('admin.categories.create') }}"
                class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                <i data-feather="plus" class="w-4 h-4 mr-2"></i>
                Add Category
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <form method="GET" action="{{ route('admin.categories.index') }}" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Name Filter -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                <input type="text"
                    name="name"
                    id="name"
                    value="{{ request('name') }}"
                    placeholder="Search by category name..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status"
                    id="status"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>All Categories</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                    <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted Only</option>
                </select>
            </div>

            <!-- Filter Actions -->
            <div class="flex items-end space-x-2">
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <i data-feather="filter" class="w-4 h-4 inline mr-1"></i>
                    Apply Filters
                </button>
                <a href="{{ route('admin.categories.index') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    <i data-feather="x" class="w-4 h-4 inline"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Categories Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Order
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Category
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Slug
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Products
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Created
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($categories as $category)
                <tr class="hover:bg-gray-50 {{ $category->deleted_at ? 'bg-red-50' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $category->sort_order }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($category->image)
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                            </div>
                            @else
                            <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                <i data-feather="folder" class="w-5 h-5 text-gray-500"></i>
                            </div>
                            @endif
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                @if($category->description)
                                <div class="text-xs text-gray-500">{{ Str::limit($category->description, 50) }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $category->slug }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $category->products_count }} products
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($category->deleted_at)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <i data-feather="trash-2" class="w-3 h-3 mr-1"></i>
                            Deleted
                        </span>
                        @elseif($category->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i data-feather="check-circle" class="w-3 h-3 mr-1"></i>
                            Active
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            <i data-feather="x-circle" class="w-3 h-3 mr-1"></i>
                            Inactive
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $category->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            @if($category->deleted_at)
                            <!-- Restore Button -->
                            <form action="{{ route('admin.categories.restore', $category->slug) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="text-green-600 hover:text-green-900"
                                    onclick="return confirm('Are you sure you want to restore this category?')"
                                    title="Restore">
                                    <i data-feather="rotate-ccw" class="w-4 h-4"></i>
                                </button>
                            </form>
                            <!-- Force Delete Button -->
                            <form action="{{ route('admin.categories.force-delete', $category->slug) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Are you sure you want to permanently delete this category? This action cannot be undone!')"
                                    title="Permanently Delete">
                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                            @else
                            <!-- Edit Button -->
                            <a href="{{ route('admin.categories.edit', $category->slug) }}"
                                class="text-primary-600 hover:text-primary-900"
                                title="Edit">
                                <i data-feather="edit" class="w-4 h-4"></i>
                            </a>
                            <!-- Delete Button -->
                            <form action="{{ route('admin.categories.destroy', $category->slug) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Are you sure you want to delete this category?')"
                                    title="Delete">
                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i data-feather="folder" class="w-12 h-12 text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No categories found</h3>
                            <p class="text-sm text-gray-500 mb-4">Get started by creating your first category.</p>
                            <a href="{{ route('admin.categories.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                <i data-feather="plus" class="w-4 h-4 mr-2"></i>
                                Add Category
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection

