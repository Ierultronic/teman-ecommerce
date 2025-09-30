@extends('admin.layouts.app')

@section('title', 'Branding Settings')
@section('page-title', 'Branding Settings')

@section('content')
<div class="space-y-6">
    @livewire('admin.branding-settings')
</div>
@endsection
