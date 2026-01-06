<aside x-show="sidebarOpen"
    class="h-full md:fixed left-0 top-0 z-40 transition-all duration-300 ease-in-out md:flex flex-col border-r border-gray-200 dark:border-gray-700"
    :class="[
        sidebarShrink && !hoverExpand ? 'w-20' : 'w-72',
        hoverExpand ? 'absolute shadow-lg' : 'fixed',
        'bg-gray-100 dark:bg-gray-900',
    ]"
    @mouseleave="hoverExpand = false" x-transition:enter="transition ease-out duration-200 transform"
    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-150 transform" x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between px-4 h-16 border-b border-gray-200 dark:border-gray-700">
        <a href="{{ route('dashboard') }}" class="flex items-start justify-start w-full"
            @mouseenter="if (sidebarShrink) hoverExpand = true">
            {{-- <img src="{{ asset('assets/dashboard/images/logo.webp') }}" :class="sidebarShrink ? 'w-15' : 'w-20'"
                class="transition-all duration-300" x-cloak alt="logo"> --}}
        </a>

        <button @click="sidebarShrink = !sidebarShrink; hoverExpand = false"
            class="p-2 rounded-md text-gray-500 dark:text-gray-400 focus:outline-none absolute right-2 hidden md:block">
            <template x-if="hoverExpand">
                <svg class="w-6 h-6 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7-7l-7 7 7 7" />
                </svg>
            </template>

            <template x-if="!sidebarShrink">
                <svg class="w-6 h-6 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7-7l-7 7 7 7" />
                </svg>
            </template>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-2 overflow-y-auto space-y-1">
        @foreach (config('admin_menu') as $item)
            {{-- @if (auth()->user()->can($item['permission'] ?? 'view_dashboard') ||
    isset($item['permissions'])) --}}
            @php
                $searchValue = request()->get('search', '');
            @endphp


            <x-sidebar-item :item="$item" :search="$searchValue" x-bind:shrink="sidebarShrink" />
            {{-- @endif --}}
        @endforeach
    </nav>

    <!-- Footer -->
    <div class="px-4 py-4 border-t justify-between border-gray-200 dark:border-gray-700"
        :class="[
            hoverExpand ? 'flex' : '', sidebarShrink && !hoverExpand ? 'block' : 'flex'
        ]">
        <div class="text-sm text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left text-lg text-red-500 hover:text-red-600"><i
                    class="bi bi-box-arrow-right"></i></button>
        </form>
    </div>
</aside>

<script>
    function sidebar() {
        return {
            sidebarShrink: false,
            search: '',
        }
    }
</script>
