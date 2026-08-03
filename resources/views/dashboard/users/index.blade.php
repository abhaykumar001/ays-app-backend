<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('user.index')" :active="true">
            {{ __('User Data') }}
        </x-nav-link>
    </x-slot>
    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div class="my-auto">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Users for Website') }}
                        </h2>
                    </div>
                    @can('create_user')
                        <div class="md:text-end">
                            <x-button-link href="{{ route('user.create') }}">
                                Create User
                            </x-button-link>
                        </div>
                    @endcan
                </div>
                @php
                    $columns = [
                        ['label' => '#'],
                        ['label' => 'name', 'key' => 'name'],
                        ['label' => 'role', 'key' => 'roles->first()?->name'],
                        [
                            'label' => 'Status',
                            'key' => 'is_active',
                            'badge' => true,
                            'badgeMap' => [
                                1 => ['text' => 'Active', 'color' => 'bg-green-500 text-white'],
                                0 => ['text' => 'Deactivated', 'color' => 'bg-red-500 text-white'],
                            ],
                        ],
                    ];

                    $actions = [];

                    if (auth()->user()->can('edit_user')) {
                        $actions[] = ['type' => 'edit', 'url' => 'user.edit', 'label' => 'Edit'];
                        $actions[] = ['type' => 'toggleStatus', 'url' => 'user.toggleStatus', 'label' => 'Toggle Status'];
                    }

                    if (auth()->user()->can('delete_user')) {
                        $actions[] = ['type' => 'delete', 'url' => 'user.destroy', 'label' => 'Delete'];
                    }

                    if (count($actions) > 0) {
                        $columns[] = ['label' => 'Actions', 'actions' => $actions];
                    }
                @endphp
                <x-datatable :data="$users" :columns="$columns" />


            </div>
        </div>
    </div>
</x-app-layout>
