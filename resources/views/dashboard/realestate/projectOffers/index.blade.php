<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('projects.index')" :active="true">
            {{ __('Offers') }} – {{ $project->name }}
        </x-nav-link>
    </x-slot>
    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div class="my-auto">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Offers') }} – {{ $project->name }}
                        </h2>
                    </div>
                    @can('create_project_offers')
                    <div class="md:text-end">
                        <x-button-link href="{{ route('projects.projectOffers.create', $project) }}">
                            Add New Offer
                        </x-button-link>
                    </div>
                    @endcan
                </div>
                @can('view_project_offers')
                    @php
                        $actions = [];

                        if (auth()->user()->can('edit_project_offers')) {
                            $actions[] = ['type' => 'edit', 'url' => 'projects.projectOffers.edit', 'params' => [$project->id], 'label' => 'Edit'];
                        }

                        if (auth()->user()->can('delete_project_offers')) {
                            $actions[] = ['type' => 'delete', 'url' => 'projects.projectOffers.destroy', 'params' => [$project->id], 'label' => 'Delete'];
                        }

                        $columns = collect([
                            ['label' => '#'],
                            ['label' => 'Title', 'key' => 'title'],
                            [
                                'label' => 'Category',
                                'key' => 'category',
                                'badge' => true,
                                'badgeMap' => [
                                    'exclusive' => ['text' => 'Exclusive Offer', 'color' => 'bg-blue-200 text-blue-800'],
                                    'payment_plan' => ['text' => 'Payment Plan', 'color' => 'bg-purple-200 text-purple-800'],
                                    'investment' => ['text' => 'Investment Offer', 'color' => 'bg-green-200 text-green-800'],
                                ],
                            ],
                            [
                                'label' => 'Status',
                                'key' => 'is_active',
                                'badge' => true,
                                'badgeMap' => [
                                    1 => ['text' => 'Active', 'color' => 'bg-green-200 text-green-800'],
                                    0 => ['text' => 'Inactive', 'color' => 'bg-gray-200 text-gray-800'],
                                ],
                            ],
                            ['label' => 'Units', 'key' => 'offer_units_count'],
                            count($actions) > 0 ? ['label' => 'Actions', 'actions' => $actions] : null,
                        ])
                        ->filter()
                        ->values()
                        ->toArray();
                    @endphp

                    <x-datatable :data="$offers" :columns="$columns" />
                @else
                    <div class="text-gray-600 dark:text-gray-300">
                        You do not have permission to view offers.
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
