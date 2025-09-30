@extends('admin.layouts.app')

@section('title', 'QR Payment Settings')
@section('page-title', 'QR Payment Settings')

@section('content')
<div class="space-y-6">
    @livewire('admin.qr-payment-settings')
</div>
@endsection
