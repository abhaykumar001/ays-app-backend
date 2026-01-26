<x-app-layout>
    <div x-data="maintenanceModal({
        name: @js(old('name')),
        description: @js(old('description')),
        default_cost: @js(old('default_cost')),
        estimated_duration: @js(old('estimated_duration')),
        required_materials: @js(old('required_materials')),
        special_instructions: @js(old('special_instructions')),
        is_active: @js(old('is_active', true)),
    })" x-init="@if ($errors->any()) show = true @endif">

        <x-slot name="header">
            <x-nav-link :href="route('maintanance.index')" :active="true">
                {{ __('Maintenance Services') }}
            </x-nav-link>
        </x-slot>

        <div class="py-6">
            <div class="sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                    <!-- Header -->
                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div class="my-auto">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Maintenance Services
                            </h2>
                        </div>

                        @can('create_maintanance')
                            <div class="md:text-end">
                                <x-button-link href="#" @click.prevent="openCreate()">
                                    Create Service
                                </x-button-link>
                            </div>
                        @endcan
                    </div>

                    <!-- Table -->
                    @can('view_maintanance')
                        @php
                            $actions = [];

                            if (auth()->user()->can('edit_maintanance')) {
                                $actions[] = [
                                    'type' => 'edit',
                                    'label' => 'Edit',
                                    'click' => 'true',
                                ];
                            }

                            if (auth()->user()->can('delete_maintanance')) {
                                $actions[] = [
                                    'type' => 'delete',
                                    'url' => 'maintanance.destroy',
                                    'label' => 'Delete',
                                ];
                            }

                            $columns = collect([
                                ['label' => '#'],
                                ['label' => 'Service Name', 'key' => 'name'],
                                ['label' => 'Cost', 'key' => 'default_cost'],
                                ['label' => 'Duration', 'key' => 'estimated_duration'],
                                [
                                    'label' => 'Status',
                                    'key' => 'status',
                                    'badge' => true,
                                    'badgeMap' => [
                                        'active' => ['text' => 'Active', 'color' => 'bg-green-600 text-white'],
                                        'inactive' => ['text' => 'Inactive', 'color' => 'bg-red-600 text-white'],
                                    ],
                                ],
                                count($actions) ? ['label' => 'Actions', 'actions' => $actions] : null,
                            ])->filter()->values()->toArray();
                        @endphp

                        <x-datatable :data="$maintanances" :columns="$columns" />
                    @endcan
                </div>
            </div>
        </div>

        <!-- ================= MODAL ================= -->
        <div x-show="show" x-cloak class="fixed inset-0 z-50 flex">
            <div class="fixed inset-0 bg-black/40" @click="close()" x-transition.opacity></div>

            <div class="relative ml-auto w-full max-w-md h-full bg-white dark:bg-gray-900 shadow-xl p-6 overflow-y-auto"
                x-show="show"
                x-transition:enter="transform transition ease-in-out duration-300"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full">

                <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">
                    <span x-text="isEdit ? 'Edit Maintenance Service' : 'Create Maintenance Service'"></span>
                </h2>

                <!-- Errors -->
                @if ($errors->any())
                    <div class="mb-4 rounded bg-red-50 border border-red-200 p-3 text-sm text-red-700">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form :action="isEdit ? updateUrl : createUrl" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="isEdit">@method('PUT')</template>

                    <!-- Name -->
                    <div>
                        <x-input-label value="Service Name" />
                        <x-text-input name="name" x-model="form.name" class="w-full" required />
                    </div>

                    <!-- Description -->
                    <div>
                        <x-input-label value="Description" />
                        <x-text-textarea name="description" x-model="form.description" class="w-full" />
                    </div>

                    <!-- Cost -->
                    <div>
                        <x-input-label value="Default Cost" />
                        <x-text-input type="number" step="0.01" name="default_cost" x-model="form.default_cost" class="w-full" />
                    </div>

                    <!-- Duration -->
                    <div>
                        <x-input-label value="Estimated Duration" />
                        <x-text-input name="estimated_duration" x-model="form.estimated_duration" class="w-full" />
                    </div>

                    <!-- Materials -->
                    <div>
                        <x-input-label value="Required Materials" />
                        <x-text-textarea name="required_materials" x-model="form.required_materials" class="w-full" />
                    </div>

                    <!-- Instructions -->
                    <div>
                        <x-input-label value="Special Instructions" />
                        <x-text-textarea name="special_instructions" x-model="form.special_instructions" class="w-full" />
                    </div>
                    <!-- Image -->
                    <div>
                        <x-input-label for="image" :value="__('Image')" />

                        <!-- Preview -->
                        <div class="mb-2" x-show="imagePreview" x-transition>
                            <img :src="imagePreview" class="h-24 w-24 rounded object-cover border" />
                        </div>

                        <x-text-input id="image" name="image" type="file" class="mt-1 block w-full"
                            accept="image/*" @change="previewImage" />
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>
                    <!-- Status -->
                    <div>
                        <x-input-label value="Status" />
                        <select name="is_active" x-model="form.is_active"
                            class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm">
                            <option :value="true">Active</option>
                            <option :value="false">Inactive</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-2 pt-4">
                        <x-secondary-button type="button" @click="close()">Cancel</x-secondary-button>
                        <x-primary-button type="submit">Save</x-primary-button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= SCRIPT ================= -->
        <script>
            function maintenanceModal(oldData = {}) {
                return {
                    show: false,
                    isEdit: false,
                    createUrl: "{{ route('maintanance.store') }}",
                    updateUrl: "",
                    imagePreview: oldData.image_url ?? null,
                    form: {
                        name: oldData.name ?? '',
                        description: oldData.description ?? '',
                        default_cost: oldData.default_cost ?? '',
                        estimated_duration: oldData.estimated_duration ?? '',
                        required_materials: oldData.required_materials ?? '',
                        special_instructions: oldData.special_instructions ?? '',
                        is_active: oldData.is_active ?? true,
                    },

                    openCreate() {
                        this.isEdit = false;
                        this.reset();
                        this.show = true;
                    },

                    openEdit(id) {
                        this.isEdit = true;
                        this.show = true;
                        this.updateUrl = `/dashboard/maintanance/${id}`;

                        fetch(`/dashboard/maintanance/${id}/edit`, {
                            headers: { 'Accept': 'application/json' }
                        })
                        .then(res => res.json())
                        .then(data => this.form = data)
                        .then(data => this.imagePreview = data.image ?? null)
                        .catch(() => this.close());
                    },
                     previewImage(event) {
                        const file = event.target.files[0]
                        if (!file) return
                        const reader = new FileReader()
                        reader.onload = e => this.imagePreview = e.target.result
                        reader.readAsDataURL(file)
                    },
                    close() { this.show = false },

                    reset() {
                        this.form = {
                            name: '',
                            description: '',
                            default_cost: '',
                            estimated_duration: '',
                            required_materials: '',
                            special_instructions: '',
                            is_active: true,
                        };
                        this.imagePreview = null;
                    }
                }
            }
        </script>
    </div>
</x-app-layout>
