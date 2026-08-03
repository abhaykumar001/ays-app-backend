<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('roles.index')" :active="true">
            {{ __('Roles & Permissions') }}
        </x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 grid md:grid-cols-2 gap-6">
            @can('create_roles')
            <!-- CREATE ROLE FORM -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Create Role') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Add new role and assign permissions.') }}
                        </p>
                    </header>

                    <form method="POST" action="{{ route('roles.store') }}" class="mt-6 space-y-6" id="createRoleForm">
                        @csrf
                        <div>
                            <x-input-label for="name" :value="__('Role Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                :value="old('name')" autocomplete="name" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- PERMISSIONS -->
                        <div class="mt-4 bg-gray-100 dark:bg-gray-700 p-2 rounded">
                            <div class="flex items-center gap-2 mb-2 text-xs text-gray-700 dark:text-white">
                                <button type="button" onclick="selectAll('createRoleForm')" class="hover:underline">Select All</button> /
                                <button type="button" onclick="clearAll('createRoleForm')" class="hover:underline">Clear All</button>
                            </div>
                             <x-input-error class="mt-2" :messages="$errors->get('permissions')" />
                            <div class="h-96 overflow-y-auto">
                                @foreach ($permissionsByModule as $module => $modulePermissions)
                                    <div class="border-b border-gray-300 dark:border-white/30 p-2">
                                        <div class="flex items-center justify-between bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded cursor-pointer text-gray-900 dark:text-white"
                                            onclick="toggleModule('{{ $module }}', 'createRoleForm')">
                                            <strong>{{ ucfirst($module) }}</strong>
                                            <input type="checkbox"
                                                onclick="event.stopPropagation(); toggleModuleCheckbox(this, '{{ $module }}', 'createRoleForm')">
                                        </div>
                                        <div class="pl-4 pt-2 flex flex-wrap gap-2"
                                            id="module-{{ $module }}-createRoleForm">
                                            @foreach ($modulePermissions as $permission)
                                                <label
                                                    class="flex items-center gap-1 bg-white dark:bg-gray-600 text-gray-800 dark:text-white text-xs px-2 py-1 rounded cursor-pointer">
                                                    <input type="checkbox" name="permissions[]"
                                                        value="{{ $permission->name }}">
                                                    {{ $permission->description }}
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex flex-col items-center justify-center gap-4 mt-4">
                            <x-primary-button>{{ __('Create Role') }}</x-primary-button>
                        </div>

                    </form>
                </section>
            </div>
            @endcan
            <!-- ROLE LIST & EDIT -->
            @can('view_roles')
            <div class="p-4 sm:p-8 max-h-screen overflow-y-auto bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <header>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('All Roles & Assign Permissions') }}
                    </p>
                </header>

                <div class=" p-3 space-y-4">
                    @foreach ($roles as $role)
                        <div id="roleCard-{{ $role->id }}"
                            class="border-b bg-gray-100 dark:bg-gray-700 p-3 text-gray-900 dark:text-white rounded-lg">

                            <!-- VIEW MODE -->
                            <div id="viewMode-{{ $role->id }}">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold">{{ ucfirst($role->name) }}</h3>
                                    <div class="flex gap-2">
                                        @can('edit_roles')
                                        <button type="button" onclick="toggleEdit({{ $role->id }})"
                                            class="px-3 py-1 text-sm bg-blue-500 text-white rounded hover:bg-blue-600">
                                            Edit
                                        </button>
                                        @endcan
                                        @can('delete_roles')
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1 text-sm bg-red-500 text-white rounded hover:bg-red-600">
                                                Delete
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </div>

                                <!-- PERMISSION SUMMARY -->
                                <div class="mt-2">
                                    @foreach ($permissionsByModule as $module => $modulePermissions)
                                        @php
                                            $rolePerms = $role->permissions->where('module', $module);
                                        @endphp
                                        @if ($rolePerms->count() > 0)
                                            <div class="mb-2">
                                                <strong class="text-sm">{{ ucfirst($module) }}</strong>
                                                <div class="flex flex-wrap gap-1 mt-1">
                                                    @foreach ($rolePerms as $perm)
                                                        <span
                                                            class="text-xs bg-green-500/20 text-green-700 px-2 py-0.5 rounded">
                                                            {{ $perm->description ?? $perm->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <!-- EDIT MODE -->
                            <form id="editMode-{{ $role->id }}" method="POST"
                                action="{{ route('roles.update', $role->id) }}"
                                class="hidden flex flex-col gap-3 mt-3">
                                @csrf
                                @method('PUT')

                                <x-text-input name="name" value="{{ $role->name }}"
                                    class="border p-2 rounded w-full  mb-2 md:mb-0" required />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                <div class="flex justify-between items-center text-xs text-gray-700 dark:text-gray-300">
                                    <div class="flex gap-2">
                                        <button type="button"
                                            onclick="selectAll('roleForm-{{ $role->id }}')" class="hover:underline">Select All</button> /
                                        <button type="button" onclick="clearAll('roleForm-{{ $role->id }}')" class="hover:underline">Clear
                                            All</button>
                                    </div>
                                    <button type="button" onclick="toggleEdit({{ $role->id }})"
                                        class="text-red-500 hover:underline">Cancel</button>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('permissions')" />
                                <div id="roleForm-{{ $role->id }}"
                                    class="flex flex-col gap-2 h-80 overflow-y-auto mb-3">
                                    @foreach ($permissionsByModule as $module => $modulePermissions)
                                        <div class="border-b border-gray-300 dark:border-white/30 p-2">
                                            <div class="flex items-center justify-between bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded cursor-pointer text-gray-900 dark:text-white"
                                                onclick="toggleModule('{{ $module }}', 'roleForm-{{ $role->id }}')">
                                                <strong>{{ ucfirst($module) }}</strong>
                                                <input type="checkbox"
                                                    onclick="event.stopPropagation(); toggleModuleCheckbox(this, '{{ $module }}', 'roleForm-{{ $role->id }}')">
                                            </div>
                                            <div class="pl-4 pt-2 flex flex-wrap gap-2"
                                                id="module-{{ $module }}-roleForm-{{ $role->id }}">
                                                @foreach ($modulePermissions as $permission)
                                                    <label
                                                        class="flex items-center gap-1 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-2 py-1 text-xs rounded cursor-pointer">
                                                        <input type="checkbox" name="permissions[]"
                                                            value="{{ $permission->name }}"
                                                            {{ $role->permissions->contains('name', $permission->name) ? 'checked' : '' }}>
                                                        {{ $permission->description ?? $permission->name }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <x-primary-button class="text-center">Update</x-primary-button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
            @endcan
        </div>
    </div>

    <script>
        function toggleEdit(roleId) {
            document.getElementById(`viewMode-${roleId}`).classList.toggle('hidden');
            document.getElementById(`editMode-${roleId}`).classList.toggle('hidden');
        }

        function selectAll(formId) {
            document.querySelectorAll(`#${formId} input[type=checkbox]`).forEach(cb => cb.checked = true);
        }

        function clearAll(formId) {
            document.querySelectorAll(`#${formId} input[type=checkbox]`).forEach(cb => cb.checked = false);
        }

        function toggleModuleCheckbox(checkbox, module, formId) {
            const moduleId = `module-${module}-${formId}`;
            document.querySelectorAll(`#${moduleId} input[type=checkbox]`).forEach(cb => cb.checked = checkbox.checked);
        }

        function toggleModule(module, formId) {
            const section = document.getElementById(`module-${module}-${formId}`);
            if (section) section.classList.toggle('hidden');
        }
    </script>
</x-app-layout>
