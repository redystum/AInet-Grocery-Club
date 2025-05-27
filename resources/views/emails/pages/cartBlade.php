@extends('layout')

@section('title', ' - Shopping Cart')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-2xl font-bold mb-6">Your Shopping Cart</h1>
    @if($cart && count($cart) > 0)
        @foreach($cart as $id => $item)
            <div class="flex items-center mb-4">
                <img src="{{ asset('storage/products/' . $item['photo']) }}" class="w-16 h-16 rounded mr-4">
                <div class="flex-1">
                    <div class="font-medium">{{ $item['name'] }}</div>
                    <div class="text-sm text-gray-500">Qty: {{ $item['quantity'] }}</div>
                </div>
                <div class="font-bold">€{{ number_format($item['price'], 2) }}</div>
                <form action="{{ route('cart.remove') }}" method="POST" class="ml-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $id }}">
                    <button class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        @endforeach
        <a href="#" class="block w-full bg-blue-600 text-white text-center py-2 rounded mt-4">Checkout</a>
    @else
        <p class="text-gray-500 text-center mt-8">Your cart is empty.</p>
    @endif
</div>
@endsection