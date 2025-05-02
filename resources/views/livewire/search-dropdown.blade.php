<div class="relative w-full max-w-lg mx-auto">
    <input
            type="text"
            id="searchInput"
            autofocus
            wire:model.live.debounce.300ms="query"
            wire:keydown.escape="resetInput"
            wire:keydown.enter="selectItem"
            wire:keydown.arrow-down.prevent="incrementIndex"
            wire:keydown.arrow-up.prevent="decrementIndex"
            wire:keydown.tab="resetSelection"
            placeholder="Search products & categories..."
            class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-300 dark:border-neutral-700
            focus:outline-none focus:border-1 focus:border-indigo-500 text-sm bg-white dark:bg-neutral-800
            text-gray-900 dark:text-neutral-100 transition-none
            @if(strlen($query) > 0) rounded-b-none dark:!border-b-neutral-700 !border-b-gray-300 @endif"
    />
    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 dark:text-neutral-500 text-sm"></i>

    @if(strlen($query) > 0)
        <div id="searchDropdown"
             class="absolute left-0 w-full bg-white dark:bg-neutral-800 rounded-b-xl shadow-lg border border-indigo-500 dark:border-indigo-500 border-t-0 z-50 max-h-dvh overflow-y-auto transition-opacity duration-200"
        >
            @forelse($results as $index => $result)
                <a
                        href="{{ $result['url'] }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700 {{ $selectedIndex === $index ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                        wire:click.prevent="$set('selectedIndex', {{ $index }}); selectItem()"
                        wire:key="search-result-{{ $index }}"
                >
                    @if($result['type'] == "page")
                        <div
                                class="h-8 w-8 mr-3 rounded-full overflow-hidden bg-neutral-200 dark:bg-neutral-700 flex items-center justify-center">
                            <i class="fas {{ $result['icon'] }} text-neutral-500"></i>
                        </div>
                    @else
                        <img src="{{ $result['image'] }}" alt="" class="h-8 w-8 rounded-full mr-3"/>
                    @endif
                    <div class="flex-1">
                        <div class="font-medium truncate">{{ $result['name'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-neutral-400">{{ ucfirst($result['type']) }}</div>
                    </div>
                </a>
            @empty
                <div class="px-4 py-2 text-sm text-gray-500">No results found.</div>
            @endforelse
        </div>
    @endif
</div>