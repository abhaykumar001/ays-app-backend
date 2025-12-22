<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('insights.index')" :active="true">
            {{ __('Insight Data') }}
        </x-nav-link>
    </x-slot>
    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div class="my-auto">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Insights for Website') }}
                        </h2>
                    </div>
                    @can('create_insights')
                    <div class="md:text-end">
                        <x-button-link href="{{ route('insights.create') }}">
                            Add New Insight
                        </x-button-link>
                    </div>
                    @endcan
                </div>
                @can('view_insights')
                    @php
                        // Build allowed actions based on permissions
                        $actions = [];

                        if (auth()->user()->can('view_insights')) {
                            $actions[] = ['type' => 'view', 'url' => 'insights.show', 'label' => 'View'];
                        }

                        if (auth()->user()->can('edit_insights')) {
                            $actions[] = ['type' => 'edit', 'url' => 'insights.edit', 'label' => 'Edit'];
                        }

                        if (auth()->user()->can('delete_insights')) {
                            $actions[] = ['type' => 'delete', 'url' => 'insights.destroy', 'label' => 'Delete'];
                        }

                        // Define table columns
                        $columns = collect([
                            ['label' => '#'],
                            ['label' => 'Title', 'key' => 'title'],
                            ['label' => 'Published Date', 'key' => 'published_at'],
                            [
                                'label' => 'Forbes',
                                'key' => 'published_on_forbes',
                                'badge' => true,
                                'badgeMap' => [
                                    '1' => ['text' => 'Yes', 'color' => 'bg-green-600 text-white'],
                                    '0' => ['text' => 'No', 'color' => 'bg-red-600 text-white'],
                                ],
                            ],
                            [
                                'label' => 'Featured',
                                'key' => 'is_featured',
                                'badge' => true,
                                'badgeMap' => [
                                    '1' => ['text' => 'Yes', 'color' => 'bg-green-600 text-white'],
                                    '0' => ['text' => 'No', 'color' => 'bg-red-600 text-white'],
                                ],
                            ],
                            [
                                'label' => 'Status',
                                'key' => 'status',
                                'badge' => true,
                                'badgeMap' => [
                                    'active' => ['text' => 'Active', 'color' => 'bg-green-600 text-white'],
                                    'inactive' => ['text' => 'Inactive', 'color' => 'bg-red-600 text-white'],
                                    'draft' => ['text' => 'Draft', 'color' => 'bg-blue-600 text-white'],
                                ],
                            ],
                            count($actions) > 0 ? ['label' => 'Actions', 'actions' => $actions] : null,
                        ])
                        ->filter()
                        ->values()
                        ->toArray();
                    @endphp

                    <x-datatable :data="$insights" :columns="$columns" />
                @else
                    <div class="text-gray-600 dark:text-gray-300">
                        You do not have permission to view insights.
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
