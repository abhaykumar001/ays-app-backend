<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('roles.index')" :active="true">
            {{ __('Roles & Permissions') }}
        </x-nav-link>
    </x-slot>

    @php
        // The bare 'create' route (Route::resource's GET .../create) never renders
        // a separate page in this UI — creation happens inline below — but guard
        // against it being hit directly without a $roles collection in scope.
        $roles = $roles ?? collect();
        $protectedRoles = $protectedRoles ?? [];

        // Flat list of every permission name, used by "Select all" in the editor.
        $allPermissionNames = $permissionsByModule->flatten()->pluck('name');

        // If a create/update just failed validation, re-open the editor with
        // whatever the user had selected instead of silently discarding it.
        $hadValidationError = $errors->any();
        $resumeEditingRoleId = old('_editing_role_id');
    @endphp

    <div class="py-6"
        x-data="rolesManager({
            allPermissionNames: @js($allPermissionNames),
            initialEditorOpen: {{ $hadValidationError ? 'true' : 'false' }},
            initialMode: {{ $resumeEditingRoleId ? "'edit'" : "'create'" }},
            initialRoleId: {{ $resumeEditingRoleId ? (int) $resumeEditingRoleId : 'null' }},
            initialRoleName: @js(old('name', '')),
            initialSelected: @js(old('permissions', [])),
        })">
        <div class="sm:px-6 lg:px-8 space-y-6">

            {{-- Help card: what a role is and what happens after you save one --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-sm text-gray-600 dark:text-gray-400 space-y-2">
                <p>
                    A <strong class="text-gray-900 dark:text-gray-100">role</strong> is a named set of permissions —
                    it controls exactly which pages a dashboard user can see and which actions (view / create / edit / delete)
                    they can perform on each one. Anything left unticked stays hidden from anyone with that role.
                </p>
                <p>
                    This page only defines roles. To give a role to someone, go to
                    <a href="{{ route('user.index') }}" class="underline text-primary">Users &amp; Roles → All Users</a>,
                    open their profile, and assign the role there.
                </p>
            </div>

            <div class="grid lg:grid-cols-5 gap-6 items-start">

                {{-- ================= ROLE LIST ================= --}}
                @can('view_roles')
                <div class="lg:col-span-2 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Roles') }}</h2>
                        @can('create_roles')
                        <button type="button" @click="startCreate()"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border font-semibold border-transparent rounded-md text-xs text-white dark:text-primary uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <i class="bi bi-plus-lg mr-1"></i> {{ __('New Role') }}
                        </button>
                        @endcan
                    </div>

                    @if (session('status') === 'error')
                        <div class="mb-4 text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md px-3 py-2">
                            {{ session('message') }}
                        </div>
                    @elseif (session('status') === 'success')
                        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                            class="mb-4 text-sm text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md px-3 py-2">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if ($roles->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('No custom roles yet. Create one to get started.') }}
                        </p>
                    @endif

                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($roles as $role)
                            @php
                                $roleModules = $role->permissions->pluck('module')->filter()->unique()->values();
                                $permCount = $role->permissions->count();
                                $isProtected = in_array($role->name, $protectedRoles, true);
                            @endphp
                            <li class="py-3 first:pt-0 last:pb-0 rounded-md px-2 -mx-2"
                                :class="mode === 'edit' && roleId === {{ $role->id }} ? 'bg-primary-light dark:bg-gray-700' : ''">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <h3 class="font-semibold text-gray-900 dark:text-white truncate">
                                            {{ $role->name }}
                                            @if ($isProtected)
                                                <i class="bi bi-shield-lock text-gray-400 dark:text-gray-500 text-xs ml-1"
                                                    title="{{ __('Built into the app') }}"></i>
                                            @endif
                                        </h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ $permCount }} {{ Str::plural('permission', $permCount) }}
                                            @if ($roleModules->isNotEmpty())
                                                across {{ $roleModules->count() }} {{ Str::plural('area', $roleModules->count()) }}
                                            @endif
                                        </p>
                                        @if ($roleModules->isNotEmpty())
                                            <p class="text-xs text-gray-400 dark:text-gray-500 truncate mt-0.5">
                                                {{ $roleModules->take(4)->implode(', ') }}
                                                @if ($roleModules->count() > 4)
                                                    +{{ $roleModules->count() - 4 }} more
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3 shrink-0 text-xs">
                                        @can('edit_roles')
                                        <button type="button"
                                            @click="startEdit({{ $role->id }}, @js($role->name), @js($role->permissions->pluck('name')))"
                                            class="text-blue-500 hover:underline whitespace-nowrap">
                                            <i class="bi bi-pencil-square"></i> {{ __('Edit') }}
                                        </button>
                                        @endcan

                                        @can('delete_roles')
                                            @if ($isProtected)
                                                <span class="text-gray-400 dark:text-gray-500 whitespace-nowrap cursor-not-allowed"
                                                    title="{{ __('This role is required by the app and can\'t be deleted.') }}">
                                                    <i class="bi bi-trash3"></i> {{ __('Delete') }}
                                                </span>
                                            @else
                                                <form id="delete-form-{{ $role->id }}" action="{{ route('roles.destroy', $role->id) }}" method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button type="button"
                                                    @click="deleteTarget = { id: {{ $role->id }}, name: @js($role->name) }"
                                                    class="text-red-500 hover:underline whitespace-nowrap">
                                                    <i class="bi bi-trash3"></i> {{ __('Delete') }}
                                                </button>
                                            @endif
                                        @endcan
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endcan

                {{-- ================= SHARED EDITOR (create or edit — one copy of the permission grid) ================= --}}
                @canany(['create_roles', 'edit_roles'])
                <div class="lg:col-span-3 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg" x-ref="editor">
                    <template x-if="!editorOpen">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Select a role to edit its permissions, or create a new one.') }}
                        </p>
                    </template>

                    <form method="POST" x-show="editorOpen" x-cloak class="space-y-6"
                        :action="mode === 'create' ? '{{ route('roles.store') }}' : updateUrlTemplate.replace('__ID__', roleId)">
                        @csrf
                        <input type="hidden" name="_method" :value="mode === 'edit' ? 'PUT' : 'POST'">
                        <input type="hidden" name="_editing_role_id" :value="mode === 'edit' ? roleId : ''">

                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <span x-show="mode === 'create'">{{ __('Create Role') }}</span>
                                <span x-show="mode === 'edit'">{{ __('Edit Role') }}: <span x-text="roleName"></span></span>
                            </h2>
                            <button type="button" x-show="mode === 'edit'" @click="startCreate()"
                                class="text-xs text-gray-500 hover:underline">{{ __('Cancel') }}</button>
                        </div>

                        <div>
                            <x-input-label for="role_name" :value="__('Role Name')" />
                            <x-text-input id="role_name" name="name" type="text" class="mt-1 block w-full"
                                x-model="roleName" required autocomplete="off" />
                            @if ($hadValidationError)
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            @endif
                        </div>

                        <div>
                            <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                <x-input-label :value="__('Permissions')" />
                                <div class="flex items-center gap-3 text-xs">
                                    <span class="text-gray-500 dark:text-gray-400">
                                        <span x-text="selectedPermissions.length"></span> / {{ $allPermissionNames->count() }} {{ __('selected') }}
                                    </span>
                                    <button type="button" @click="selectAll()" class="text-primary hover:underline">{{ __('Select all') }}</button>
                                    <button type="button" @click="clearAll()" class="text-primary hover:underline">{{ __('Clear all') }}</button>
                                </div>
                            </div>
                            @if ($hadValidationError)
                                <x-input-error class="mb-2" :messages="$errors->get('permissions')" />
                            @endif

                            <input type="search" x-model="filterText" placeholder="{{ __('Search permissions…') }}"
                                class="mb-3 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm text-sm">

                            <div class="border border-gray-200 dark:border-gray-700 rounded-md max-h-[28rem] overflow-y-auto">
                                @foreach ($permissionsByModule as $module => $modulePermissions)
                                    @php $moduleNames = $modulePermissions->pluck('name'); @endphp
                                    <div x-show="moduleMatches('{{ $module }}', @js($modulePermissions->pluck('description')))"
                                        class="border-b border-gray-100 dark:border-gray-700/60 last:border-b-0">
                                        <button type="button"
                                            class="w-full flex items-center justify-between gap-2 px-3 py-2 bg-gray-50 dark:bg-gray-700/40 hover:bg-gray-100 dark:hover:bg-gray-700/70 text-left"
                                            @click="toggleModule('{{ $module }}')">
                                            <span class="flex items-center gap-2 min-w-0">
                                                <input type="checkbox" @click.stop
                                                    :checked="allSelectedInModule(@js($moduleNames))"
                                                    @change="toggleModuleAll(@js($moduleNames), $event.target.checked)">
                                                <span class="text-sm font-medium text-gray-800 dark:text-gray-100 truncate">{{ ucfirst($module) }}</span>
                                            </span>
                                            <span class="flex items-center gap-2 shrink-0 ml-2">
                                                <span class="text-xs text-gray-500 dark:text-gray-400"
                                                    x-text="countSelected(@js($moduleNames)) + ' / {{ $modulePermissions->count() }}'"></span>
                                                <i class="bi bi-chevron-down text-xs text-gray-400 transition-transform duration-150"
                                                    :class="(filterText.length > 0 || isModuleOpen('{{ $module }}')) ? 'rotate-180' : ''"></i>
                                            </span>
                                        </button>
                                        <div class="px-3 py-2 flex flex-wrap gap-1.5"
                                            x-show="filterText.length > 0 || isModuleOpen('{{ $module }}')">
                                            @foreach ($modulePermissions as $permission)
                                                <label class="flex items-center gap-1.5 bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-200 text-xs px-2 py-1 rounded cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800">
                                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                        x-model="selectedPermissions">
                                                    {{ $permission->description ?? $permission->name }}
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex justify-center">
                            <x-primary-button>
                                <span x-text="mode === 'create' ? '{{ __('Create Role') }}' : '{{ __('Update Role') }}'"></span>
                            </x-primary-button>
                        </div>
                    </form>
                </div>
                @endcanany
            </div>
        </div>

        {{-- Custom CSS for the Delete Modal to bypass Tailwind compilation issues --}}
        <style>
            .custom-modal-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.6);
                z-index: 9998;
                backdrop-filter: blur(2px);
            }
            .custom-modal-wrapper {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
            }
            .custom-modal-box {
                background-color: #ffffff;
                border-radius: 12px;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                width: 100%;
                max-width: 480px;
                overflow: hidden;
                font-family: inherit;
                border: 1px solid #e5e7eb;
            }
            .custom-modal-body {
                padding: 24px;
                display: flex;
                align-items: flex-start;
                gap: 16px;
            }
            .custom-modal-icon {
                flex-shrink: 0;
                width: 48px;
                height: 48px;
                border-radius: 50%;
                background-color: #fee2e2;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #dc2626;
                font-size: 24px;
            }
            .custom-modal-text {
                margin-top: 4px;
            }
            .custom-modal-text h3 {
                margin: 0 0 8px 0;
                font-size: 18px;
                font-weight: 600;
                color: #111827;
                line-height: 1.2;
            }
            .custom-modal-text p {
                margin: 0;
                font-size: 14px;
                line-height: 1.5;
                color: #4b5563;
            }
            .custom-modal-footer {
                background-color: #f9fafb;
                padding: 16px 24px;
                display: flex;
                justify-content: flex-end;
                gap: 12px;
                border-top: 1px solid #e5e7eb;
            }
            .custom-btn {
                padding: 8px 16px;
                font-size: 14px;
                font-weight: 600;
                border-radius: 8px;
                cursor: pointer;
                transition: all 0.2s;
                display: inline-flex;
                justify-content: center;
                align-items: center;
            }
            .custom-btn-cancel {
                background-color: #ffffff;
                color: #374151;
                border: 1px solid #d1d5db;
            }
            .custom-btn-cancel:hover {
                background-color: #f3f4f6;
            }
            .custom-btn-danger {
                background-color: #dc2626;
                color: #ffffff;
                border: 1px solid #dc2626;
            }
            .custom-btn-danger:hover {
                background-color: #b91c1c;
                border-color: #b91c1c;
            }

            /* Dark mode support */
            @media (prefers-color-scheme: dark) {
                .custom-modal-box { 
                    background-color: #1f2937; 
                    border-color: #374151;
                }
                .custom-modal-text h3 { color: #f9fafb; }
                .custom-modal-text p { color: #9ca3af; }
                .custom-modal-icon { 
                    background-color: rgba(153, 27, 27, 0.2); 
                    color: #ef4444; 
                }
                .custom-modal-footer { 
                    background-color: rgba(17, 24, 39, 0.5); 
                    border-top-color: #374151; 
                }
                .custom-btn-cancel { 
                    background-color: #374151; 
                    color: #f9fafb; 
                    border-color: #4b5563; 
                }
                .custom-btn-cancel:hover { 
                    background-color: #4b5563; 
                }
            }
        </style>

        {{-- Delete confirmation (Standard CSS) --}}
        <div x-show="deleteTarget" style="display: none;" x-cloak>
            
            <div class="custom-modal-backdrop" 
                 x-show="deleteTarget" 
                 x-transition.opacity></div>

            <div class="custom-modal-wrapper"
                 x-show="deleteTarget"
                 x-transition
                 @click.self="deleteTarget = null"
                 @keydown.escape.window="deleteTarget = null">
                 
                <div class="custom-modal-box">
                    <div class="custom-modal-body">
                        <div class="custom-modal-icon">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div class="custom-modal-text">
                            <h3>{{ __('Delete this role?') }}</h3>
                            <p>
                                {{ __('Are you sure you want to delete') }} "<strong style="color: inherit;" x-text="deleteTarget?.name"></strong>"?
                                {{ __('Anyone currently assigned this role will immediately lose these permissions. This action cannot be undone.') }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="custom-modal-footer">
                        <button type="button" @click="deleteTarget = null" class="custom-btn custom-btn-cancel">
                            {{ __('Cancel') }}
                        </button>
                        <button type="button" @click="confirmDelete()" class="custom-btn custom-btn-danger">
                            {{ __('Yes, delete') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function rolesManager({ allPermissionNames, initialEditorOpen, initialMode, initialRoleId, initialRoleName, initialSelected }) {
            return {
                allPermissionNames,
                editorOpen: initialEditorOpen,
                mode: initialMode,
                roleId: initialRoleId,
                roleName: initialRoleName,
                selectedPermissions: initialSelected || [],
                filterText: '',
                openModules: {},
                deleteTarget: null,
                updateUrlTemplate: '{{ route('roles.update', '__ID__') }}',

                startCreate() {
                    this.mode = 'create';
                    this.roleId = null;
                    this.roleName = '';
                    this.selectedPermissions = [];
                    this.filterText = '';
                    this.editorOpen = true;
                },

                startEdit(id, name, permissionNames) {
                    this.mode = 'edit';
                    this.roleId = id;
                    this.roleName = name;
                    this.selectedPermissions = [...permissionNames];
                    this.filterText = '';
                    this.editorOpen = true;
                    this.$nextTick(() => {
                        if (window.innerWidth < 1024 && this.$refs.editor) {
                            this.$refs.editor.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    });
                },

                selectAll() {
                    this.selectedPermissions = [...this.allPermissionNames];
                },
                clearAll() {
                    this.selectedPermissions = [];
                },

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
                countSelected(names) {
                    return names.filter(n => this.selectedPermissions.includes(n)).length;
                },
                allSelectedInModule(names) {
                    return names.length > 0 && names.every(n => this.selectedPermissions.includes(n));
                },
                toggleModuleAll(names, checked) {
                    if (checked) {
                        names.forEach(n => {
                            if (!this.selectedPermissions.includes(n)) this.selectedPermissions.push(n);
                        });
                    } else {
                        this.selectedPermissions = this.selectedPermissions.filter(n => !names.includes(n));
                    }
                },

                confirmDelete() {
                    if (!this.deleteTarget) return;
                    const form = document.getElementById('delete-form-' + this.deleteTarget.id);
                    if (form) form.submit();
                    this.deleteTarget = null;
                },
            };
        }
    </script>
</x-app-layout>