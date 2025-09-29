@extends('admin.layouts.app')

@section('title', 'Discounts')
@section('page-title', 'Discount Management')

@section('content')
<div class="space-y-6">
    @livewire('admin.discount-management')
</div>
@endsection
