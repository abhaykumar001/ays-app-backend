<x-app-layout>
    <div x-data="constructionUpdatesModal()" x-init="@if ($errors->any()) show = true @endif">

        <x-slot name="header">
            <x-nav-link :href="route('projects.index')" :active="true">
                Construction Updates – {{ $project->name }}
            </x-nav-link>
        </x-slot>

        <div class="py-6 space-y-6">
            @if (session('success'))
                <div class="p-3 rounded bg-green-50 border border-green-200 text-green-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Overall Progress</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                    Auto-calculated from each stage's progress weighted by its contribution to the project
                    (Σ Progress % × Weight % ÷ 100 — {{ $autoProgress }}% right now).
                    Leave the override blank to use that automatically, or set a fixed number to show instead.
                    Manage stage weights under <a href="{{ route('constructionStages.index') }}" class="underline">Construction Stages</a>.
                </p>

                <div class="overflow-x-auto mb-4">
                    <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr class="text-left text-gray-600 dark:text-gray-300">
                                <th class="px-3 py-2">Stage</th>
                                <th class="px-3 py-2">Progress %</th>
                                <th class="px-3 py-2">Weight %</th>
                                <th class="px-3 py-2">Contribution %</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($breakdown as $row)
                                <tr class="text-gray-700 dark:text-gray-200">
                                    <td class="px-3 py-2">{{ $row['name'] }}</td>
                                    <td class="px-3 py-2">{{ rtrim(rtrim(number_format($row['progress'], 2), '0'), '.') }}%</td>
                                    <td class="px-3 py-2">{{ rtrim(rtrim(number_format($row['weight'], 2), '0'), '.') }}%</td>
                                    <td class="px-3 py-2">{{ rtrim(rtrim(number_format($row['contribution'], 2), '0'), '.') }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="text-left font-semibold text-gray-900 dark:text-gray-100 border-t border-gray-300 dark:border-gray-600">
                                <td class="px-3 py-2" colspan="3">Overall Project Progress</td>
                                <td class="px-3 py-2">{{ $autoProgress }}%</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @can('edit_construction_updates')
                    <form method="POST" action="{{ route('projects.constructionUpdates.overallProgress', $project) }}" class="flex items-end gap-3">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-input-label for="overall_progress_override" value="Override (%)" />
                            <x-text-input id="overall_progress_override" name="overall_progress_override" type="number" min="0" max="100"
                                class="mt-1 block w-40" placeholder="{{ $autoProgress }} (auto)"
                                :value="old('overall_progress_override', $project->overall_progress_override)" />
                        </div>
                        <x-primary-button>Save</x-primary-button>
                    </form>
                @else
                    <p class="text-sm font-medium">
                        Currently showing: {{ $project->computedConstructionProgress() }}%
                    </p>
                @endcan
            </div>

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
                        ['label' => 'Stage', 'key' => 'stage?->name'],
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
                        <x-input-label for="construction_stage_id" :value="'Stage'" />
                        <select id="construction_stage_id" name="construction_stage_id" x-model="form.construction_stage_id"
                                class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm">
                            <option value="">Select Stage</option>
                            @foreach ($stages as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">
                            Manage the stage list under <a href="{{ route('constructionStages.index') }}" class="underline">Construction Stages</a>.
                        </p>
                    </div>

                    <!-- Progress -->
                    <div>
                        <x-input-label for="progress_percentage" :value="'Progress (%)'" />
                        <x-text-input id="progress_percentage" name="progress_percentage" type="number" min="0" max="100" step="0.1"
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
                        <div id="cu-description-editor"
                             class="mt-1 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-900"
                             style="min-height:140px"></div>
                        <input type="hidden" name="description" id="cu-description-input" />
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
                        <x-input-label for="files" :value="'Upload Images / Videos'" />

                        <!-- Existing media (edit mode) -->
                        <template x-if="existingMedia.length">
                            <div class="mb-2 grid grid-cols-3 gap-2">
                                <template x-for="media in existingMedia" :key="media.id">
                                    <div class="relative group">
                                        <img :src="media.url" class="w-full h-20 object-cover rounded border border-gray-300 dark:border-gray-700">
                                        <button type="button" @click="removeExistingMedia(media.id)"
                                            class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs leading-none opacity-90 hover:opacity-100">
                                            &times;
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <!-- New file previews -->
                        <template x-if="newFilePreviews.length">
                            <div class="mb-2 grid grid-cols-3 gap-2">
                                <template x-for="(src, idx) in newFilePreviews" :key="idx">
                                    <img :src="src" class="w-full h-20 object-cover rounded border border-gray-300 dark:border-gray-700">
                                </template>
                            </div>
                        </template>

                        <x-text-input id="files" name="files[]" type="file" multiple accept="image/*,video/*" class="mt-1 block w-full" @change="previewFiles" />
                        <p class="text-xs text-gray-500 mt-1">You can select multiple images. New files are added to the existing gallery.</p>
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
                    currentUpdateId: null,
                    createUrl: "{{ route('projects.constructionUpdates.store', $project) }}",
                    updateUrl: "",
                    mediaType: 'file',
                    existingMedia: [],
                    newFilePreviews: [],
                    form: {
                        title: '',
                        construction_stage_id: '',
                        progress_percentage: '',
                        update_date: '',
                        description: '',
                        is_active: 1,
                        link: '',
                    },
                    openCreate() {
                        this.isEdit = false;
                        this.reset();
                        this.show = true;
                        this.$nextTick(() => {
                            if (window.cuQuill) {
                                window.cuQuill.setText('');
                                document.getElementById('cu-description-input').value = '';
                            }
                        });
                    },
                    openEdit(id) {
                        this.isEdit = true;
                        this.show = true;
                        this.currentUpdateId = id;
                        this.updateUrl = `/dashboard/projects/${this.projectId}/constructionUpdates/${id}`;

                        fetch(`/dashboard/projects/${this.projectId}/constructionUpdates/${id}/edit`, {
                            headers: { 'Accept': 'application/json' }
                        }).then(res => res.json())
                          .then(data => {
                              this.form = data;
                              this.existingMedia = data.media_items || [];
                              this.newFilePreviews = [];
                              this.mediaType = (!this.existingMedia.length && data.link) ? 'link' : 'file';
                              this.$nextTick(() => {
                                  if (window.cuQuill) {
                                      window.cuQuill.clipboard.dangerouslyPasteHTML(data.description || '');
                                      document.getElementById('cu-description-input').value = data.description || '';
                                  }
                              });
                          }).catch(err => { console.error(err); this.close(); });
                    },
                    previewFiles(event) {
                        const files = Array.from(event.target.files || []);
                        this.newFilePreviews = files
                            .filter(f => f.type.startsWith('image/'))
                            .map(f => URL.createObjectURL(f));
                    },
                    removeExistingMedia(mediaId) {
                        if (!confirm('Remove this image?')) return;
                        const token = document.querySelector('meta[name="csrf-token"]').content;
                        fetch(`/dashboard/projects/${this.projectId}/constructionUpdates/${this.currentUpdateId}/media/${mediaId}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                        }).then(res => {
                            if (res.ok) {
                                this.existingMedia = this.existingMedia.filter(m => m.id !== mediaId);
                            }
                        }).catch(err => console.error(err));
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
                            link: '',
                        };
                        this.mediaType = 'file';
                        this.currentUpdateId = null;
                        this.existingMedia = [];
                        this.newFilePreviews = [];
                    }
                }
            }
        </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cuEditor = new Quill('#cu-description-editor', {
                theme: 'snow',
                placeholder: 'Write update description…',
                modules: {
                    toolbar: [
                        [{ header: [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ color: [] }],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['blockquote'],
                        ['link', 'image'],
                        ['clean']
                    ]
                }
            });

            const cuInput = document.getElementById('cu-description-input');
            cuInput.value = cuEditor.root.innerHTML;
            cuEditor.on('text-change', () => {
                cuInput.value = cuEditor.root.innerHTML;
            });

            window.cuQuill = cuEditor;
        });
    </script>

    </div>
</x-app-layout>
