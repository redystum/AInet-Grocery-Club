<div x-data="{
    activeDropdown: null,
    showEditModal: false,
    editCategoryId: null,
    editCategoryName: '',
    showDeleteModal: false,
    deleteCategoryId: null,
    deleteCategoryName: ''
}"
     x-init="document.addEventListener('click', () => { activeDropdown = null })"
     @scroll.window="activeDropdown = null"
     @keydown.escape.window="activeDropdown = null">

    <!-- Edit Category Modal -->
    <div x-show="showEditModal"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">
        <div class="flex items-center justify-center min-h-dvh pt-4 px-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div x-show="showEditModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black/65 transition-opacity"
                 aria-hidden="true"
                 @click="showEditModal = false"></div>

            <!-- Modal panel -->
            <div x-show="showEditModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form method="POST"
                      x-bind:action="'{{ route('board.categories.update', ['category' => '__ID__']) }}'.replace('__ID__', editCategoryId)">
                    @method('PUT')
                    @csrf
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
                                    <input type="text" id="category-name" x-model="editCategoryName" name="name"
                                           required
                                           x-effect="if(showEditModal) $nextTick(() => $el.focus())"
                                           class="appearance-textfield w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-neutral-50 dark:bg-neutral-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                                @click="showEditModal = false;"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Save Changes
                        </button>
                        <button type="button"
                                @click="showEditModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-neutral-300 dark:border-neutral-600 shadow-sm px-4 py-2 bg-white dark:bg-neutral-700 text-base font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Category Modal -->
    <div x-show="showDeleteModal"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">
        <div class="flex items-center justify-center min-h-dvh pt-4 px-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div x-show="showDeleteModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black/65 transition-opacity"
                 aria-hidden="true"
                 @click="showDeleteModal = false"></div>

            <!-- Modal panel -->
            <div x-show="showDeleteModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form method="POST"
                      x-bind:action="'{{ route('board.categories.destroy', ['category' => '__ID__']) }}'.replace('__ID__', deleteCategoryId)">
                    @method('DELETE')
                    @csrf
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
                                        Are you sure you want to delete the category "<span x-text="deleteCategoryName"></span>"?
                                        This action cannot be undone.
                                    </p>
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400" x-show="productsCount > 0">
                                        Warning: This category has <span x-text="productsCount"></span> products. Deleting it will remove all associated products.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-neutral-50 dark:bg-neutral-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                                @click="showDeleteModal = false;"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                        <button type="button"
                                @click="showDeleteModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-neutral-300 dark:border-neutral-600 shadow-sm px-4 py-2 bg-white dark:bg-neutral-700 text-base font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create Category Button -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">Categories</h2>
        <a href="{{ route('board.categories.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
            <i class="fas fa-plus mr-2"></i> New Category
        </a>
    </div>

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
                            <div class="relative">
                                <button @click="$event.stopPropagation(); activeDropdown === 'category-{{ $category->id }}' ? activeDropdown = null : activeDropdown = 'category-{{ $category->id }}'"
                                        class="inline-flex cursor-pointer justify-center w-8 h-8 rounded-full bg-neutral-100 dark:bg-neutral-700 hover:bg-neutral-200 dark:hover:bg-neutral-600 transition-colors"
                                        :aria-expanded="activeDropdown === 'category-{{ $category->id }}'"
                                        aria-haspopup="true">
                                    <i class="fas fa-ellipsis-v text-neutral-600 dark:text-neutral-300 m-auto"></i>
                                </button>

                                <div x-show="activeDropdown === 'category-{{ $category->id }}'"
                                     x-cloak
                                     @click.outside="activeDropdown = null"
                                     @click.stop="$event.stopPropagation()"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-neutral-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                     role="menu" aria-orientation="vertical" tabindex="-1">
                                    <div role="none">
                                        <a href="{{ route('board.categories.show', $category->id) }}"
                                           class="flex items-center rounded-t-md px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700"
                                           role="menuitem">
                                            <i class="fas fa-eye mr-3 text-neutral-400"></i>
                                            View Details
                                        </a>
                                        <button @click="
                                                    editCategoryId = '{{ $category->id }}';
                                                    editCategoryName = '{{ $category->name }}';
                                                    showEditModal = true;
                                                    activeDropdown = null;
                                                "
                                                class="cursor-pointer w-full text-left flex items-center px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700"
                                                role="menuitem">
                                            <i class="fas fa-edit mr-3 text-blue-400"></i>
                                            Edit Category
                                        </button>
                                        <button @click="
                                                    deleteCategoryId = '{{ $category->id }}';
                                                    deleteCategoryName = '{{ $category->name }}';
                                                    productsCount = {{ $category->products_count }};
                                                    showDeleteModal = true;
                                                    activeDropdown = null;
                                                "
                                                class="cursor-pointer w-full rounded-b-md text-left flex items-center px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700"
                                                role="menuitem">
                                            <i class="fas fa-trash mr-3 text-red-400"></i>
                                            Delete Category
                                        </button>
                                    </div>
                                </div>
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

        <!-- Custom Pagination -->
        <div class="flex flex-col sm:flex-row p-4 justify-between items-center gap-4 border-t border-neutral-200 dark:border-neutral-700 pt-6">
            <div class="text-sm text-neutral-600 dark:text-neutral-400">
                Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }}
                of {{ $categories->total() }} items
            </div>

            <div class="flex items-center gap-2">
                <!-- First Page Link -->
                <button wire:click="gotoPage(1)"
                        @if($categories->currentPage() === 1) disabled @endif
                        class="px-3 @unless($categories->currentPage() === 1) cursor-pointer @endunless py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ $categories->currentPage() === 1 ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-angle-double-left"></i>
                </button>

                <!-- Previous Page Link -->
                <button wire:click="previousPage"
                        @if($categories->onFirstPage()) disabled @endif
                        class="px-3 @unless($categories->onFirstPage()) cursor-pointer @endunless py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ $categories->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-angle-left"></i>
                </button>

                <!-- Page Number Input -->
                <div class="flex items-center gap-1">
                    <span class="text-neutral-600 dark:text-neutral-400">Page</span>
                    <input type="number" min="1" max="{{ $categories->lastPage() }}"
                           value="{{ $categories->currentPage() }}" id="pageInput"
                           wire:keydown.enter="gotoPage($event.target.value)"
                           wire:blur="gotoPage($event.target.value)"
                           class="appearance-textfield w-12 px-2 py-1 text-center rounded-lg border border-gray-300 dark:border-neutral-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500">

                    <span class="text-neutral-600 dark:text-neutral-400">of {{ $categories->lastPage() }}</span>
                </div>

                <!-- Next Page Link -->
                <button wire:click="nextPage"
                        @unless($categories->hasMorePages()) disabled @endunless
                        class="px-3 @if($categories->hasMorePages()) cursor-pointer @endif py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ !$categories->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-angle-right"></i>
                </button>

                <!-- Last Page Link -->
                <button wire:click="gotoPage({{ $categories->lastPage() }})"
                        @if($categories->currentPage() === $categories->lastPage()) disabled @endif
                        class="px-3 py-1 @unless($categories->currentPage() === $categories->lastPage()) disabled @endunless rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ $categories->currentPage() === $categories->lastPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-angle-double-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>
