@extends('pages.layouts.admin')

@section('title', 'Add New Product')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">Add New Product</h1>
            <a href="{{ route('board.stock') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Products
            </a>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
            <form method="POST" action="{{ route('board.stock.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Basic Information Section -->
                <div class="border-b border-neutral-200 dark:border-neutral-700 pb-6">
                    <h2 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100 mb-4">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Product Name *</label>
                            <input type="text" name="name" required
                                   class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Category *</label>
                            <select name="category_id" required
                                    class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Price (€) *</label>
                            <input type="number" name="price" step="0.01" min="0" required
                                   class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Initial Stock *</label>
                            <input type="number" name="stock" min="0" required
                                   class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Description</label>
                            <textarea name="description" rows="3" maxlength="255"
                                      class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Product Image Section -->
                <div class="border-b border-neutral-200 dark:border-neutral-700 pb-6">
                    <h2 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100 mb-4">Product Image</h2>
                    <div class="flex flex-col sm:flex-row items-start gap-6">
                        <div class="w-40 h-40 rounded-lg bg-neutral-100 dark:bg-neutral-700 flex items-center justify-center overflow-hidden">
                            <div id="image-preview" class="hidden w-full h-full">
                                <img id="preview-image" class="w-full h-full object-cover" src="#" alt="Preview">
                            </div>
                            <div id="image-placeholder" class="text-neutral-400 dark:text-neutral-500 text-center p-4">
                                <i class="fas fa-camera text-3xl mb-2"></i>
                                <p class="text-sm">No image selected</p>
                            </div>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Upload Product Photo</label>
                            <input type="file" name="photo" id="photo-input" accept="image/*"
                                   class="block w-full text-sm text-neutral-600 dark:text-neutral-400
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-lg file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-blue-50 dark:file:bg-neutral-700 file:text-blue-700 dark:file:text-blue-400
                                      hover:file:bg-blue-100 dark:hover:file:bg-neutral-600">
                            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">JPG, PNG or GIF (Max 2MB)</p>
                        </div>
                    </div>
                </div>

                <!-- Discount Settings Section -->
                <div class="border-b border-neutral-200 dark:border-neutral-700 pb-6">
                    <h2 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100 mb-4">Discount Settings</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Minimum Quantity for Discount</label>
                            <input type="number" name="discount_min_qty" min="1"
                                   class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Discount Amount (€)</label>
                            <input type="number" name="discount" step="0.01" min="0"
                                   class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                        </div>
                    </div>
                </div>

                <!-- Stock Management Section -->
                <div class="pb-6">
                    <h2 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100 mb-4">Stock Management</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Stock Lower Limit</label>
                            <input type="number" name="stock_lower_limit" min="0"
                                   class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Alert when stock reaches this level</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Stock Upper Limit</label>
                            <input type="number" name="stock_upper_limit" min="0"
                                   class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Maximum desired stock level</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end gap-4 pt-4">
                    <button type="reset" class="px-6 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-700 dark:text-neutral-200 hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                        Reset
                    </button>
                    <button type="submit" class="cursor-pointer px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-plus-circle mr-2"></i> Add Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Image preview functionality
        document.getElementById('photo-input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('preview-image');
            const previewContainer = document.getElementById('image-preview');
            const placeholder = document.getElementById('image-placeholder');

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }

                reader.readAsDataURL(file);
            } else {
                previewContainer.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        });
    </script>
@endsection