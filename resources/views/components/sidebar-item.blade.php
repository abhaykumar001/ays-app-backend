@props(['item', 'search' => '', 'sidebarShrink' => false])

@php
    $hasChildren = !empty($item['children']);

    // Check if current route matches this item
    $isActive = isset($item['route']) && request()->routeIs($item['route']);

    // Check if any child is active
    $isChildActive = $hasChildren && collect($item['children'])->contains(function ($child) {
        return isset($child['route']) && request()->routeIs($child['route']);
    });

    $matchesSearch = empty($search) || str_contains(strtolower($item['title']), strtolower($search));
@endphp

@if ($matchesSearch)

    {{-- ================= PARENT ITEM ================= --}}
    @if ($hasChildren)
        <div x-data="{ open: {{ $isChildActive ? 'true' : 'false' }} }" class="space-y-1">

            <button
                @click="open = !open"
                class="w-full flex items-center justify-between text-sm px-3 py-2 rounded-md transition-all duration-200
                    {{ $isChildActive ? 'bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-white' : 'text-gray-500 hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-800' }}"
            >
                <div class="flex items-center space-x-2">
                    <i class="{{ $item['icon'] }}"></i>
                    <span x-show="!sidebarShrink || hoverExpand">{{ $item['title'] }}</span>
                </div>

                <i
                    class="bi bi-chevron-down transition-transform duration-200"
                    :class="open ? 'rotate-180' : ''"
                ></i>
            </button>

            <div x-show="open" x-collapse class="ml-4 space-y-1">
                @foreach ($item['children'] as $child)
                    @if(auth()->user()->can($child['permission'] ?? 'view_dashboard'))
                        <x-sidebar-item
                            :item="$child"
                            :search="$search"
                            :sidebarShrink="$sidebarShrink"
                        />
                    @endif
                @endforeach
            </div>
        </div>

    {{-- ================= SINGLE ITEM ================= --}}
    @else
        <a
            href="{{ isset($item['route']) ? route($item['route']) : '#' }}"
            class="flex items-center space-x-2 text-sm px-3 py-2 rounded-md transition-all duration-200
                {{ $isActive
                    ? 'bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-white'
                    : 'text-gray-500 hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-800' }}"
        >
            <i class="{{ $item['icon'] }}"></i>
            <span x-show="!sidebarShrink || hoverExpand">{{ $item['title'] }}</span>
        </a>
    @endif
@endif
