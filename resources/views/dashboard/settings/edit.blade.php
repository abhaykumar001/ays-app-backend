<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('website.settings')" :active="true">
            {{ __('Website Settings') }}
        </x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="">
                    @include('dashboard.settings.partials.website-info-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="">
                    @include('dashboard.settings.partials.personal-info-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
