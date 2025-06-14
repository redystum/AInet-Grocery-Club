@extends('pages.layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-2xl font-bold mb-6">Edit Product</h1>
    <form method="POST" action="{{ route('board.stock.update', $product->id) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block mb-1">Name</label>
            <input type="text" name="name" value="{{ $product->name }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block mb-1">Category</label>
            <select name="category_id" class="w-full border rounded px-3 py-2" required>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @if($product->category_id == $cat->id) selected @endif>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block mb-1">Price (€)</label>
            <input type="number" name="price" step="0.01" min="0" value="{{ $product->price }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block mb-1">Stock</label>
            <input type="number" name="stock" min="0" value="{{ $product->stock }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block mb-1">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2">{{ $product->description }}</textarea>
        </div>
        <div>
            <label class="block mb-1">Photo</label>
            <input type="file" name="photo" class="w-full">
            @if($product->photo)
                <img src="{{ asset('storage/products/' . $product->photo) }}" alt="Current Photo" class="mt-2 w-24 h-24 object-cover rounded">
            @endif
        </div>
        <div>
            <label class="block mb-1">Discount Min Qty</label>
            <input type="number" name="discount_min_qty" min="1" value="{{ $product->discount_min_qty }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block mb-1">Discount (€)</label>
            <input type="number" name="discount" step="0.01" min="0" value="{{ $product->discount }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block mb-1">Stock Lower Limit</label>
            <input type="number" name="stock_lower_limit" min="0" value="{{ $product->stock_lower_limit }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block mb-1">Stock Upper Limit</label>
            <input type="number" name="stock_upper_limit" min="0" value="{{ $product->stock_upper_limit }}" class="w-full border rounded px-3 py-2">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Save Changes</button>
    </form>
</div>
@endsection