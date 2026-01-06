<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-nav-link :href="route('projects.index')">
                Projects
            </x-nav-link>

            <span class="text-gray-500 mx-2">/</span>

            <span class="font-semibold text-gray-800 dark:text-gray-200">
                Phases – {{ $project->name }}
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                {{-- Header --}}
                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Project Phases
                    </h2>

                    @can('create_phases')
                        <div class="md:text-end">
                            <x-button-link
                                href="{{ route('projects.phases.create', $project) }}">
                                + Add Phase
                            </x-button-link>
                        </div>
                    @endcan
                </div>

                @can('view_phases')
                    @php
                        $actions = [];

                        if(auth()->user()->can('edit_phases')) {
                            $actions[] = [
                                'type' => 'edit',
                                'url'  => 'projects.phases.edit',
                                'params' => [$project->id], 
                                'label'=> 'Edit'
                            ];
                        }

                        if(auth()->user()->can('delete_phases')) {
                            $actions[] = [
                                'type' => 'delete',
                                'url'  => 'projects.phases.destroy',
                                'params' => [$project->id], 
                                'label'=> 'Delete'
                            ];
                        }

                        $columns = collect([
                            ['label' => '#'],

                            ['label' => 'Phase Name', 'key' => 'name'],

                            ['label' => 'Total Units', 'key' => 'total_units'],

                            ['label' => 'Bedrooms', 'key' => 'bedrooms'],

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

                            ['label' => 'Handover', 'key' => 'handover'],

                            count($actions) ? ['label' => 'Actions', 'actions' => $actions] : null,
                        ])
                        ->filter()
                        ->values()
                        ->toArray();
                    @endphp

                    <x-datatable
                        :data="$phases"
                        :columns="$columns"
                        :routeParams="['project' => $project->id]"
                    />

                @else
                    <div class="text-gray-600 dark:text-gray-300">
                        You do not have permission to view phases.
                    </div>
                @endcan

            </div>
        </div>
    </div>
</x-app-layout>
