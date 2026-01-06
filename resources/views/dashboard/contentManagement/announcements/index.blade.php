<x-app-layout>
    <div x-data="announcementModal({
        title: @js(old('title')),
        message: @js(old('message')),
        type: @js(old('type', 'general')),
        audience: @js(old('audience', 'public')),
        priority: @js(old('priority', 'normal')),
        is_popup: @js(old('is_popup', false)),
        is_active: @js(old('is_active', true)),
        publish_at: @js(old('publish_at')),
        expire_at: @js(old('expire_at')),
        cta_text: @js(old('cta_text')),
        cta_url: @js(old('cta_url')),
    })" x-init="@if ($errors->any()) show = true @endif">
        <x-slot name="header">
            <x-nav-link :href="route('announcements.index')" :active="true">
                {{ __('Announcements') }}
            </x-nav-link>
        </x-slot>

        <div class="py-6">
            <div class="sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                    <!-- Header -->
                    <div class="flex justify-between mb-4">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Announcements') }}
                        </h2>

                        @can('create_announcements')
                            <x-button-link href="#" @click.prevent="openCreate()">
                                Add New Announcement
                            </x-button-link>
                        @endcan
                    </div>

                    <!-- Table -->
                    @can('view_announcements')
                        @php
                            $actions = [];
                            if(auth()->user()->can('edit_announcements')){
                                $actions[] = ['type'=>'edit','label'=>'Edit','click'=>true];
                            }
                            if(auth()->user()->can('delete_announcements')){
                                $actions[] = ['type'=>'delete','url'=>'announcements.destroy','label'=>'Delete'];
                            }

                            $columns = collect([
                                ['label'=>'#','key'=>'id'],
                                ['label'=>'Title','key'=>'title'],
                                ['label'=>'Type','key'=>'type'],
                                ['label'=>'Audience','key'=>'audience'],
                                ['label'=>'Priority','key'=>'priority'],
                                ['label'=>'Popup','key'=>'is_popup','badge'=>true,'badgeMap'=>[1=>['text'=>'Yes','color'=>'bg-green-200 text-green-800'],0=>['text'=>'No','color'=>'bg-yellow-200 text-yellow-800']]],
                                ['label'=>'Active','key'=>'is_active','badge'=>true,'badgeMap'=>[1=>['text'=>'Active','color'=>'bg-green-200 text-green-800'],0=>['text'=>'Inactive','color'=>'bg-yellow-200 text-yellow-800']]],
                                ['label'=>'Publish At','key'=>'publish_at'],
                                ['label'=>'Expire At','key'=>'expire_at'],
                                ['label'=>'CTA Text','key'=>'cta_text'],
                                ['label'=>'CTA URL','key'=>'cta_url'],
                                count($actions) ? ['label'=>'Actions','actions'=>$actions] : null,
                            ])->filter()->values()->toArray();
                        @endphp

                        <x-datatable :data="$announcements" :columns="$columns" />
                    @endcan
                </div>
            </div>
        </div>

        <!-- ================= MODAL ================= -->
        <div x-show="show" x-cloak class="fixed inset-0 z-50 flex">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-black/40" @click="close()" x-transition.opacity></div>

            <!-- Panel -->
            <div class="relative ml-auto w-full max-w-lg h-full bg-white dark:bg-gray-900 shadow-xl p-6 overflow-y-auto"
                x-show="show" x-transition:enter="transform transition ease-in-out duration-300"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

                <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">
                    <span x-text="isEdit ? 'Edit Announcement' : 'Create Announcement'"></span>
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

                <form :action="isEdit ? updateUrl : createUrl" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="isEdit">@method('PUT')</template>

                    <div>
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                            x-model="form.title" required />
                    </div>

                    <div>
                        <x-input-label for="message" :value="__('Message')" />
                        <x-text-textarea id="message" name="message" class="mt-1 block w-full"
                            x-model="form.message" required></x-text-textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="type" :value="__('Type')" />
                            <select id="type" name="type" class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm " x-model="form.type">
                                <option value="general">General</option>
                                <option value="project_update">Project Update</option>
                                <option value="handover">Handover</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="offer">Offer</option>
                                <option value="construction_update">Construction Update</option>
                                <option value="system">System</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="audience" :value="__('Audience')" />
                            <select id="audience" name="audience" class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm" x-model="form.audience">
                                <option value="public">Public</option>
                                <option value="owners">Owners</option>
                                <option value="buyers">Buyers</option>
                                <option value="agents">Agents</option>
                                <option value="internal">Internal</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="priority" :value="__('Priority')" />
                            <select id="priority" name="priority" class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm" x-model="form.priority">
                                <option value="low">Low</option>
                                <option value="normal">Normal</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="is_popup" :value="__('Popup')" />
                            <select id="is_popup" name="is_popup" class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm" x-model="form.is_popup">
                                <option :value="0">No</option>
                                <option :value="1">Yes</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="is_active" :value="__('Active')" />
                            <select id="is_active" name="is_active" class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm" x-model="form.is_active">
                                <option :value="1">Active</option>
                                <option :value="0">Inactive</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="publish_at" :value="__('Publish At')" />
                            <input type="datetime-local" id="publish_at" name="publish_at" class="mt-1 block w-full"
                                x-model="form.publish_at">
                        </div>

                        <div>
                            <x-input-label for="expire_at" :value="__('Expire At')" />
                            <input type="datetime-local" id="expire_at" name="expire_at" class="mt-1 block w-full"
                                x-model="form.expire_at">
                        </div>

                        <div>
                            <x-input-label for="cta_text" :value="__('CTA Text')" />
                            <x-text-input id="cta_text" name="cta_text" type="text" class="mt-1 block w-full"
                                x-model="form.cta_text" />
                        </div>

                        <div>
                            <x-input-label for="cta_url" :value="__('CTA URL')" />
                            <x-text-input id="cta_url" name="cta_url" type="text" class="mt-1 block w-full"
                                x-model="form.cta_url" />
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <x-secondary-button type="button" @click="close()">Cancel</x-secondary-button>
                        <x-primary-button type="submit" x-text="isEdit ? 'Update' : 'Create'"></x-primary-button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= SCRIPT ================= -->
        <script>
            function announcementModal() {
                return {
                    show: false,
                    isEdit: false,
                    createUrl: "{{ route('announcements.store') }}",
                    updateUrl: "",
                    form: {
                        title: '',
                        message: '',
                        type: 'general',
                        audience: 'public',
                        priority: 'normal',
                        is_popup: 0,
                        is_active: 1,
                        publish_at: '',
                        expire_at: '',
                        cta_text: '',
                        cta_url: '',
                    },
                    openCreate() {
                        this.isEdit = false;
                        this.reset();
                        this.show = true;
                        this.updateUrl = '';
                    },
                    openEdit(id) {
                        this.isEdit = true;
                        this.show = true;
                        this.updateUrl = `/announcements/${id}`;
                        fetch(`/announcements/${id}/edit`, { headers: { 'Accept': 'application/json' }})
                            .then(res => res.json())
                            .then(data => {
                                this.form = { ...data };
                            });
                    },
                    close() {
                        this.show = false;
                    },
                    reset() {
                        this.form = {
                            title: '',
                            message: '',
                            type: 'general',
                            audience: 'public',
                            priority: 'normal',
                            is_popup: 0,
                            is_active: 1,
                            publish_at: '',
                            expire_at: '',
                            cta_text: '',
                            cta_url: '',
                        };
                    }
                }
            }
        </script>
    </div>
</x-app-layout>
