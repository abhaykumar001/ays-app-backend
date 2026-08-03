<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('permission.index')" :active="true">
            {{ __('Permissions') }}
        </x-nav-link>
    </x-slot>

    <div class="py-6" x-data="permissionsManager()">
        <div class="sm:px-6 lg:px-8 space-y-6">

            {{-- Help card --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-sm text-gray-600 dark:text-gray-400">
                <p>
                    A <strong class="text-gray-900 dark:text-gray-100">permission</strong> is the smallest unit of
                    access — a single view/create/edit/delete action on one area of the dashboard. Permissions are
                    normally created automatically for every new dashboard page; use the form below only if you need
                    a one-off permission that doesn't map to a page. Permissions are grouped into
                    <a href="{{ route('roles.index') }}" class="underline text-primary">Roles</a>, which is what you
                    actually assign to a user.
                </p>
            </div>

            <div class="grid lg:grid-cols-5 gap-6 items-start">

                {{-- ================= CREATE PERMISSION ================= --}}
                @can('create_permission')
                <div class="lg:col-span-2 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">
                        {{ __('Add a Permission') }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        {{ __('Pick the area and action it controls, then describe it in plain language.') }}
                    </p>

                    @if (session('status') === 'success')
                        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                            class="mb-4 text-sm text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md px-3 py-2">
                            {{ session('message', __('Permission created successfully.')) }}
                        </div>
                    @elseif (session('status') === 'error')
                        <div class="mb-4 text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md px-3 py-2">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form method="post" action="{{ route('permission.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="module" :value="__('Area (Module)')" />
                            <select id="module" name="module" required
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm text-sm">
                                <!-- Options populated by JS -->
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('module')" />
                        </div>

                        <div>
                            <x-input-label for="action" :value="__('Action')" />
                            <select id="action" name="action" required
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm text-sm">
                                <!-- Options populated by JS -->
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('action')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <x-text-input id="description" name="description" class="mt-1 block w-full"
                                placeholder="e.g. View users list" />
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                {{ __('Shown to admins when they assign this permission to a role.') }}
                            </p>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="flex justify-center pt-2">
                            <x-primary-button>{{ __('Create') }}</x-primary-button>
                        </div>
                    </form>
                </div>
                @endcan

                {{-- ================= ALL PERMISSIONS ================= --}}
                @can('view_permission')
                <div class="lg:col-span-3 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="flex items-center justify-between mb-1">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('All Permissions') }}</h2>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $permissions->count() }} {{ __('total') }}</span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        {{ __('Grouped by area. Deleting a permission removes it from every role that has it.') }}
                    </p>

                    <input type="search" x-model="filterText" placeholder="{{ __('Search permissions…') }}"
                        class="mb-3 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm text-sm">

                    @php $grouped = $permissions->groupBy('module'); @endphp

                    <div class="border border-gray-200 dark:border-gray-700 rounded-md max-h-[32rem] overflow-y-auto">
                        @forelse ($grouped as $module => $perms)
                            <div x-show="moduleMatches('{{ $module }}', @js($perms->pluck('description')))"
                                class="border-b border-gray-100 dark:border-gray-700/60 last:border-b-0">
                                <button type="button"
                                    class="w-full flex items-center justify-between gap-2 px-3 py-2 bg-gray-50 dark:bg-gray-700/40 hover:bg-gray-100 dark:hover:bg-gray-700/70 text-left"
                                    @click="toggleModule('{{ $module }}')">
                                    <span class="text-sm font-medium text-gray-800 dark:text-gray-100 truncate">{{ $module }}</span>
                                    <span class="flex items-center gap-2 shrink-0 ml-2">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $perms->count() }}</span>
                                        <i class="bi bi-chevron-down text-xs text-gray-400 transition-transform duration-150"
                                            :class="(filterText.length > 0 || isModuleOpen('{{ $module }}')) ? 'rotate-180' : ''"></i>
                                    </span>
                                </button>
                                <div class="divide-y divide-gray-100 dark:divide-gray-700/60"
                                    x-show="filterText.length > 0 || isModuleOpen('{{ $module }}')">
                                    @foreach ($perms as $permission)
                                        <div class="flex justify-between items-center gap-3 text-sm px-3 py-2">
                                            <span class="text-gray-700 dark:text-gray-200 min-w-0 truncate">
                                                {{ $permission->description ?? $permission->name }}
                                            </span>
                                            @can('delete_permission')
                                            <form id="delete-permission-form-{{ $permission->id }}"
                                                action="{{ route('permission.destroy', $permission->id) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button"
                                                @click="deleteTarget = { id: {{ $permission->id }}, name: @js($permission->description ?? $permission->name) }"
                                                class="shrink-0 text-red-500 hover:text-red-600 p-1" title="{{ __('Delete') }}">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                            @endcan
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400 p-3">{{ __('No permissions yet.') }}</p>
                        @endforelse
                    </div>
                </div>
                @endcan
            </div>
        </div>

        {{-- Delete confirmation --}}
        <div x-show="deleteTarget" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
            @keydown.escape.window="deleteTarget = null">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-sm w-full p-6" @click.outside="deleteTarget = null">
                <div class="flex items-start gap-3">
                    <div class="shrink-0 w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/40 flex items-center justify-center">
                        <i class="bi bi-exclamation-triangle text-red-600 dark:text-red-400"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ __('Delete this permission?') }}</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Are you sure you want to delete') }} "<span class="font-medium" x-text="deleteTarget?.name"></span>"?
                            {{ __("It will be removed from every role that currently has it. This can't be undone.") }}
                        </p>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-5">
                    <button type="button" @click="deleteTarget = null"
                        class="px-3 py-1.5 text-sm rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                        {{ __('Cancel') }}
                    </button>
                    <button type="button" @click="confirmDelete()"
                        class="px-3 py-1.5 text-sm rounded-md bg-red-600 text-white hover:bg-red-700">
                        {{ __('Yes, delete') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    @once
        @push('scripts')
            <script>
                function permissionsManager() {
                    return {
                        filterText: '',
                        openModules: {},
                        deleteTarget: null,

                        toggleModule(module) {
                            this.openModules[module] = !this.openModules[module];
                        },
                        isModuleOpen(module) {
                            return !!this.openModules[module];
                        },
                        moduleMatches(module, labels) {
                            if (!this.filterText) return true;
                            const t = this.filterText.toLowerCase();
                            if (module.toLowerCase().includes(t)) return true;
                            return labels.some(l => (l || '').toLowerCase().includes(t));
                        },
                        confirmDelete() {
                            if (!this.deleteTarget) return;
                            const form = document.getElementById('delete-permission-form-' + this.deleteTarget.id);
                            if (form) form.submit();
                            this.deleteTarget = null;
                        },
                    };
                }

                document.addEventListener('DOMContentLoaded', function() {
                    const moduleActions = @json($moduleActions);

                    const moduleSelect = document.getElementById('module');
                    const actionSelect = document.getElementById('action');
                    if (moduleActions && moduleSelect) {
                        Object.keys(moduleActions).forEach(module => {
                            const option = document.createElement('option');
                            option.value = module;
                            option.textContent = module;
                            moduleSelect.appendChild(option);
                        });
                    }
                    function populateActions(module) {
                        actionSelect.innerHTML = '';
                        if (moduleActions[module]) {
                            moduleActions[module].forEach(action => {
                                const option = document.createElement('option');
                                option.value = action;
                                option.textContent = action;
                                actionSelect.appendChild(option);
                            });
                        }
                    }
                    if (moduleSelect) {
                        populateActions(moduleSelect.value);
                        moduleSelect.addEventListener('change', function() {
                            populateActions(this.value);
                        });
                    }
                });
            </script>
        @endpush
    @endonce
</x-app-layout>
