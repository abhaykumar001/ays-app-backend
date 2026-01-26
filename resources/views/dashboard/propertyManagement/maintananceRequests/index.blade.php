<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('maintananceRequests.index')" :active="true">
            {{ __('Maintenance Requests') }}
        </x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">

                <div class="flex justify-between mb-4">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Maintenance Requests
                    </h2>

                    @can('create_maintanance_requests')
                        <x-button-link :href="route('maintananceRequests.create')">
                            Create Request
                        </x-button-link>
                    @endcan
                </div>

                @php
                    $actions = [];

                    if (auth()->user()->can('edit_maintanance_requests')) {
                        $actions[] = [
                            'type' => 'edit',
                            'label' => 'Edit',
                            'url' => 'maintananceRequests.edit',
                        ];
                    }

                    if (auth()->user()->can('delete_maintanance_requests')) {
                        $actions[] = [
                            'type' => 'delete',
                            'url' => 'maintananceRequests.destroy',
                            'label' => 'Delete',
                        ];
                    }
                    $columns = collect([
                        ['label' => '#'],
                        ['label' => 'Service', 'key' => 'service.name'],
                        ['label' => 'Owner', 'key' => 'owner.name'],
                        [
                            'label' => 'Status',
                            'key' => 'status',
                            'badge' => true,
                            'badgeMap' => [
                                'pending' => ['text' => 'Pending', 'color' => 'bg-yellow-500 text-white'],
                                'in_progress' => ['text' => 'In Progress', 'color' => 'bg-blue-600 text-white'],
                                'completed' => ['text' => 'Completed', 'color' => 'bg-green-600 text-white'],
                                'cancelled' => ['text' => 'Cancelled', 'color' => 'bgRed-600 text-white'],
                            ],
                        ],
                        ['label' => 'Priority', 'key' => 'priority_level'],
                        ['label' => 'Scheduled', 'key' => 'scheduled_at'],
                        count($actions) ? ['label' => 'Actions', 'actions' => $actions] : null,
                    ])
                                ->filter()
                                ->values()
                                ->toArray();
                @endphp

                <x-datatable :data="$requests" :columns="$columns" />
            </div>
        </div>
    </div>
</x-app-layout>
