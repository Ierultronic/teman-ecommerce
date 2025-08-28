@extends('admin.layouts.app')

@section('title', 'Admin')
@section('page-title', 'Welcome to Admin Panel')

@section('content')
    <div class="text-center py-12">
        <div class="w-24 h-24 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i data-feather="shield" class="w-12 h-12 text-primary-600"></i>
        </div>
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Welcome to TEMAN Admin Panel</h2>
        <p class="text-lg text-gray-600 mb-8">Manage your products, orders, and store settings from here.</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center px-6 py-3 bg-primary-600 text-white text-lg font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                <i data-feather="home" class="w-5 h-5 mr-2"></i>
                Go to Dashboard
            </a>
            
            <a href="{{ route('admin.products.index') }}" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 text-lg font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                <i data-feather="package" class="w-5 h-5 mr-2"></i>
                Manage Products
            </a>
        </div>
    </div>
@endsection
