<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('projects.index')" :active="true">
            {{ __('Units Data') }} – {{ $project->name }}
        </x-nav-link>
    </x-slot>
    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div class="my-auto">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Units') }} – {{ $project->name }}
                        </h2>
                    </div>
                    @can('create_units')
                    <div class="md:text-end">
                        <x-button-link href="{{ route('projects.units.create', $project) }}">
                            Add New Unit
                        </x-button-link>
                    </div>
                    @endcan
                </div>
                @can('view_units')
                    @php
                        // Build allowed actions based on permissions
                        $actions = [];

                        if (auth()->user()->can('edit_units') || auth()->user()->can('edit_unit_pricing')) {
                            $actions[] = ['type' => 'edit', 'url' => 'projects.units.edit', 'params' => [$project->id],  'label' => 'Edit'];
                        }

                        if (auth()->user()->can('delete_units')) {
                            $actions[] = ['type' => 'delete', 'url' => 'projects.units.destroy', 'params' => [$project->id],  'label' => 'Delete'];
                        }
                        if(auth()->user()->can('view_unit_media')) {
                            $actions[] = [ 'type' => 'virtualTour', 'url'  => 'units.unitMedia.index', 'label'=> 'Virtual Tours'];
                        }
                        // Define table columns
                        $columns = collect([
                            ['label' => '#'],
                            ['label' => 'Title', 'key' => 'title'],
                            ['label' => 'Unit Number', 'key' => 'unit_number'],
                            [
                                'label' => 'Availablity',
                                'key' => 'availability_status',
                                'badge' => true,
                                'badgeMap' => [
                                    'available' => [
                                        'text' => 'Available',
                                        'color' => 'bg-green-200 text-green-800'
                                    ],
                                    'reserved' => [
                                        'text' => 'Reserved',
                                        'color' => 'bg-yellow-200 text-yellow-800'
                    ],
                                    'sold' => [
                                        'text' => 'Sold',
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

                    <x-datatable :data="$units" :columns="$columns" />
                @else
                    <div class="text-gray-600 dark:text-gray-300">
                        You do not have permission to view projects.
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
