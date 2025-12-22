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
    <div class="flex items-center justify-between px-4 h-16 border-b border-gray-200 dark:border-gray-700 relative">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="flex items-start justify-start w-full"
            @mouseenter="if (sidebarShrink) hoverExpand = true">
            <img :class="sidebarShrink && !hoverExpand ? 'w-10' : 'w-20'"
                src="{{ asset('assets/dashboard/images/logo.webp') }}" alt="logo"
                class="transition-all duration-300">
        </a>

        <!-- Shrink Button -->
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
    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        @can('view_dashboard')
            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <div class="flex items-center space-x-3">
                    <i class="bi bi-house-fill text-xl"></i>
                    <span x-show="!sidebarShrink || hoverExpand" class="transition-all duration-300">
                        {{ __('Dashboard') }}
                    </span>
                </div>
            </x-sidebar-link>
        @endcan
         @canany(['view_projects', 'view_developers', 'view_amenities', 'view_units', 'view_construction_updates'])
        <div x-data="{
            open: {{ request()->routeIs('projects.index', 'developers.index', 'amenities.index', 'units.index', 'floorplans.index', 'constructionUpdates.index') ? 'true' : 'false' }}
        }" class="space-y-1">

            <!-- Parent Link -->
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-800 rounded-md transition-all duration-200"
                :class="{ 'bg-gray-200 dark:bg-gray-800': open }">
                <div class="inline-flex items-center space-x-3">
                    <i class="bi bi-buildings text-xl"></i>
                    <span x-show="!sidebarShrink || hoverExpand" class="transition-all duration-300">
                        {{ __('Real Estate') }}
                    </span>
                </div>

                <!-- Chevron -->
                <svg x-show="!sidebarShrink || hoverExpand" :class="{ 'rotate-180': open }"
                    class="w-4 h-4  text-gray-900 dark:text-gray-100 transition-transform duration-200" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Submenu -->
            <div x-show="open" x-collapse class="ml-2 space-y-1">
                @can('view_projects')
                    <x-sidebar-link :href="route('projects.index')" :active="request()->routeIs('projects.index')">
                        <div class="flex items-center space-x-2">
                            <i class="bi bi-building-fill-add text-sm"></i>
                            <span x-show="!sidebarShrink || hoverExpand" class="text-sm">
                                {{ __('Projects') }}
                            </span>
                        </div>
                    </x-sidebar-link>
                @endcan
                @can('view_developers')
                    <x-sidebar-link :href="route('developers.index')" :active="request()->routeIs('developers.index')">
                        <div class="flex items-center space-x-2">
                            <i class="bi bi-building-up text-sm"></i>
                            <span x-show="!sidebarShrink || hoverExpand" class="text-sm">
                                {{ __('Developers') }}
                            </span>
                        </div>
                    </x-sidebar-link>
                @endcan
                @can('view_amenities')
                    <x-sidebar-link :href="route('amenities.index')" :active="request()->routeIs('amenities.index')">
                        <div class="flex items-center space-x-2">
                            <i class="bi bi-activity text-sm"></i>
                            <span x-show="!sidebarShrink || hoverExpand" class="text-sm">
                                {{ __('Amenities') }}
                            </span>
                        </div>
                    </x-sidebar-link>
                @endcan
                @can('view_units')
                    <x-sidebar-link :href="route('units.index')" :active="request()->routeIs('units.index')">
                        <div class="flex items-center space-x-2">
                            <i class="bi bi-building text-sm"></i>
                            <span x-show="!sidebarShrink || hoverExpand" class="text-sm">
                                {{ __('Units') }}
                            </span>
                        </div>
                    </x-sidebar-link>
                @endcan
                @can('view_construction_updates')
                    <x-sidebar-link :href="route('constructionUpdates.index')" :active="request()->routeIs('constructionUpdates.index')">
                        <div class="flex items-center space-x-2">
                            <i class="bi bi-building-gear text-sm"></i>
                            <span x-show="!sidebarShrink || hoverExpand" class="text-sm">
                                {{ __('Construction Updates') }}
                            </span>
                        </div>
                    </x-sidebar-link>
                @endcan
            </div>
        </div>
        @endcanany
         @canany(['view_blogs', 'view_market_insights', 'view_blog_categories', 'view_announcements', 'view_offers'])
        <div x-data="{
            open: {{ request()->routeIs('blogs.index', 'blogCategories.index', 'marketInsights.index', 'announcements.index', 'offers.index') ? 'true' : 'false' }}
        }" class="space-y-1">

            <!-- Parent Link -->
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-800 rounded-md transition-all duration-200"
                :class="{ 'bg-gray-200 dark:bg-gray-800': open }">
                <div class="inline-flex items-center space-x-3">
                    <i class="bi bi-substack text-xl"></i>
                    <span x-show="!sidebarShrink || hoverExpand" class="transition-all duration-300">
                        {{ __('Content Management') }}
                    </span>
                </div>

                <!-- Chevron -->
                <svg x-show="!sidebarShrink || hoverExpand" :class="{ 'rotate-180': open }"
                    class="w-4 h-4  text-gray-900 dark:text-gray-100 transition-transform duration-200" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Submenu -->
            <div x-show="open" x-collapse class="ml-2 space-y-1">
                @can('view_blogs')
                    <x-sidebar-link :href="route('blogs.index')" :active="request()->routeIs('blogs.index')">
                        <div class="flex items-center space-x-2">
                            <i class="bi bi-person-plus text-sm"></i>
                            <span x-show="!sidebarShrink || hoverExpand" class="text-sm">
                                {{ __('Blogs') }}
                            </span>
                        </div>
                    </x-sidebar-link>
                @endcan
                @can('view_market_insights')
                    <x-sidebar-link :href="route('marketInsights.index')" :active="request()->routeIs('marketInsights.index')">
                        <div class="flex items-center space-x-2">
                            <i class="bi bi-newspaper text-sm"></i>
                            <span x-show="!sidebarShrink || hoverExpand" class="text-sm">
                                {{ __('Market Insights') }}
                            </span>
                        </div>
                    </x-sidebar-link>
                @endcan
                @can('view_blog_categories')
                    <x-sidebar-link :href="route('blogCategories.index')" :active="request()->routeIs('blogCategories.index')">
                        <div class="flex items-center space-x-2">
                            <i class="bi bi-menu-button text-sm"></i>
                            <span x-show="!sidebarShrink || hoverExpand" class="text-sm">
                                {{ __('Blog Categories') }}
                            </span>
                        </div>
                    </x-sidebar-link>
                @endcan
                @can('view_announcements')
                    <x-sidebar-link :href="route('announcements.index')" :active="request()->routeIs('announcements.index')">
                        <div class="flex items-center space-x-2">
                            <i class="bi bi-megaphone text-sm"></i>
                            <span x-show="!sidebarShrink || hoverExpand" class="text-sm">
                                {{ __('Announcements') }}
                            </span>
                        </div>
                    </x-sidebar-link>
                @endcan
                @can('view_offers')
                    <x-sidebar-link :href="route('offers.index')" :active="request()->routeIs('offers.index')">
                        <div class="flex items-center space-x-2">
                            <i class="bi bi-broadcast text-sm"></i>
                            <span x-show="!sidebarShrink || hoverExpand" class="text-sm">
                                {{ __('Offers') }}
                            </span>
                        </div>
                    </x-sidebar-link>
                @endcan
            </div>
        </div>
        @endcanany
         @canany(['view_maintanance', 'view_owners', 'view_payments', 'view_payment_schedules', 'view_maintanance_requests'])
        <div x-data="{
            open: {{ request()->routeIs('maintanance.index', 'maintananceRequests.index', 'owners.index', 'payments.index', 'paymentSchedules.index') ? 'true' : 'false' }}
        }" class="space-y-1">

            <!-- Parent Link -->
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-800 rounded-md transition-all duration-200"
                :class="{ 'bg-gray-200 dark:bg-gray-800': open }">
                <div class="inline-flex items-center space-x-3">
                    <i class="bi bi-kanban text-xl"></i>
                    <span x-show="!sidebarShrink || hoverExpand" class="transition-all duration-300">
                        {{ __('Property Managements') }}
                    </span>
                </div>

                <!-- Chevron -->
                <svg x-show="!sidebarShrink || hoverExpand" :class="{ 'rotate-180': open }"
                    class="w-4 h-4  text-gray-900 dark:text-gray-100 transition-transform duration-200" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Submenu -->
            <div x-show="open" x-collapse class="ml-2 space-y-1">
                @can('view_maintanance')
                    <x-sidebar-link :href="route('maintanance.index')" :active="request()->routeIs('maintanance.index')">
                        <div class="flex items-center space-x-2">
                            <i class="bi bi-house-gear-fill text-sm"></i>
                            <span x-show="!sidebarShrink || hoverExpand" class="text-sm">
                                {{ __('Maintanance') }}
                            </span>
                        </div>
                    </x-sidebar-link>
                @endcan
                @can('view_owners')
                    <x-sidebar-link :href="route('owners.index')" :active="request()->routeIs('owners.index')">
                        <div class="flex items-center space-x-2">
                            <i class="bi bi-person-rolodex text-sm"></i>
                            <span x-show="!sidebarShrink || hoverExpand" class="text-sm">
                                {{ __('Owners') }}
                            </span>
                        </div>
                    </x-sidebar-link>
                @endcan
                @can('view_payments')
                    <x-sidebar-link :href="route('payments.index')" :active="request()->routeIs('payments.index')">
                        <div class="flex items-center space-x-2">
                            <i class="bi bi-wallet text-sm"></i>
                            <span x-show="!sidebarShrink || hoverExpand" class="text-sm">
                                {{ __('Payments') }}
                            </span>
                        </div>
                    </x-sidebar-link>
                @endcan
                @can('view_payment_schedules')
                    <x-sidebar-link :href="route('paymentSchedules.index')" :active="request()->routeIs('paymentSchedules.index')">
                        <div class="flex items-center space-x-2">
                            <i class="bi bi-credit-card text-sm"></i>
                            <span x-show="!sidebarShrink || hoverExpand" class="text-sm">
                                {{ __('Payment Schedules') }}
                            </span>
                        </div>
                    </x-sidebar-link>
                @endcan
                @can('view_maintanance_requests')
                    <x-sidebar-link :href="route('maintananceRequests.index')" :active="request()->routeIs('maintananceRequests.index')">
                        <div class="flex items-center space-x-2">
                            <i class="bi bi-person-fill-gear text-sm"></i>
                            <span x-show="!sidebarShrink || hoverExpand" class="text-sm">
                                {{ __('Maintanance Requests') }}
                            </span>
                        </div>
                    </x-sidebar-link>
                @endcan
            </div>
        </div>
        @endcanany
         @canany(['view_enquiries', 'view_viewings'])
        <div x-data="{
            open: {{ request()->routeIs('enquiries.index', 'viewings.index') ? 'true' : 'false' }}
        }" class="space-y-1">

            <!-- Parent Link -->
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-800 rounded-md transition-all duration-200"
                :class="{ 'bg-gray-200 dark:bg-gray-800': open }">
                <div class="inline-flex items-center space-x-3">
                    <i class="bi bi-person-lines-fill text-xl"></i>
                    <span x-show="!sidebarShrink || hoverExpand" class="transition-all duration-300">
                        {{ __('Leads') }}
                    </span>
                </div>

                <!-- Chevron -->
                <svg x-show="!sidebarShrink || hoverExpand" :class="{ 'rotate-180': open }"
                    class="w-4 h-4  text-gray-900 dark:text-gray-100 transition-transform duration-200" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Submenu -->
            <div x-show="open" x-collapse class="ml-2 space-y-1">
                @can('view_enquiries')
                    <x-sidebar-link :href="route('enquiries.index')" :active="request()->routeIs('enquiries.index')">
                        <div class="flex items-center space-x-2">
                            <i class="bi bi-list text-sm"></i>
                            <span x-show="!sidebarShrink || hoverExpand" class="text-sm">
                                {{ __('Enquiries') }}
                            </span>
                        </div>
                    </x-sidebar-link>
                @endcan
                @can('view_viewings')
                    <x-sidebar-link :href="route('viewings.index')" :active="request()->routeIs('viewings.index')">
                        <div class="flex items-center space-x-2">
                            <i class="bi bi-view-list text-sm"></i>
                            <span x-show="!sidebarShrink || hoverExpand" class="text-sm">
                                {{ __('Viewings') }}
                            </span>
                        </div>
                    </x-sidebar-link>
                @endcan
            </div>
        </div>
        @endcanany
        @can('view_agents')
            <x-sidebar-link :href="route('agents.index')" :active="request()->routeIs('agents.index')">
                <div class="inline-flex items-center space-x-3">
                    <i class="bi bi-person-bounding-box  text-xl"></i>
                    <span x-show="!sidebarShrink || hoverExpand" class="transition-all duration-300">
                        {{ __('Agents') }}
                    </span>
                </div>
            </x-sidebar-link>
        @endcan
        @can('view_buyers')
            <x-sidebar-link :href="route('buyers.index')" :active="request()->routeIs('buyers.index')">
                <div class="inline-flex items-center space-x-3">
                    <i class="bi bi-person-check  text-xl"></i>
                    <span x-show="!sidebarShrink || hoverExpand" class="transition-all duration-300">
                        {{ __('Buyers') }}
                    </span>
                </div>
            </x-sidebar-link>
        @endcan
        @can('view_website_settings')
            <x-sidebar-link :href="route('website.settings')" :active="request()->routeIs('website.settings')">
                <div class="inline-flex items-center space-x-3">
                    <i class="bi bi-gear  text-xl"></i>
                    <span x-show="!sidebarShrink || hoverExpand" class="transition-all duration-300">
                        {{ __('App Setting') }}
                    </span>
                </div>
            </x-sidebar-link>
        @endcan
        @can('view_seo_data')
            <x-sidebar-link :href="route('seoData.index')" :active="request()->routeIs('seoData.index')">
                <div class="inline-flex items-center space-x-3">
                    <i class="bi bi-activity  text-xl"></i>
                    <span x-show="!sidebarShrink || hoverExpand" class="transition-all duration-300">
                        {{ __('Seo Meta Data') }}
                    </span>
                </div>
            </x-sidebar-link>
        @endcan
        <!-- User Dropdown -->
        @canany(['view_user', 'view_permission', 'view_roles'])
            <div x-data="{
                open: {{ request()->routeIs('user.index', 'roles.index', 'permission.index') ? 'true' : 'false' }}
            }" class="space-y-1">

                <!-- Parent Link -->
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-800 rounded-md transition-all duration-200"
                    :class="{ 'bg-gray-200 dark:bg-gray-800': open }">
                    <div class="inline-flex items-center space-x-3">
                        <i class="bi bi-people-fill text-xl"></i>
                        <span x-show="!sidebarShrink || hoverExpand" class="transition-all duration-300">
                            {{ __('Users & Roles') }}
                        </span>
                    </div>

                    <!-- Chevron -->
                    <svg x-show="!sidebarShrink || hoverExpand" :class="{ 'rotate-180': open }"
                        class="w-4 h-4  text-gray-900 dark:text-gray-100 transition-transform duration-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Submenu -->
                <div x-show="open" x-collapse class="ml-2 space-y-1">
                    @can('view_user')
                        <x-sidebar-link :href="route('user.index')" :active="request()->routeIs('user.index')">
                            <div class="flex items-center space-x-2">
                                <i class="bi bi-person-plus text-sm"></i>
                                <span x-show="!sidebarShrink || hoverExpand" class="text-sm">
                                    {{ __('All Users') }}
                                </span>
                            </div>
                        </x-sidebar-link>
                    @endcan
                    @can('view_roles')
                        <x-sidebar-link :href="route('roles.index')" :active="request()->routeIs('roles.index')">
                            <div class="flex items-center space-x-2">
                                <i class="bi bi-person-bounding-box text-sm"></i>
                                <span x-show="!sidebarShrink || hoverExpand" class="text-sm">
                                    {{ __('Roles') }}
                                </span>
                            </div>
                        </x-sidebar-link>
                    @endcan
                    @can('view_permission')
                        <x-sidebar-link :href="route('permission.index')" :active="request()->routeIs('permission.index')">
                            <div class="flex items-center space-x-2">
                                <i class="bi bi-person-fill-lock text-sm"></i>
                                <span x-show="!sidebarShrink || hoverExpand" class="text-sm">
                                    {{ __('Permissions') }}
                                </span>
                            </div>
                        </x-sidebar-link>
                    @endcan
                </div>
            </div>
        @endcanany
        <x-sidebar-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
            <div class="inline-flex items-center space-x-3">
                <i class="bi bi-person-badge-fill  text-xl"></i>
                <span x-show="!sidebarShrink || hoverExpand" class="transition-all duration-300">
                    {{ __('Profile') }}
                </span>
            </div>
        </x-sidebar-link>
    </nav>
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
