<x-app-layout>
    <div x-data="paymentPlanModal()" x-init="@if ($errors->any()) show = true @endif">

        <x-slot name="header">
            <x-nav-link :href="route('projects.index')" :active="true">
                Payment Plans – {{ $project->name }}
            </x-nav-link>
        </x-slot>

        <div class="py-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">

                @can('create_payment_plans')
                    <div class="text-right mb-4">
                        <x-button-link href="#" @click.prevent="openCreate()">Create Payment Plan</x-button-link>
                    </div>
                @endcan

                @php
                    $actions = [];

                    if (auth()->user()->can('edit_payment_plans')) {
                        $actions[] = ['type' => 'edit', 'label' => 'Edit', 'click' => true];
                    }

                    if (auth()->user()->can('delete_payment_plans')) {
                        $actions[] = [
                            'type' => 'delete',
                            'url' => 'projects.paymentPlans.destroy',
                            'params' => [$project->id],
                            'label' => 'Delete',
                        ];
                    }

                    $columns = collect([
                        ['label' => '#'],
                        ['label' => 'Title', 'key' => 'title'],
                        ['label' => 'Down Payment', 'key' => 'down_payment'],
                        ['label' => 'Total Price', 'key' => 'total_price'],
                        [
                            'label' => 'Offer',
                            'key' => 'is_offer',
                            'badge' => true,
                            'badgeMap' => [
                                1 => ['text' => 'Yes', 'color' => 'bg-green-200'],
                                0 => ['text' => 'No', 'color' => 'bg-gray-200'],
                            ],
                        ],
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

                <x-datatable :data="$paymentPlans" :columns="$columns" />

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

                    <!-- Title -->
                    <div>
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                            x-model="form.title" required autocomplete="title" />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <x-text-textarea id="description" name="description" class="mt-1 block w-full"
                            x-model="form.description" required autocomplete="description"></x-text-textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <!-- Payment Breakdown -->
                    <div>
                        <x-input-label :value="'Payment Breakdown'" />
                        <template x-for="(item, index) in form.payment_breakdown" :key="index">
                            <div class="flex gap-2 mb-2">
                                <input type="text" :name="`payment_breakdown[${index}][name]`" x-model="item.name"
                                    placeholder="Payment Type"
                                    class="mt-1 block w-1/2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm" required>
                                <input type="text" :name="`payment_breakdown[${index}][percentage]`"
                                    x-model="item.percentage" placeholder="Percentage"
                                    class="mt-1 block w-1/2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm" required>
                                <button type="button" class="text-red-500"
                                    @click="form.payment_breakdown.splice(index, 1)">×</button>
                            </div>
                        </template>
                        <button type="button" class="text-blue-500 mt-1"
                            @click="form.payment_breakdown.push({ name:'', percentage:'' })">
                            + Add Payment Breakdown
                        </button>
                    </div>

                    <!-- Installments -->
                    <div class="mt-4">
                        <x-input-label :value="'Installments'" />
                        <template x-for="(item, index) in form.installments" :key="index">
                            <div class="flex gap-2 mb-2">
                                <input type="date" :name="`installments[${index}][due_date]`" x-model="item.due_date"
                                    class="mt-1 block w-1/2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm" required>
                                <input type="text" :name="`installments[${index}][amount]`" x-model="item.amount"
                                    placeholder="Amount" class="mt-1 block w-1/2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm"
                                    required>
                                <button type="button" class="text-red-500"
                                    @click="form.installments.splice(index, 1)">×</button>
                            </div>
                        </template>
                        <button type="button" class="text-blue-500 mt-1"
                            @click="form.installments.push({ due_date:'', amount:'' })">
                            + Add Installment
                        </button>
                    </div>


                    <!-- Down Payment -->
                    <div>
                        <x-input-label for="down_payment" :value="__('Down Payment')" />
                        <x-text-input id="down_payment" name="down_payment" type="number" step="0.01"
                            class="mt-1 block w-full" x-model="form.down_payment" />
                        <x-input-error :messages="$errors->get('down_payment')" class="mt-2" />
                    </div>

                    <!-- Total Price -->
                    <div>
                        <x-input-label for="total_price" :value="__('Total Price')" />
                        <x-text-input id="total_price" name="total_price" type="number" step="0.01"
                            class="mt-1 block w-full" x-model="form.total_price" />
                        <x-input-error :messages="$errors->get('total_price')" class="mt-2" />
                    </div>

                    <!-- Is Offer -->
                    <div>
                        <x-input-label for="is_offer" :value="__('Is Offer')" />
                        <select id="is_offer" name="is_offer" x-model="form.is_offer"
                            class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        <x-input-error :messages="$errors->get('is_offer')" class="mt-2" />
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

                    <!-- Payment Plan File -->
                    <div>
                        <x-input-label for="payment_plan_file" :value="__('Payment Plan File')" />

                        <div class="mb-2" x-show="filePreview" x-transition>
                            <a :href="filePreview" target="_blank" class="text-primary underline">View current
                                file</a>
                        </div>

                        <x-text-input id="payment_plan_file" name="payment_plan_file" type="file"
                            class="mt-1 block w-full" @change="previewFile" />
                        <x-input-error :messages="$errors->get('payment_plan_file')" class="mt-2" />
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
            function paymentPlanModal() {
                return {
                    show: false,
                    isEdit: false,
                    projectId: {{ $project->id }},
                    createUrl: "{{ route('projects.paymentPlans.store', $project) }}",
                    updateUrl: "",
                    filePreview: null,
                    form: {
                        title: '',
                        description: '',
                        payment_breakdown: [],
                        installments: [],
                        down_payment: '',
                        total_price: '',
                        is_offer: 0,
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
                        this.updateUrl = `/dashboard/projects/${this.projectId}/paymentPlans/${id}`;

                        fetch(`/dashboard/projects/${this.projectId}/paymentPlans/${id}/edit`, {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                this.form = data;
                                this.filePreview = data.file ?? null;
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
                    },
                    close() {
                        this.show = false;
                    },
                    reset() {
                        this.form = {
                            title: '',
                            description: '',
                            payment_breakdown: [{
                                name: '',
                                percentage: ''
                            }],
                            installments: [{
                                due_date: '',
                                amount: ''
                            }],
                            down_payment: '',
                            total_price: '',
                            is_offer: 0,
                            is_active: 1
                        };
                        this.filePreview = null;
                    }

                }
            }
        </script>

    </div>
</x-app-layout>
