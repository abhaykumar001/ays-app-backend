<x-app-layout>
    <div x-data="agentModal({
        first_name: @js(old('first_name')),
        last_name: @js(old('last_name')),
        designation: @js(old('designation')),
        email: @js(old('email', '')),
        phone: @js(old('phone', '')),
        whatsapp: @js(old('whatsapp', '')),
        nationality: @js(old('nationality', '')),
        license_number: @js(old('license_number', '')),
        license_expiry: @js(old('license_expiry', '')),
        notes: @js(old('notes', '')),
        is_active: @js(old('is_active', true)),
        image_url: @js(old('image_url')),
        {{-- optional if you store previous image --}}
    })" x-init="@if ($errors->any()) show = true @endif">
        <x-slot name="header">
            <x-nav-link :href="route('agents.index')" :active="true">
                {{ __('Agents Data') }}
            </x-nav-link>
        </x-slot>

        <div class="py-6">
            <div class="sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                    <!-- Header -->
                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div class="my-auto">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Agents Data for Website
                            </h2>
                        </div>

                        @can('create_agents')
                            <div class="md:text-end">
                                <x-button-link href="#" @click.prevent="openCreate()">
                                    Create Agent Data
                                </x-button-link>
                            </div>
                        @endcan
                    </div>

                    <!-- Table -->
                    @can('view_agents')
                        @php
                            $actions = [];

                            if (auth()->user()->can('edit_agents')) {
                                $actions[] = [
                                    'type' => 'edit',
                                    'label' => 'Edit',
                                    'click' => 'true',
                                ];
                            }

                            if (auth()->user()->can('delete_agents')) {
                                $actions[] = [
                                    'type' => 'delete',
                                    'url' => 'agents.destroy',
                                    'label' => 'Delete',
                                ];
                            }

                            $columns = collect([
                                ['label' => '#'],
                                ['label' => 'Name', 'key' => 'name'],
                                ['label' => 'Designation', 'key' => 'designation'],
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
                            ])
                                ->filter()
                                ->values()
                                ->toArray();
                        @endphp

                        <x-datatable :data="$agents" :columns="$columns" />
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
                    <span x-text="isEdit ? 'Edit Agent Member' : 'Create Agent Member'"></span>
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
                        <x-input-label for="name" :value="__('First Name')" />
                        <x-text-input id="name" name="first_name" type="text" class="mt-1 block w-full"
                            x-model="form.first_name" required autocomplete="first_name" />
                        <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="name" :value="__('Last Name')" />
                        <x-text-input id="name" name="last_name" type="text" class="mt-1 block w-full"
                            x-model="form.last_name" required autocomplete="last_name" />
                        <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                    </div>

                    <!-- Designation -->
                    <div>
                        <x-input-label for="designation" :value="__('Designation')" />
                        <x-text-input id="designation" name="designation" type="text" class="mt-1 block w-full"
                            x-model="form.designation" required autocomplete="designation" />
                        <x-input-error :messages="$errors->get('designation')" class="mt-2" />
                    </div>
                    <!-- Designation -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                            x-model="form.email" required autocomplete="email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <!-- Designation -->
                    <div>
                        <x-input-label for="phone" :value="__('Phone')" />
                        <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full"
                            x-model="form.phone" required autocomplete="phone" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>
                    <!-- Designation -->
                    <div>
                        <x-input-label for="whatsapp" :value="__('WhatsApp')" />
                        <x-text-input id="whatsapp" name="whatsapp" type="tel" class="mt-1 block w-full"
                            x-model="form.whatsapp" required autocomplete="whatsapp" />
                        <x-input-error :messages="$errors->get('whatsapp')" class="mt-2" />
                    </div>
                    <!-- Designation -->
                    <div>
                        <x-input-label for="nationality" :value="__('Nationality')" />
                        <x-text-input id="nationality" name="nationality" type="text" class="mt-1 block w-full"
                            x-model="form.nationality" required autocomplete="nationality" />
                        <x-input-error :messages="$errors->get('nationality')" class="mt-2" />
                    </div>
                    <!-- Designation -->
                    <div>
                        <x-input-label for="license_number" :value="__('BRN Number')" />
                        <x-text-input id="license_number" name="license_number" type="number" class="mt-1 block w-full"
                            x-model="form.license_number" required autocomplete="license_number" />
                        <x-input-error :messages="$errors->get('license_number')" class="mt-2" />
                    </div>
                    <!-- Designation -->
                    <div>
                        <x-input-label for="license_expiry" :value="__('License Expiry')" />
                        <x-text-input id="license_expiry" name="license_expiry" type="text"
                            class="mt-1 block w-full" x-model="form.license_expiry" required
                            autocomplete="license_expiry" />
                        <x-input-error :messages="$errors->get('license_expiry')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="notes" :value="__('Notes')" />
                        <x-text-textarea id="notes" name="notes" type="text" class="mt-1 block w-full"
                            x-model="form.notes" required autocomplete="notes"> </x-text-textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
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
                        <x-input-label for="is_active" :value="__('Status')" />
                        <select id="is_active" name="is_active" x-model="form.is_active"
                            class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm">
                            <option :value="true">Active</option>
                            <option :value="false">Inactive</option>
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
            function agentModal(oldData = {}) {
                return {
                    show: false,
                    isEdit: false,

                    createUrl: "{{ route('agents.store') }}",
                    updateUrl: "",

                    imagePreview: oldData.image_url ?? null,

                    form: {
                        first_name: oldData.first_name ?? '',
                        last_name: oldData.last_name ?? '',
                        designation: oldData.designation ?? '',
                        email: oldData.email ?? '',
                        phone: oldData.phone ?? '',
                        whatsapp: oldData.whatsapp ?? '',
                        nationality: oldData.nationality ?? '',
                        license_number: oldData.license_number ?? '',
                        license_expiry: oldData.license_expiry ?? '',
                        notes: oldData.notes ?? '',
                        is_active: oldData.is_active ?? true,
                    },


                    openCreate() {
                        this.isEdit = false;
                        this.reset();
                        this.show = true;
                    },

                    openEdit(agentId) {
                        this.isEdit = true;
                        this.show = true;

                        // Set update URL
                        this.updateUrl = `/dashboard/agents/${agentId}`;

                        // Fetch agent data via AJAX
                        fetch(`/dashboard/agents/${agentId}/edit`, {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => {
                                if (!res.ok) throw new Error('Network response was not ok');
                                return res.json();
                            })
                            .then(agent => {
                                this.form = {
                                    first_name: agent.first_name,
                                    last_name: agent.last_name,
                                    designation: agent.designation,
                                    email: agent.email,
                                    phone: agent.phone,
                                    whatsapp: agent.whatsapp,
                                    nationality: agent.nationality,
                                    license_number: agent.license_number,
                                    license_expiry: agent.license_expiry,
                                    notes: agent.notes,
                                    is_active: agent.is_active,
                                };
                                this.imagePreview = agent.image ?? null;
                            })
                            .catch(err => {
                                console.error('Error fetching agent:', err);
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
                            first_name: '',
                            last_name: '',
                            designation: '',
                            email: '',
                            phone: '',
                            whatsapp: '',
                            nationality: '',
                            license_number: '',
                            license_expiry: '',
                            notes: '',
                            is_active: true,
                        };
                        this.imagePreview = null;
                    }
                }
            }
        </script>
    </div>
</x-app-layout>
