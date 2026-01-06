<x-app-layout>
    <div x-data="virtualToursModal()" x-init="@if ($errors->any()) show = true @endif">

        <x-slot name="header">
            <x-nav-link :href="route('projects.index')" :active="true">
                Virtual Tours – {{ $project->name }}
            </x-nav-link>
        </x-slot>

        <div class="py-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">

                @can('create_virtual_tours')
                    <div class="text-right mb-4">
                        <x-button-link href="#" @click.prevent="openCreate()">Create Virtual Tour</x-button-link>
                    </div>
                @endcan

                @php
                    $actions = [];

                    if (auth()->user()->can('edit_virtual_tours')) {
                        $actions[] = ['type' => 'edit', 'label' => 'Edit', 'click' => true];
                    }

                    if (auth()->user()->can('delete_virtual_tours')) {
                        $actions[] = [
                            'type' => 'delete',
                            'url' => 'projects.virtualTours.destroy',
                            'params' => [$project->id],
                            'label' => 'Delete',
                        ];
                    }

                    $columns = collect([
                        ['label' => '#'],
                        ['label' => 'Title', 'key' => 'title'],
                        ['label' => 'Type', 'key' => 'type'],
                        ['label' => 'URL/File', 'key' => 'url'],
                        [
                            'label' => 'Status',
                            'key' => 'is_active',
                            'badge' => true,
                            'badgeMap' => [
                                1 => ['text' => 'Active', 'color' => 'bg-green-200'],
                                0 => ['text' => 'Inactive', 'color' => 'bg-yellow-200'],
                            ],
                        ],
                        count($actions) ? ['label' => 'Actions', 'actions' => $actions] : null,
                    ])
                        ->filter()
                        ->values()
                        ->toArray();
                @endphp

                <x-datatable :data="$virtualTours" :columns="$columns" />

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
                    <span x-text="isEdit ? 'Edit Virtual Tour' : 'Create Virtual Tour'"></span>
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

                    <!-- Title -->
                    <div>
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                            x-model="form.title" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <!-- Type -->
                    <div>
                        <x-input-label for="type" :value="__('Type')" />
                        <select id="type" name="type" x-model="form.type"
                            class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm">
                            <option value="360">360</option>
                            <option value="video">Video</option>
                            <option value="iframe">Iframe</option>
                            <option value="link">Link</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <!-- URL: show only if type is not video -->
                    <div x-show="form.type !== 'video'" x-cloak>
                        <x-input-label for="url" :value="__('URL')" />
                        <x-text-input id="url" name="url" type="text" class="mt-1 block w-full"
                            x-model="form.url" />
                        <x-input-error :messages="$errors->get('url')" class="mt-2" />
                    </div>

                    <!-- File: show only if type is video -->
                    <div x-show="form.type === 'video'" x-cloak>
                        <x-input-label for="file" :value="'Video File'" />

                        <div class="mb-2" x-show="filePreview" x-transition>
                            <a :href="filePreview" target="_blank" class="text-primary underline">View current
                                video</a>
                        </div>

                        <x-text-input id="file" name="file" type="file" class="mt-1 block w-full"
                            @change="previewFile" />
                        <x-input-error :messages="$errors->get('file')" class="mt-2" />
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

        <script>
            function virtualToursModal() {
                return {
                    show: false,
                    isEdit: false,
                    projectId: {{ $project->id }},
                    createUrl: "{{ route('projects.virtualTours.store', $project) }}",
                    updateUrl: "",
                    filePreview: null,
                    form: {
                        title: '',
                        type: 'link',
                        url: '',
                        file: null,
                        is_active: 1,
                    },
                    openCreate() {
                        this.isEdit = false;
                        this.reset();
                        this.show = true;
                    },
                    openEdit(id) {
                        this.isEdit = true;
                        this.show = true;
                        this.updateUrl = `/dashboard/projects/${this.projectId}/virtualTours/${id}`;

                        fetch(`/dashboard/projects/${this.projectId}/virtualTours/${id}/edit`, {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                this.form = data;
                                this.filePreview = data.file ? `/storage/${data.file}` : null;
                            })
                            .catch(err => {
                                console.error(err);
                                this.close();
                            });
                    },
                    previewFile(event) {
                        const file = event.target.files[0];
                        if (!file) return;
                        this.filePreview = URL.createObjectURL(file);
                        this.form.file = file;
                    },
                    close() {
                        this.show = false;
                    },
                    reset() {
                        this.form = {
                            title: '',
                            type: 'link',
                            url: '',
                            file: null,
                            is_active: 1,
                        };
                        this.filePreview = null;
                    }
                }
            }
        </script>

    </div>
</x-app-layout>
