<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('locations.index')" :active="true">
            {{ __('Locations') }}
        </x-nav-link>
    </x-slot>
    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div class="my-auto">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Locations') }}
                        </h2>
                    </div>
                    @can('create_locations')
                        <div class="md:text-end">
                            <x-button-link href="{{ route('locations.create') }}">
                                Add Location
                            </x-button-link>
                        </div>
                    @endcan
                </div>

                @if (session('status') === 'success')
                    <div class="mb-4 p-3 rounded bg-green-50 border border-green-200 text-green-700 text-sm">
                        {{ session('message') }}
                    </div>
                @elseif (session('status') === 'error')
                    <div class="mb-4 p-3 rounded bg-red-50 border border-red-200 text-red-700 text-sm">
                        {{ session('message') }}
                    </div>
                @endif

                @can('view_locations')
                    @php
                        $actions = [];

                        if (auth()->user()->can('edit_locations')) {
                            $actions[] = ['type' => 'edit', 'url' => 'locations.edit', 'label' => 'Edit'];
                            $actions[] = ['type' => 'toggleStatus', 'url' => 'locations.toggleStatus', 'label' => 'Toggle Status'];
                        }

                        if (auth()->user()->can('delete_locations')) {
                            $actions[] = ['type' => 'delete', 'url' => 'locations.destroy', 'label' => 'Delete'];
                        }

                        $columns = collect([
                            ['label' => '#'],
                            ['label' => 'Image', 'key' => 'thumbnail_url', 'type' => 'image'],
                            ['label' => 'Title', 'key' => 'title'],
                            ['label' => 'Order', 'key' => 'sort_order'],
                            [
                                'label' => 'Status',
                                'key' => 'is_active',
                                'badge' => true,
                                'badgeMap' => [
                                    1 => ['text' => 'Active', 'color' => 'bg-green-200 text-green-800'],
                                    0 => ['text' => 'Inactive', 'color' => 'bg-yellow-200 text-yellow-800'],
                                ],
                            ],
                            count($actions) > 0 ? ['label' => 'Actions', 'actions' => $actions] : null,
                        ])
                        ->filter()
                        ->values()
                        ->all();
                    @endphp
                    <x-datatable :data="$locations" :columns="$columns" />
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
