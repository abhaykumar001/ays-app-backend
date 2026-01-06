<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('blogs.index')" :active="true">
            {{ __('Blog Data') }}
        </x-nav-link>
    </x-slot>
    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div class="my-auto">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Blogs for Website') }}
                        </h2>
                    </div>
                    @can('create_blogs')
                    <div class="md:text-end">
                        <x-button-link href="{{ route('blogs.create') }}">
                            Add New Blog
                        </x-button-link>
                    </div>
                    @endcan
                </div>
                @can('view_blogs')
                    @php
                        // Build allowed actions based on permissions
                        $actions = [];

                        if (auth()->user()->can('view_blogs')) {
                            $actions[] = ['type' => 'view', 'url' => 'blogs.show', 'label' => 'View'];
                        }

                        if (auth()->user()->can('edit_blogs')) {
                            $actions[] = ['type' => 'edit', 'url' => 'blogs.edit', 'label' => 'Edit'];
                        }

                        if (auth()->user()->can('delete_blogs')) {
                            $actions[] = ['type' => 'delete', 'url' => 'blogs.destroy', 'label' => 'Delete'];
                        }

                        // Define table columns
                        $columns = collect([
                            ['label' => '#'],
                            ['label' => 'Title', 'key' => 'title'],
                            ['label' => 'Published Date', 'key' => 'published_at'],
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

                    <x-datatable :data="$blogs" :columns="$columns" />
                @else
                    <div class="text-gray-600 dark:text-gray-300">
                        You do not have permission to view blogs.
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
