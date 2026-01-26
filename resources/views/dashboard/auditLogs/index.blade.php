<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('auditLogs.index')" :active="true">
            {{ __('Audit Logs') }}
        </x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                <!-- Header -->
                <div class="mb-4">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        System Activity & Audit Trail
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Track all create, update, and delete actions across the system.
                    </p>
                </div>

                @php
                    $columns = [
                        ['label' => '#'],
                        ['label' => 'User', 'key' => 'user.name'],
                        [
                            'label' => 'Action',
                            'key' => 'action',
                            'badge' => true,
                            'badgeMap' => [
                                'created' => ['text' => 'Created', 'color' => 'bg-green-600 text-white'],
                                'updated' => ['text' => 'Updated', 'color' => 'bg-blue-600 text-white'],
                                'deleted' => ['text' => 'Deleted', 'color' => 'bg-red-600 text-white'],
                            ],
                        ],
                        ['label' => 'Target', 'key' => 'auditable_type'],
                        ['label' => 'Target ID', 'key' => 'auditable_id'],
                        ['label' => 'Date', 'key' => 'created_at'],
                        [
                            'label' => 'Changes',
                            'actions' => [
                                [
                                    'type' => 'custom',
                                    'label' => 'View',
                                    'click' => 'openChanges',
                                ],
                            ],
                        ],
                    ];
                @endphp

                <x-datatable :data="$auditLogs" :columns="$columns" />
            </div>
        </div>
    </div>

    <!-- ================= MODAL ================= -->
    <div x-data="{ show:false, oldValues:null, newValues:null }">

        <div x-show="show" x-cloak class="fixed inset-0 z-50 flex">
            <div class="fixed inset-0 bg-black/40" @click="show=false"></div>

            <div class="relative ml-auto w-full max-w-lg h-full bg-white dark:bg-gray-900 shadow-xl p-6 overflow-y-auto">

                <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">
                    Audit Log Changes
                </h2>

                <div class="grid md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Old Values
                        </h3>
                        <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded text-xs overflow-auto"
                             x-text="JSON.stringify(oldValues, null, 2)">
                        </pre>
                    </div>

                    <div>
                        <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-2">
                            New Values
                        </h3>
                        <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded text-xs overflow-auto"
                             x-text="JSON.stringify(newValues, null, 2)">
                        </pre>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <x-secondary-button @click="show=false">
                        Close
                    </x-secondary-button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= SCRIPT ================= -->
    <script>
        function openChanges(log) {
            Alpine.store('audit', {
                show: true,
                oldValues: log.old_values,
                newValues: log.new_values,
            })
        }
    </script>
</x-app-layout>
