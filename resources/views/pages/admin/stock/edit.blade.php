@extends('pages.layouts.admin')

@section('title', 'Edit Product')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">Edit Product</h1>
            <a href="{{ route('board.stock') }}"
               class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Products
            </a>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
            <form method="POST" action="{{ route('board.stock.update', $product->id) }}" enctype="multipart/form-data"
                  class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Information Section -->
                <div class="border-b border-neutral-200 dark:border-neutral-700 pb-6">
                    <h2 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100 mb-4">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Product
                                Name *</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                                   class="w-full px-4 py-2 border @error('name') border-red-500 @else border-neutral-300 dark:border-neutral-600 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                            @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Category
                                *</label>
                            <select name="category_id" required
                                    class="w-full px-4 py-2 border @error('category_id') border-red-500 @else border-neutral-300 dark:border-neutral-600 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ (old('category_id', $product->category_id) == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Price
                                (€) *</label>
                            <input type="number" name="price" step="0.01" min="0"
                                   value="{{ old('price', $product->price) }}" required
                                   class="w-full px-4 py-2 border @error('price') border-red-500 @else border-neutral-300 dark:border-neutral-600 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                            @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Current
                                Stock *</label>
                            <input type="number" name="stock" min="0" value="{{ old('stock', $product->stock) }}"
                                   required
                                   class="w-full px-4 py-2 border @error('stock') border-red-500 @else border-neutral-300 dark:border-neutral-600 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                            @error('stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Description</label>
                            <textarea name="description" rows="3" maxlength="255"
                                      class="w-full px-4 py-2 border @error('description') border-red-500 @else border-neutral-300 dark:border-neutral-600 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Product Image Section -->
                <div class="border-b border-neutral-200 dark:border-neutral-700 pb-6">
                    <h2 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100 mb-4">Product Image</h2>
                    <div class="flex flex-col sm:flex-row items-start gap-6">
                        <div class="w-40 h-40 rounded-lg bg-neutral-100 dark:bg-neutral-700 flex items-center justify-center overflow-hidden">
                            <img id="preview-image" src="{{ asset('storage/products/' . $product->photo) }}"
                                 alt="Current Product Image" class="{{ $product->photo ? 'w-full h-full' : 'hidden' }} object-cover">
                            <div id="image-placeholder"
                                 class="{{ $product->photo ? 'hidden' : 'text-neutral-400 dark:text-neutral-500 text-center p-4' }}">
                                <i class="fas fa-image text-3xl mb-2"></i>
                                <p class="text-sm">No image selected</p>
                            </div>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Update
                                Product Photo</label>
                            <input type="file" name="photo" id="photo-input" accept="image/*"
                                   class="block w-full text-sm @error('photo') text-red-500 @else text-neutral-600 dark:text-neutral-400 @enderror
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-lg file:border-0
                                      file:text-sm file:font-semibold
                                      @error('photo') file:bg-red-100 file:text-red-700 @else file:bg-blue-50 dark:file:bg-neutral-700 file:text-blue-700 dark:file:text-blue-400 @enderror
                                      @error('photo') hover:file:bg-red-200 @else hover:file:bg-blue-100 dark:hover:file:bg-neutral-600 @enderror">
                            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">JPG, PNG or GIF (Max 2MB)</p>
                            @error('photo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            @if($product->photo)
                                <div class="mt-2 flex items-center">
                                    <input type="checkbox" name="remove_photo" id="remove_photo" class="mr-2">
                                    <label for="remove_photo" class="text-sm text-neutral-600 dark:text-neutral-400">Remove
                                        current image</label>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Discount Settings Section -->
                <div class="border-b border-neutral-200 dark:border-neutral-700 pb-6">
                    <h2 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100 mb-4">Discount Settings</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Minimum
                                Quantity for Discount</label>
                            <input type="number" name="discount_min_qty" min="1"
                                   value="{{ old('discount_min_qty', $product->discount_min_qty) }}"
                                   class="w-full px-4 py-2 border @error('discount_min_qty') border-red-500 @else border-neutral-300 dark:border-neutral-600 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                            @error('discount_min_qty')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Discount
                                Amount (€)</label>
                            <input type="number" name="discount" step="0.01" min="0"
                                   value="{{ old('discount', $product->discount) }}"
                                   class="w-full px-4 py-2 border @error('discount') border-red-500 @else border-neutral-300 dark:border-neutral-600 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                            @error('discount')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Stock Management Section -->
                <div class="pb-6">
                    <h2 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100 mb-4">Stock Management</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Stock
                                Lower Limit</label>
                            <input type="number" name="stock_lower_limit" min="0"
                                   value="{{ old('stock_lower_limit', $product->stock_lower_limit) }}"
                                   class="w-full px-4 py-2 border @error('stock_lower_limit') border-red-500 @else border-neutral-300 dark:border-neutral-600 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Alert when stock reaches this
                                level</p>
                            @error('stock_lower_limit')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Stock
                                Upper Limit</label>
                            <input type="number" name="stock_upper_limit" min="0"
                                   value="{{ old('stock_upper_limit', $product->stock_upper_limit) }}"
                                   class="w-full px-4 py-2 border @error('stock_upper_limit') border-red-500 @else border-neutral-300 dark:border-neutral-600 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Maximum desired stock
                                level</p>
                            @error('stock_upper_limit')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end gap-4 pt-4">
                    <a href="{{ route('board.stock') }}"
                       class="cursor-pointer px-6 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-700 dark:text-neutral-200 hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                            class="cursor-pointer px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i> Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Image preview functionality
        document.getElementById('photo-input')?.addEventListener('change', function (e) {
            const file = e.target.files[0];
            const preview = document.getElementById('preview-image');
            const placeholder = document.getElementById('image-placeholder');

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    if (!preview) {
                        // Create new preview image if it doesn't exist
                        const previewContainer = document.createElement('img');
                        previewContainer.id = 'preview-image';
                        previewContainer.className = 'w-full h-full object-cover';
                        previewContainer.src = e.target.result;
                        document.querySelector('.w-40.h-40').prepend(previewContainer);
                    } else {
                        preview.src = e.target.result;
                    }

                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                }

                reader.readAsDataURL(file);
            }
        });

        // Handle remove photo checkbox
        document.getElementById('remove_photo')?.addEventListener('change', function (e) {
            const preview = document.getElementById('preview-image');
            const placeholder = document.getElementById('image-placeholder');

            if (e.target.checked) {
                if (preview) preview.classList.add('hidden');
                if (placeholder) placeholder.classList.remove('hidden');
            } else {
                if (preview) preview.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
            }
        });
    </script>
@endsection
