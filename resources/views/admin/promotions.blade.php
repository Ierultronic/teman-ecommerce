@extends('admin.layouts.app')

@section('title', 'Promotions')
@section('page-title', 'Promotion Management')

@section('content')
<div class="space-y-6">
    @livewire('admin.promotion-management')
</div>
@endsection
