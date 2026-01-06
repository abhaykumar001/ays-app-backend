<x-app-layout>
    <div x-data="constructionUpdatesModal()" x-init="@if ($errors->any()) show = true @endif">

        <x-slot name="header">
            <x-nav-link :href="route('projects.index')" :active="true">
                Construction Updates – {{ $project->name }}
            </x-nav-link>
        </x-slot>

        <div class="py-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">

                @can('create_construction_updates')
                    <div class="text-right mb-4">
                        <x-button-link href="#" @click.prevent="openCreate()">Create Update</x-button-link>
                    </div>
                @endcan

                @php
                    $actions = [];

                    if (auth()->user()->can('edit_construction_updates')) {
                        $actions[] = ['type' => 'edit', 'label' => 'Edit', 'click' => true];
                    }

                    if (auth()->user()->can('delete_construction_updates')) {
                        $actions[] = [
                            'type' => 'delete',
                            'url' => 'projects.constructionUpdates.destroy',
                            'params' => [$project->id],
                            'label' => 'Delete',
                        ];
                    }

                    $columns = collect([
                        ['label' => '#'],
                        ['label' => 'Title', 'key' => 'title'],
                        ['label' => 'Stage', 'key' => 'stage'],
                        ['label' => 'Progress %', 'key' => 'progress_percentage'],
                        ['label' => 'Date', 'key' => 'update_date'],
                        ['label' => 'Media', 'key' => 'media_preview'],
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
                    ])->filter()->values()->toArray();
                @endphp

                <x-datatable :data="$updates" :columns="$columns" />

            </div>
        </div>

        <!-- ================= MODAL ================= -->
        <div x-show="show" x-cloak class="fixed inset-0 z-50 flex">
            <div class="fixed inset-0 bg-black/40" @click="close()" x-transition.opacity></div>

            <div class="relative ml-auto w-full max-w-md h-full bg-white dark:bg-gray-900 shadow-xl p-6 overflow-y-auto"
                x-show="show" x-transition:enter="transform transition ease-in-out duration-300"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

                <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">
                    <span x-text="isEdit ? 'Edit Update' : 'Create Update'"></span>
                </h2>

                @if ($errors->any())
                    <div class="mb-4 rounded bg-red-50 border border-red-200 p-3 text-sm text-red-700">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form :action="isEdit ? updateUrl : createUrl" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <template x-if="isEdit">@method('PUT')</template>

                    <!-- Title -->
                    <div>
                        <x-input-label for="title" :value="'Title'" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                      x-model="form.title" required />
                    </div>

                    <!-- Stage -->
                    <div>
                        <x-input-label for="stage" :value="'Stage'" />
                        <select id="stage" name="stage" x-model="form.stage"
                                class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm">
                            <option value="">Select Stage</option>
                            <option value="foundation">Foundation</option>
                            <option value="structure">Structure</option>
                            <option value="facade">Facade</option>
                            <option value="interior">Interior</option>
                            <option value="finishing">Finishing</option>
                        </select>
                    </div>

                    <!-- Progress -->
                    <div>
                        <x-input-label for="progress_percentage" :value="'Progress (%)'" />
                        <x-text-input id="progress_percentage" name="progress_percentage" type="number" min="0" max="100"
                                      x-model="form.progress_percentage" class="mt-1 block w-full" />
                    </div>

                    <!-- Date -->
                    <div>
                        <x-input-label for="update_date" :value="'Update Date'" />
                        <x-text-input id="update_date" name="update_date" type="date"
                                      x-model="form.update_date" class="mt-1 block w-full" />
                    </div>

                    <!-- Description -->
                    <div>
                        <x-input-label for="description" :value="'Description'" />
                        <x-text-textarea id="description" name="description"
                                         x-model="form.description" class="mt-1 block w-full"></x-text-textarea>
                    </div>

                    <!-- Media Type -->
                    <div>
                        <x-input-label :value="'Media Type'" />
                        <select x-model="mediaType" class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm">
                            <option value="file">File (Image/Video)</option>
                            <option value="link">External Link</option>
                        </select>
                    </div>

                    <!-- File -->
                    <div x-show="mediaType === 'file'" x-cloak>
                        <x-input-label for="file" :value="'Upload File'" />
                        <div class="mb-2" x-show="filePreview" x-transition>
                            <a :href="filePreview" target="_blank" class="text-primary underline">View current file</a>
                        </div>
                        <x-text-input id="file" name="file" type="file" class="mt-1 block w-full" @change="previewFile" />
                    </div>

                    <!-- Link -->
                    <div x-show="mediaType === 'link'" x-cloak>
                        <x-input-label for="link" :value="'External Link'" />
                        <x-text-input id="link" name="link" type="url" x-model="form.link" class="mt-1 block w-full" />
                    </div>

                    <!-- Status -->
                    <div>
                        <x-input-label for="is_active" :value="'Status'" />
                        <select id="is_active" name="is_active" x-model="form.is_active"
                                class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
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

        <script>
            function constructionUpdatesModal() {
                return {
                    show: false,
                    isEdit: false,
                    projectId: {{ $project->id }},
                    createUrl: "{{ route('projects.constructionUpdates.store', $project) }}",
                    updateUrl: "",
                    mediaType: 'file',
                    filePreview: null,
                    form: {
                        title: '',
                        stage: '',
                        progress_percentage: '',
                        update_date: '',
                        description: '',
                        is_active: 1,
                        file: null,
                        link: '',
                    },
                    openCreate() {
                        this.isEdit = false;
                        this.reset();
                        this.show = true;
                    },
                    openEdit(id) {
                        this.isEdit = true;
                        this.show = true;
                        this.updateUrl = `/dashboard/projects/${this.projectId}/constructionUpdates/${id}`;

                        fetch(`/dashboard/projects/${this.projectId}/constructionUpdates/${id}/edit`, {
                            headers: { 'Accept': 'application/json' }
                        }).then(res => res.json())
                          .then(data => {
                              this.form = data;
                              if(data.file_url) {
                                  this.mediaType = 'file';
                                  this.filePreview = data.file_url;
                              } else if(data.link) {
                                  this.mediaType = 'link';
                              }
                          }).catch(err => { console.error(err); this.close(); });
                    },
                    previewFile(event) {
                        const file = event.target.files[0];
                        if(!file) return;
                        this.filePreview = URL.createObjectURL(file);
                        this.form.file = file;
                    },
                    close() {
                        this.show = false;
                    },
                    reset() {
                        this.form = {
                            title: '',
                            stage: '',
                            progress_percentage: '',
                            update_date: '',
                            description: '',
                            is_active: 1,
                            file: null,
                            link: '',
                        };
                        this.mediaType = 'file';
                        this.filePreview = null;
                    }
                }
            }
        </script>

    </div>
</x-app-layout>
