<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('communities.index')" :active="true">
            {{ __('Community Data') }}
        </x-nav-link>
    </x-slot>
    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div class="my-auto">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Communities for Website') }}
                        </h2>
                    </div>
                    @can('create_communities')
                    <div class="md:text-end">
                        <x-button-link href="{{ route('communities.create') }}">
                            Add New Community
                        </x-button-link>
                    </div>
                    @endcan
                </div>
                @can('view_communities')
                    @php
                        // Build allowed actions based on permissions
                        $actions = [];

                        if (auth()->user()->can('edit_communities')) {
                            $actions[] = ['type' => 'edit', 'url' => 'communities.edit', 'label' => 'Edit'];
                        }

                        if (auth()->user()->can('delete_communities')) {
                            $actions[] = ['type' => 'delete', 'url' => 'communities.destroy', 'label' => 'Delete'];
                        }
                        if (auth()->user()->can('view_nearby_places')) {
                            $actions[] = ['type' => 'phase', 'url' => 'communities.nearbyPlaces.index',  'label' => 'NearbyPlaces'];
                        }

                        // Define table columns
                        $columns = collect([
                            ['label' => '#'],
                            ['label' => 'Name', 'key' => 'name'],
                            ['label' => 'Image', 'key' => 'image'],
                            [
                                'label' => 'Status',
                                'key' => 'is_active',
                                'badge' => true,
                                'badgeMap' => [
                                    1 => [
                                        'text' => 'Active',
                                        'color' => 'bg-green-200 text-green-800'
                                    ],
                                    0 => [
                                        'text' => 'Inactive',
                                        'color' => 'bg-yellow-200 text-yellow-800'
                                    ]
                                ],
                            ],
                            count($actions) > 0 ? ['label' => 'Actions', 'actions' => $actions] : null,
                        ])
                        ->filter()
                        ->values()
                        ->toArray();
                    @endphp

                    <x-datatable :data="$communities" :columns="$columns" />
                @else
                    <div class="text-gray-600 dark:text-gray-300">
                        You do not have permission to view communities.
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
