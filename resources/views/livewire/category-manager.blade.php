<div>
    <!-- Edit Category Modal -->
    @if($editingCategoryId)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-dvh pt-4 px-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black/65 transition-opacity" aria-hidden="true"
                 wire:click="cancelEditing"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="updateCategory">
                    <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/50 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-edit text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-neutral-800 dark:text-neutral-100"
                                    id="modal-title">
                                    Edit Category
                                </h3>
                                <div class="mt-4">
                                    <label for="category-name"
                                           class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Category Name</label>
                                    <input type="text" id="category-name" wire:model="name" required
                                           class="appearance-textfield w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="mt-4">
                                    <label for="category-image"
                                           class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Category Image</label>

                                    @if($image && !$tempImage)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/categories/' . $image) }}" alt="Current Image"
                                             class="h-24 w-auto rounded-md">
                                        <p class="text-xs text-neutral-500 mt-1">Current image</p>
                                    </div>
                                    @endif

                                    @if($tempImage)
                                    <div class="mb-3">
                                        <img src="{{ $tempImage->temporaryUrl() }}" alt="New Image Preview"
                                             class="h-24 w-auto rounded-md">
                                        <p class="text-xs text-neutral-500 mt-1">New image preview</p>
                                    </div>
                                    @endif

                                    <input type="file" id="category-image" wire:model="tempImage"
                                           class="appearance-textfield w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                                    @error('tempImage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-neutral-50 dark:bg-neutral-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Save Changes
                        </button>
                        <button type="button" wire:click="cancelEditing"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-neutral-300 dark:border-neutral-600 shadow-sm px-4 py-2 bg-white dark:bg-neutral-700 text-base font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Category Modal -->
    @if($categoryToDelete)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-dvh pt-4 px-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black/65 transition-opacity" aria-hidden="true"
                 wire:click="cancelDelete"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/50 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-neutral-800 dark:text-neutral-100"
                                id="modal-title">
                                Delete Category
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                    Are you sure you want to delete the category "{{ $categoryToDelete->name }}"?
                                    This action cannot be undone.
                                </p>
                                @if($categoryToDelete->products()->exists())
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    Warning: This category has {{ $categoryToDelete->products()->count() }} products. Deleting it will remove all associations.
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-neutral-50 dark:bg-neutral-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="deleteCategory"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                    <button type="button" wire:click="cancelDelete"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-neutral-300 dark:border-neutral-600 shadow-sm px-4 py-2 bg-white dark:bg-neutral-700 text-base font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Search and Filter -->
    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">
                    Search Categories
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-neutral-400"></i>
                    </div>
                    <input type="text" id="search" placeholder="Search category" wire:model.live.debounce.300ms="search"
                           class="pl-10 w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                </div>
            </div>

            <div>
                <label for="order-by" class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">
                    Order By
                </label>
                <select id="order-by" wire:model.live="order_by"
                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200 cursor-pointer">
                    <option value="name_asc">Name A-Z</option>
                    <option value="name_desc">Name Z-A</option>
                    <option value="products_high_low">Products High-Low</option>
                    <option value="products_low_high">Products Low-High</option>
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-50 dark:bg-neutral-700">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Image
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Category
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Products
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Created At
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                @forelse($categories as $category)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors">
                        <!-- Image Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-md object-cover"
                                     src="{{ asset('storage/categories/' . $category->image) }}"
                                     alt="{{ $category->name }}">
                            </div>
                        </td>

                        <!-- Category Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-neutral-800 dark:text-neutral-100">{{ $category->name }}</div>
                        </td>

                        <!-- Products Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-neutral-800 dark:text-neutral-100">
                                {{ $category->products_count }} products
                            </div>
                        </td>

                        <!-- Created At Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-neutral-800 dark:text-neutral-100">
                                {{ $category->created_at->format('M d, Y') }}
                            </div>
                        </td>

                        <!-- Actions Column -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('board.categories.show', $category->id) }}"
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" wire:click="startEditing({{ $category->id }})"
                                        class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" wire:click="confirmDelete({{ $category->id }})"
                                        class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-neutral-500 dark:text-neutral-400">
                            No categories found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-neutral-200 dark:border-neutral-700">
            {{ $categories->links() }}
        </div>
    </div>
</div>
