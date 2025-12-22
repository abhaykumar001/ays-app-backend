<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('seoData.index')" :active="true">
            {{ __('Seo Meta Data') }}
        </x-nav-link>
    </x-slot>
    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div class="my-auto">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Meta Data for Website') }}
                        </h2>
                    </div>
                    @can('create_seo_data')
                    <div class="md:text-end">
                        <x-button-link href="{{ route('seoData.create') }}">
                            Create Meta Data
                        </x-button-link>
                    </div>
                    @endcan
                </div>
                @can('view_seo_data')
                    @php
                        // Build allowed actions dynamically
                        $actions = [];

                        if (auth()->user()->can('edit_seo_data')) {
                            $actions[] = ['type' => 'edit', 'url' => 'seoData.edit', 'label' => 'Edit'];
                        }

                        if (auth()->user()->can('delete_seo_data')) {
                            $actions[] = ['type' => 'delete', 'url' => 'seoData.destroy', 'label' => 'Delete'];
                        }

                        // Define table columns
                        $columns = collect([
                            ['label' => '#'],
                            ['label' => 'Page', 'key' => 'page_name'],
                            ['label' => 'Meta Title', 'key' => 'meta_title'],
                            [
                                'label' => 'Status',
                                'key' => 'status',
                                'badge' => true,
                                'badgeMap' => [
                                    'active' => ['text' => 'Active', 'color' => 'bg-green-600 text-white'],
                                    'inactive' => ['text' => 'Inactive', 'color' => 'bg-red-600 text-white'],
                                ],
                            ],
                            count($actions) > 0 ? ['label' => 'Actions', 'actions' => $actions] : null,
                        ])
                        ->filter()
                        ->values()
                        ->toArray();
                    @endphp

                    <x-datatable :data="$seoData" :columns="$columns" />
                @else
                    <div class="text-gray-600 dark:text-gray-300">
                        You do not have permission to view SEO Meta Data.
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
