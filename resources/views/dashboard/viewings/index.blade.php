<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('viewings.index')" :active="true">
            {{ __('Viewings') }}
        </x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">

                <div class="flex justify-between mb-4">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Viewings
                    </h2>
                </div>

                @php
                    $actions = [];

                    if (auth()->user()->can('edit_viewings')) {
                        $actions[] = [
                            'type' => 'edit',
                            'label' => 'Edit',
                            'url' => 'viewings.edit',
                        ];
                    }

                    if (auth()->user()->can('delete_viewings')) {
                        $actions[] = [
                            'type' => 'delete',
                            'url' => 'viewings.destroy',
                            'label' => 'Delete',
                        ];
                    }

                    $columns = collect([
                        ['label' => '#'],
                        ['label' => 'Name', 'key' => 'contact_name'],
                        ['label' => 'Email', 'key' => 'contact_email'],
                        ['label' => 'Phone', 'key' => 'contact_phone'],
                        ['label' => 'Project', 'key' => 'project.name'],
                        ['label' => 'Unit', 'key' => 'unit.title'],
                        ['label' => 'Team Member', 'key' => 'teamMember.name'],
                        ['label' => 'Type', 'key' => 'viewing_type'],
                        ['label' => 'Scheduled', 'key' => 'scheduled_at_formatted'],
                        [
                            'label' => 'Status',
                            'key' => 'status',
                            'badge' => true,
                            'badgeMap' => [
                                'pending' => ['text' => 'Pending', 'color' => 'bg-yellow-500 text-white'],
                                'completed' => ['text' => 'Completed', 'color' => 'bg-green-600 text-white'],
                                'cancelled' => ['text' => 'Cancelled', 'color' => 'bg-red-600 text-white'],
                            ],
                        ],
                        count($actions) ? ['label' => 'Actions', 'actions' => $actions] : null,
                    ])
                                ->filter()
                                ->values()
                                ->toArray();
                @endphp

                <x-datatable :data="$viewings" :columns="$columns" />
            </div>
        </div>
    </div>
</x-app-layout>
