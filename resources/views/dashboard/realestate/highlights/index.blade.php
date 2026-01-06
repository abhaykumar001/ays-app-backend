<x-app-layout>
    <div x-data="highlightModal({
        project_id: @js(old('project_id')),
        title: @js(old('title')),
        description: @js(old('description')),
        is_featured: @js(old('is_featured')),
        sort_order: @js(old('sort_order')),
        is_active: @js(old('is_active')),
        image_url: @js(old('image_url')),
        {{-- optional if you store previous image --}}
    })" x-init="@if ($errors->any()) show = true @endif">
        <x-slot name="header">
            <x-nav-link :href="route('projects.index')" :active="true">
                {{ __('Highlight Data - ') }} {{ $project->name }}
            </x-nav-link>
        </x-slot>

        <div class="py-6">
            <div class="sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                    <!-- Header -->
                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div class="my-auto">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Highlight Data for {{ $project->name }}
                            </h2>
                        </div>

                        @can('create_highlights')
                            <div class="md:text-end">
                                <x-button-link href="#" @click.prevent="openCreate()">
                                    Create Highlight Data
                                </x-button-link>
                            </div>
                        @endcan
                    </div>

                    <!-- Table -->
                    @can('view_highlights')
                        @php
                            $actions = [];

                            if (auth()->user()->can('edit_highlights')) {
                                $actions[] = [
                                    'type' => 'edit',
                                    'label' => 'Edit',
                                    'click' => 'true',
                                ];
                            }

                            if (auth()->user()->can('delete_highlights')) {
                                $actions[] = [
                                    'type' => 'delete',
                                    'url' => 'projects.highlights.destroy',
                                    'params' => [$project->id], // nearbyPlace id will be added in blade
                                    'label' => 'Delete',
                                ];
                            }

                            $columns = collect([
                                ['label' => '#'],
                                ['label' => 'Title', 'key' => 'title'],
                                ['label' => 'Description', 'key' => 'description'],
                                [
                                    'label' => 'Featured',
                                    'key' => 'is_featured',
                                    'badge' => true,
                                    'badgeMap' => [
                                        1 => [
                                            'text' => 'No',
                                            'color' => 'bg-green-200 text-green-800',
                                        ],
                                        0 => [
                                            'text' => 'Yes',
                                            'color' => 'bg-yellow-200 text-yellow-800',
                                        ],
                                    ],
                                ],
                                [
                                    'label' => 'Status',
                                    'key' => 'is_active',
                                    'badge' => true,
                                    'badgeMap' => [
                                        1 => [
                                            'text' => 'Active',
                                            'color' => 'bg-green-200 text-green-800',
                                        ],
                                        0 => [
                                            'text' => 'Inactive',
                                            'color' => 'bg-yellow-200 text-yellow-800',
                                        ],
                                    ],
                                ],
                                ['label' => 'Order', 'key' => 'sort_order'],
                                count($actions) ? ['label' => 'Actions', 'actions' => $actions] : null,
                            ])
                                ->filter()
                                ->values()
                                ->toArray();
                        @endphp

                        <x-datatable :data="$highlights" :columns="$columns" />
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
                    <span x-text="isEdit ? 'Edit Highlight Data' : 'Create Highlight Data'"></span>
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
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                            x-model="form.title" required autocomplete="title" />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <x-text-textarea id="description" name="description" type="text" class="mt-1 block w-full"
                            x-model="form.description" required autocomplete="description"> </x-text-textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
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
                        <x-input-label for="sort_order" :value="__('Sort Order')" />
                        <x-text-input id="sort_order" name="sort_order" type="number" class="mt-1 block w-full"
                            x-model="form.sort_order" required autocomplete="sort_order" />
                        <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="is_featured" :value="__('Is Featured')" />
                        <select id="is_featured" name="is_featured" x-model="form.is_featured"
                            class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        <x-input-error :messages="$errors->get('is_featured')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="is_active" :value="__('Status')" />
                        <select id="is_active" name="is_active" x-model="form.is_active"
                            class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm">
                            <option value="true">Active</option>
                            <option value="false">Inactive</option>
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
            function highlightModal() {
                return {
                    show: false,
                    isEdit: false,

                    projectId: {{ $project->id }},

                    createUrl: "{{ route('projects.highlights.store', $project) }}",
                    updateUrl: "",

                    form: {
                        title: '',
                        description: '',
                        is_featured: 0,
                        sort_order: 0,
                        is_active: true,
                    },
                    imagePreview: null,
                    openCreate() {
                        this.isEdit = false;
                        this.reset();
                        this.show = true;
                    },

                    openEdit(nearbyPlaceId) {
                        this.isEdit = true;
                        this.show = true;

                        // Correct update URL
                        this.updateUrl =
                            `/dashboard/projects/${this.projectId}/highlights/${nearbyPlaceId}`;

                        // Correct edit fetch URL
                        fetch(
                                `/dashboard/projects/${this.projectId}/highlights/${nearbyPlaceId}/edit`, {
                                    headers: {
                                        'Accept': 'application/json'
                                    }
                                }
                            )
                            .then(res => {
                                if (!res.ok) throw new Error('Failed to fetch data');
                                return res.json();
                            })
                            .then(place => {
                                this.form = {
                                    title: place.title,
                                    description: place.description,
                                    is_featured: place.is_featured,
                                    sort_order: place.sort_order,
                                    is_active: place.is_active,
                                };
                                this.imagePreview = place.image ?? null;
                            })
                            .catch(err => {
                                console.error(err);
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
                    close() {
                        this.show = false;
                    },

                    reset() {
                        this.form = {
                            title: '',
                            description: '',
                            is_featured: 0,
                            sort_order: 0,
                            is_active: true,
                        };
                        this.imagePreview = null;
                    }
                }
            }
        </script>

    </div>
</x-app-layout>
