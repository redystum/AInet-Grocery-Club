@extends('pages.layouts.admin')

@section('title', 'Supply Orders')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <livewire:supply-orders-table />
    </div>
@endsection
