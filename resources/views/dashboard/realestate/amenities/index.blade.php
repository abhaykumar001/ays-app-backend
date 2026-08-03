<x-app-layout>
    <div x-data="amenityModal({
        name: @js(old('name')),
        description: @js(old('description')),
        status: @js(old('status', 'active')),
        image_url: @js(old('image_url')),
        {{-- optional if you store previous image --}}
    })" x-init="@if ($errors->any()) show = true @endif">
        <x-slot name="header">
            <x-nav-link :href="route('amenities.index')" :active="true">
                {{ __('Amenities Data') }}
            </x-nav-link>
        </x-slot>

        <div class="py-6">
            <div class="sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                    <!-- Header -->
                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div class="my-auto">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Amenities Data for Website
                            </h2>
                        </div>

                        @can('create_amenities')
                            <div class="md:text-end">
                                <x-button-link href="#" @click.prevent="openCreate()">
                                    Create Amenity Data
                                </x-button-link>
                            </div>
                        @endcan
                    </div>

                    <!-- Table -->
                    @can('view_amenities')
                        @php
                            $actions = [];

                            if (auth()->user()->can('edit_amenities')) {
                                $actions[] = [
                                    'type' => 'edit',
                                    'label' => 'Edit',
                                    'click' => 'true',
                                ];
                            }

                            if (auth()->user()->can('delete_amenities')) {
                                $actions[] = [
                                    'type' => 'delete',
                                    'url' => 'amenities.destroy',
                                    'label' => 'Delete',
                                ];
                            }

                            $columns = collect([
                                ['label' => '#'],
                                ['label' => 'Name', 'key' => 'name'],
                                ['label' => 'image', 'key' => 'image'],
                                ['label' => 'Logo', 'key' => 'logo'],
                                [
                                    'label' => 'Status',
                                    'key' => 'is_active',
                                    'badge' => true,
                                    'badgeMap' => [
                                    1 => [
                                        'text' => 'Active',
                                        'color' => 'bg-green-200 text-green-800'
                                    ],
                                    0 => [
                                        'text' => 'Inactive',
                                        'color' => 'bg-yellow-200 text-yellow-800'
                                    ]
                                ],
                                ],
                                count($actions) ? ['label' => 'Actions', 'actions' => $actions] : null,
                            ])
                                ->filter()
                                ->values()
                                ->toArray();
                        @endphp

                        <x-datatable :data="$amenities" :columns="$columns" />
                    @endcan
                </div>
            </div>
        </div>

        <!-- ================= MODAL ================= -->
        <div x-show="show" x-cloak class="fixed inset-0 z-50 flex">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-black/40" @click="close()" x-transition.opacity></div>

            <!-- Panel -->
            <div class="relative ml-auto w-full max-w-md h-full bg-white dark:bg-gray-900 shadow-xl p-6 overflow-y-auto"
                x-show="show" x-transition:enter="transform transition ease-in-out duration-300"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

                <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">
                    <span x-text="isEdit ? 'Edit Amenity' : 'Create Amenity'"></span>
                </h2>

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-4 rounded bg-red-50 border border-red-200 p-3 text-sm text-red-700">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form :action="isEdit ? updateUrl : createUrl" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf
                    <template x-if="isEdit">
                        @method('PUT')
                    </template>

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Full Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                            x-model="form.name" required autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Designation -->
                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <x-text-input id="description" name="description" type="text" class="mt-1 block w-full"
                            x-model="form.description" required autocomplete="description" />
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
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
                    <div>
                        <x-input-label for="logo" :value="__('Logo')" />

                        <!-- Preview -->
                        <div class="mb-2" x-show="logoPreview" x-transition>
                            <img :src="logoPreview" class="h-24 w-24 rounded object-cover border" />
                        </div>

                        <x-text-input id="logo" name="logo" type="file" class="mt-1 block w-full"
                            accept="image/*" @change="previewLogo" />
                        <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                    </div>
                    <!-- Status -->
                    <div>
                        <x-input-label for="is_active" :value="__('Status')" />
                        <select id="is_active" name="is_active" x-model="form.is_active"
                            class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-2 pt-4">
                        <x-secondary-button type="button" @click="close()">{{ __('Cancel') }}</x-secondary-button>
                        <x-primary-button type="submit">{{ __('Save') }}</x-primary-button>
                    </div>
                </form>

            </div>
        </div>

        <!-- ================= SCRIPT ================= -->
        <script>
            function amenityModal(oldData = {}) {
                return {
                    show: false,
                    isEdit: false,

                    createUrl: "{{ route('amenities.store') }}",
                    updateUrl: "",

                    imagePreview: oldData.image_url ?? null,
                    logoPreview: oldData.logo_url ?? null,

                    form: {
                        name: oldData.name ?? '',
                        description: oldData.description ?? '',
                        is_active: oldData.is_active ?? 1,
                    },


                    openCreate() {
                        this.isEdit = false;
                        this.reset();
                        this.show = true;
                    },

                    openEdit(teamId) {
                        this.isEdit = true;
                        this.show = true;

                        // Set update URL
                        this.updateUrl = `/dashboard/amenities/${teamId}`;

                        // Fetch team data via AJAX
                        fetch(`/dashboard/amenities/${teamId}/edit`, {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => {
                                if (!res.ok) throw new Error('Network response was not ok');
                                return res.json();
                            })
                            .then(team => {
                                this.form = {
                                    name: team.name,
                                    description: team.description,
                                    is_active: team.is_active ? 1 : 0,
                                };
                                this.imagePreview = team.image || null;
                                this.logoPreview = team.logo || null;
                            })
                            .catch(err => {
                                console.error('Error fetching team:', err);
                                this.close();
                            });
                    },

                    previewImage(event) {
                        const file = event.target.files[0]
                        if (!file) return
                        const reader = new FileReader()
                        reader.onload = e => this.imagePreview = e.target.result
                        reader.readAsDataURL(file)
                    },
                    previewLogo(event) {
                        const file = event.target.files[0]
                        if (!file) return
                        const reader = new FileReader()
                        reader.onload = e => this.logoPreview = e.target.result
                        reader.readAsDataURL(file)
                    },


                    close() {
                        this.show = false;
                    },

                    reset() {
                        this.form = {
                            name: '',
                            description: '',
                            is_active: 1,
                        };
                        this.imagePreview = null;
                        this.logoPreview = null;
                    }
                }
            }
        </script>
    </div>
</x-app-layout>
