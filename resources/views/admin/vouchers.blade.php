@extends('admin.layouts.app')

@section('title', 'Vouchers')
@section('page-title', 'Voucher Management')

@section('content')
<div class="space-y-6">
    @livewire('admin.voucher-management')
</div>
@endsection
