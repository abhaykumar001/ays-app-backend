<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('events.index')" :active="true">
            {{ __('Events') }}
        </x-nav-link>
    </x-slot>
    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div class="my-auto">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Life at AYS — Events') }}
                        </h2>
                    </div>
                    @can('create_events')
                    <div class="md:text-end">
                        <x-button-link href="{{ route('events.create') }}">
                            Add New Event
                        </x-button-link>
                    </div>
                    @endcan
                </div>
                @can('view_events')
                    @php
                        $actions = [];

                        if (auth()->user()->can('edit_events')) {
                            $actions[] = ['type' => 'edit', 'url' => 'events.edit', 'label' => 'Edit'];
                        }

                        if (auth()->user()->can('delete_events')) {
                            $actions[] = ['type' => 'delete', 'url' => 'events.destroy', 'label' => 'Delete'];
                        }

                        $columns = collect([
                            ['label' => '#'],
                            ['label' => 'Title', 'key' => 'title'],
                            [
                                'label' => 'Type',
                                'key' => 'type',
                                'badge' => true,
                                'badgeMap' => [
                                    'launch' => ['text' => 'Launch', 'color' => 'bg-indigo-600 text-white'],
                                    'open_house' => ['text' => 'Open House', 'color' => 'bg-cyan-600 text-white'],
                                    'site_visit' => ['text' => 'Site Visit', 'color' => 'bg-teal-600 text-white'],
                                    'broker_meet' => ['text' => 'Broker Meet', 'color' => 'bg-purple-600 text-white'],
                                    'webinar' => ['text' => 'Webinar', 'color' => 'bg-blue-600 text-white'],
                                    'handover' => ['text' => 'Handover', 'color' => 'bg-amber-600 text-white'],
                                    'other' => ['text' => 'Other', 'color' => 'bg-gray-500 text-white'],
                                ],
                            ],
                            ['label' => 'Event Date', 'key' => 'event_date'],
                            [
                                'label' => 'Status',
                                'key' => 'status',
                                'badge' => true,
                                'badgeMap' => [
                                    'draft' => ['text' => 'Draft', 'color' => 'bg-gray-500 text-white'],
                                    'published' => ['text' => 'Published', 'color' => 'bg-green-600 text-white'],
                                    'cancelled' => ['text' => 'Cancelled', 'color' => 'bg-red-600 text-white'],
                                    'completed' => ['text' => 'Completed', 'color' => 'bg-blue-600 text-white'],
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
                            count($actions) > 0 ? ['label' => 'Actions', 'actions' => $actions] : null,
                        ])
                        ->filter()
                        ->values()
                        ->toArray();
                    @endphp

                    <x-datatable :data="$events" :columns="$columns" />
                @else
                    <div class="text-gray-600 dark:text-gray-300">
                        You do not have permission to view events.
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
