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

                <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('user.index', ['role' => request('role')]) }}"
                            class="px-3 py-1.5 text-sm rounded-lg {{ request('status') !== 'pending' ? 'bg-gray-700 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                            All Users
                        </a>
                        <a href="{{ route('user.index', ['status' => 'pending', 'role' => request('role')]) }}"
                            class="px-3 py-1.5 text-sm rounded-lg {{ request('status') === 'pending' ? 'bg-amber-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                            Pending Broker Approvals
                            @if ($pendingCount > 0)
                                <span class="ml-1 px-1.5 py-0.5 text-xs rounded-full bg-white/30">{{ $pendingCount }}</span>
                            @endif
                        </a>
                    </div>

                    <form method="GET" action="{{ route('user.index') }}" class="flex items-center gap-2">
                        @if (request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        <label for="role-filter" class="text-sm text-gray-600 dark:text-gray-300">Role:</label>
                        <select id="role-filter" name="role" onchange="this.form.submit()"
                            class="rounded-lg border-gray-300 dark:bg-gray-700 dark:text-gray-200 text-sm">
                            <option value="">All Roles</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                @php
                    $columns = [
                        ['label' => '#'],
                        ['label' => 'name', 'key' => 'name'],
                        ['label' => 'role', 'key' => 'roles->first()?->name'],
                        ['label' => 'phone', 'key' => 'phone'],
                        ['label' => 'registered', 'key' => 'registered_at'],
                        [
                            'label' => 'Status',
                            'key' => 'approval_status',
                            'badge' => true,
                            'badgeMap' => [
                                'pending' => ['text' => 'Pending Approval', 'color' => 'bg-amber-500 text-white'],
                                'active' => ['text' => 'Active', 'color' => 'bg-green-500 text-white'],
                                'deactivated' => ['text' => 'Deactivated', 'color' => 'bg-red-500 text-white'],
                            ],
                        ],
                    ];

                    $actions = [];

                    if (auth()->user()->can('view_user')) {
                        $actions[] = ['type' => 'view', 'url' => 'user.show', 'label' => 'View'];
                    }

                    if (auth()->user()->can('edit_user')) {
                        $actions[] = ['type' => 'edit', 'url' => 'user.edit', 'label' => 'Edit'];
                        $actions[] = ['type' => 'approve', 'url' => 'user.approve', 'label' => 'Approve', 'statusKey' => 'is_approved'];
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
