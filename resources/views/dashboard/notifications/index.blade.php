<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('notifications.index')" :active="true">
            {{ __('Notifications') }}
        </x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-3 rounded bg-green-50 border border-green-200 text-green-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-3 rounded bg-red-50 border border-red-200 text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                <div class="flex justify-between mb-4">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Notifications') }}
                    </h2>

                    @can('create_notifications')
                        <x-button-link href="{{ route('notifications.create') }}">
                            Create Notification
                        </x-button-link>
                    @endcan
                </div>

                @can('view_notifications')
                    @php
                        $statusColors = [
                            'draft' => 'bg-gray-200 text-gray-800',
                            'scheduled' => 'bg-blue-200 text-blue-800',
                            'sending' => 'bg-yellow-200 text-yellow-800',
                            'sent' => 'bg-green-200 text-green-800',
                            'failed' => 'bg-red-200 text-red-800',
                        ];

                        $actions = [
                            ['type' => 'view', 'url' => 'notifications.show', 'label' => 'View'],
                        ];
                        if (auth()->user()->can('delete_notifications')) {
                            $actions[] = ['type' => 'delete', 'url' => 'notifications.destroy', 'label' => 'Delete'];
                        }

                        $columns = collect([
                            ['label' => '#', 'key' => 'id'],
                            ['label' => 'Title', 'key' => 'title'],
                            ['label' => 'Type', 'key' => 'typeLabel'],
                            ['label' => 'Target', 'key' => 'targetLabel'],
                            ['label' => 'Priority', 'key' => 'priority'],
                            [
                                'label' => 'Status', 'key' => 'status', 'badge' => true,
                                'badgeMap' => collect($statusColors)->mapWithKeys(fn($c, $s) => [$s => ['text' => ucfirst($s), 'color' => $c]])->toArray(),
                            ],
                            ['label' => 'Recipients', 'key' => 'total_recipients'],
                            ['label' => 'Scheduled / Sent At', 'key' => 'whenLabel'],
                            ['label' => 'Actions', 'actions' => $actions],
                        ])->toArray();
                    @endphp

                    <x-datatable :data="$campaigns" :columns="$columns" />
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
