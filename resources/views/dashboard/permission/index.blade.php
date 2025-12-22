<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('permission.index')" :active="true">
            {{ __('User Permissions') }}
        </x-nav-link>
    </x-slot>
    <div class="py-6">
        <div class="sm:px-6 lg:px-8 grid md:grid-cols-2 gap-6">
            @can('create_permission')
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="">
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Permissions') }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Add user permissions information.') }}
                                </p>
                            </header>


                            <form method="post" action="{{ route('permission.store') }}" class="mt-6 space-y-6">
                                @csrf
                                <div class="grid gap-5">
                                    <!-- Module Select -->
                                    <div>
                                        <x-input-label for="module" :value="__('Module')" />
                                        <select id="module" name="module"
                                            class=" border-transparent text-sm  w-full mt-1 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm">
                                            <!-- Options will be populated by JS -->
                                        </select>
                                    </div>

                                    <!-- Action Select -->
                                    <div class="mt-3">
                                        <x-input-label for="action" :value="__('Action')" />
                                        <select id="action" name="action"
                                            class="border-transparent text-sm  w-full mt-1 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm">
                                            <!-- Options will be populated by JS -->
                                        </select>
                                    </div>

                                    <!-- Description -->
                                    <div>
                                        <x-input-label for="description" :value="__('Description')" />
                                        <x-text-input id="description" name="description" class="mt-1 block w-full"
                                            placeholder="e.g. View users list" />
                                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                                    </div>

                                </div>

                                <div class="flex flex-col items-center justify-center gap-4 mt-4">
                                    <x-primary-button>{{ __('Create') }}</x-primary-button>

                                    @if (session('status') === 'permission-created')
                                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                            class="text-sm text-green-600 dark:text-green-600">
                                            {{ __('Permission Created Successfully.') }}</p>
                                    @endif
                                </div>
                            </form>

                        </section>

                    </div>
                </div>
            @endcan
            @can('view_permission')
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <header>
                        <p class="mt-1 mb-3 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('All permssions') }}
                        </p>
                    </header>
                    <div class="max-h-96 overflow-y-auto">
                        @php
                            $grouped = $permissions->groupBy('module');
                        @endphp

                        @foreach ($grouped as $module => $perms)
                            <div class="">
                                <h3
                                    class="font-semibold bg-gray-200 dark:bg-gray-900 px-3 py-2 text-gray-800 dark:text-gray-200 mt-3">
                                    {{ $module }}</h3>
                                <div class="divide-y divide-white/20 text-white/60">
                                    @foreach ($perms as $permission)
                                        <div class="flex justify-between items-center text-sm p-2">
                                            <div>
                                                {{ $permission->description }}
                                            </div>
                                            @can('delete_permission')
                                            <form action="{{ route('permission.destroy', $permission->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class=" text-red-500 p-1 text-sm  hover:text-white">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endcan
        </div>
    </div>
    @once
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const moduleActions = @json($moduleActions);

                    const moduleSelect = document.getElementById('module');
                    const actionSelect = document.getElementById('action');
                    if (moduleActions && moduleSelect) {
                        // Populate modules
                        Object.keys(moduleActions).forEach(module => {
                            const option = document.createElement('option');
                            option.value = module;
                            option.textContent = module;
                            moduleSelect.appendChild(option);
                        });
                    }
                    // Function to populate actions based on selected module
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
                        // Initialize actions
                        populateActions(moduleSelect.value);

                        // Change actions when module changes
                        moduleSelect.addEventListener('change', function() {
                            populateActions(this.value);
                        });
                    }
                });
            </script>
        @endpush
    @endonce
</x-app-layout>
