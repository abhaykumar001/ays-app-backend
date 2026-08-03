<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('projects.index')" :active="true">
            {{ __('Project Data') }}
        </x-nav-link>
    </x-slot>
    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div class="my-auto">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Projects for Website') }}
                        </h2>
                    </div>
                    @can('create_projects')
                    <div class="md:text-end">
                        <x-button-link href="{{ route('projects.create') }}">
                            Add New Project
                        </x-button-link>
                    </div>
                    @endcan
                </div>
                @can('view_projects')
                    @php
                        // Build allowed actions based on permissions
                        $actions = [];

                        if (auth()->user()->can('edit_projects') || auth()->user()->can('edit_project_pricing')) {
                            $actions[] = ['type' => 'edit', 'url' => 'projects.edit', 'label' => 'Edit'];
                        }

                        if (auth()->user()->can('delete_projects')) {
                            $actions[] = ['type' => 'delete', 'url' => 'projects.destroy', 'label' => 'Delete'];
                        }
                        if(auth()->user()->can('view_phases')) {
                            $actions[] = [ 'type' => 'phase', 'url'  => 'projects.phases.index', 'label'=> 'Phases'];
                        }
                        if(auth()->user()->can('view_virtual_tours')) {
                            $actions[] = [ 'type' => 'virtualTour', 'url'  => 'projects.virtualTours.index', 'label'=> 'Virtual Tours'];
                        }
                        if(auth()->user()->can('view_highlights')) {
                            $actions[] = [ 'type' => 'highlight', 'url'  => 'projects.highlights.index', 'label'=> 'Highlights'];
                        }
                        if(auth()->user()->can('view_units')) {
                            $actions[] = [ 'type' => 'unit', 'url'  => 'projects.units.index', 'label'=> 'Units'];
                        }
                        if(auth()->user()->can('view_payment_plans')) {
                            $actions[] = [ 'type' => 'paymentPlan', 'url'  => 'projects.paymentPlans.index', 'label'=> 'Payment Plans'];
                        }
                        if(auth()->user()->can('view_construction_updates')) {
                            $actions[] = [ 'type' => 'constructionUpdate', 'url'  => 'projects.constructionUpdates.index', 'label'=> 'Construction Updates'];
                        }
                        if(auth()->user()->can('view_project_offers')) {
                            $actions[] = [ 'type' => 'offer', 'url'  => 'projects.projectOffers.index', 'label'=> 'Offers'];
                        }
                        // Define table columns
                        $columns = collect([
                            ['label' => '#'],
                            ['label' => 'Title', 'key' => 'name'],
                            ['label' => 'Location', 'key' => 'community->name'],
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

                    <x-datatable :data="$projects" :columns="$columns" />
                @else
                    <div class="text-gray-600 dark:text-gray-300">
                        You do not have permission to view projects.
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
