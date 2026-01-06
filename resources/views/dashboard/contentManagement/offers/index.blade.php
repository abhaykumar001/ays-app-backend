<x-app-layout>
    <div x-data="offerModal()" x-init="@if ($errors->any()) show = true @endif">
        <x-slot name="header">
            <x-nav-link :href="route('offers.index')" :active="true">
                {{ __('Offers') }}
            </x-nav-link>
        </x-slot>

        <div class="py-6">
            <div class="sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                    <!-- Header -->
                    <div class="flex justify-between mb-4">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Offers') }}
                        </h2>

                        @can('create_offers')
                            <x-button-link href="#" @click.prevent="openCreate()">
                                Add New Offer
                            </x-button-link>
                        @endcan
                    </div>

                    <!-- Table -->
                    @can('view_offers')
                        @php
                            $actions = [];

                            if(auth()->user()->can('edit_offers')){
                                $actions[] = ['type'=>'edit','label'=>'Edit','click'=>true];
                            }

                            if(auth()->user()->can('delete_offers')){
                                $actions[] = ['type'=>'delete','url'=>'offers.destroy','label'=>'Delete'];
                            }

                            $columns = collect([
                                ['label'=>'#','key'=>'id'],
                                ['label'=>'Title','key'=>'title'],
                                ['label'=>'Type','key'=>'type'],
                                ['label'=>'Value','key'=>'value'],
                                ['label'=>'Percentage','key'=>'percentage'],
                                ['label'=>'Unit','key'=>'unit'],
                                [
                                    'label'=>'Featured',
                                    'key'=>'is_featured',
                                    'badge'=>true,
                                    'badgeMap'=>[
                                        1=>['text'=>'Yes','color'=>'bg-green-200 text-green-800'],
                                        0=>['text'=>'No','color'=>'bg-yellow-200 text-yellow-800']
                                    ]
                                ],
                                [
                                    'label'=>'Active',
                                    'key'=>'is_active',
                                    'badge'=>true,
                                    'badgeMap'=>[
                                        1=>['text'=>'Active','color'=>'bg-green-200 text-green-800'],
                                        0=>['text'=>'Inactive','color'=>'bg-red-200 text-red-800']
                                    ]
                                ],
                                ['label'=>'Start Date','key'=>'start_date'],
                                ['label'=>'End Date','key'=>'end_date'],
                                count($actions) ? ['label'=>'Actions','actions'=>$actions] : null,
                            ])->filter()->values()->toArray();
                        @endphp

                        <x-datatable :data="$offers" :columns="$columns" />
                    @endcan
                </div>
            </div>
        </div>

        <!-- ================= MODAL ================= -->
        <div x-show="show" x-cloak class="fixed inset-0 z-50 flex">
            <div class="fixed inset-0 bg-black/40" @click="close()" x-transition.opacity></div>

            <div class="relative ml-auto w-full max-w-xl h-full bg-white dark:bg-gray-900 shadow-xl p-6 overflow-y-auto"
                 x-show="show"
                 x-transition:enter="transform transition ease-in-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-300"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full">

                <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100"
                    x-text="isEdit ? 'Edit Offer' : 'Create Offer'"></h2>

                <form :action="isEdit ? updateUrl : createUrl" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="isEdit">@method('PUT')</template>

                    <div>
                        <x-input-label for="title" value="Title" />
                        <x-text-input id="title" name="title" x-model="form.title"  class="mt-1 block w-full" required />
                    </div>

                    <div>
                        <x-input-label for="description" value="Description" />
                        <x-text-textarea id="description" name="description" class="mt-1 block w-full"
                            x-model="form.description" required></x-text-textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="Type" />
                            <select name="type" class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm" x-model="form.type">
                                <option value="discount">Discount</option>
                                <option value="dld_waiver">DLD Waiver</option>
                                <option value="service_charge_waiver">Service Charge Waiver</option>
                                <option value="post_handover">Post Handover</option>
                                <option value="furniture">Furniture</option>
                                <option value="cashback">Cashback</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label value="Unit" />
                            <x-text-input name="unit" x-model="form.unit"  class="mt-1 block w-full" placeholder="AED / % / Months" />
                        </div>

                        <div>
                            <x-input-label value="Value (AED)" />
                            <x-text-input type="number" step="0.01" name="value"  class="mt-1 block w-full" x-model="form.value" />
                        </div>

                        <div>
                            <x-input-label value="Percentage (%)" />
                            <x-text-input type="number" step="0.01" name="percentage"  class="mt-1 block w-full" x-model="form.percentage" />
                        </div>

                        <div>
                            <x-input-label value="Start Date" />
                            <x-text-input type="date"  name="start_date" x-model="form.start_date"  class="mt-1 block w-full" />
                        </div>

                        <div>
                            <x-input-label value="End Date" />
                            <x-text-input type="date"  name="end_date" x-model="form.end_date"  class="mt-1 block w-full" />
                        </div>
                    </div>

                    <div>
                        <x-input-label value="Conditions (one per line)" />
                        <x-text-textarea id="conditions" name="conditions" class="mt-1 block w-full"
                            x-model="form.conditions" required></x-text-textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="Featured" />
                            <select name="is_featured" x-model="form.is_featured" class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm">
                                <option :value="0">No</option>
                                <option :value="1">Yes</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label value="Active" />
                            <select name="is_active" x-model="form.is_active" class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm">
                                <option :value="1">Active</option>
                                <option :value="0">Inactive</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label value="Sort Order" />
                            <x-text-input type="number" name="sort_order" x-model="form.sort_order" />
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <x-secondary-button type="button" @click="close()">Cancel</x-secondary-button>
                        <x-primary-button type="submit"
                                          x-text="isEdit ? 'Update Offer' : 'Create Offer'"></x-primary-button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= SCRIPT ================= -->
        <script>
            function offerModal() {
                return {
                    show: false,
                    isEdit: false,
                    createUrl: "{{ route('offers.store') }}",
                    updateUrl: '',
                    form: {
                        title: '',
                        description: '',
                        type: 'discount',
                        value: '',
                        percentage: '',
                        unit: '',
                        conditions: '',
                        start_date: '',
                        end_date: '',
                        is_featured: 0,
                        is_active: 1,
                        sort_order: 0,
                    },
                    openCreate() {
                        this.isEdit = false;
                        this.reset();
                        this.show = true;
                    },
                    openEdit(id) {
                        this.isEdit = true;
                        this.show = true;
                        this.updateUrl = `/offers/${id}`;
                        fetch(`/offers/${id}/edit`, { headers: { 'Accept': 'application/json' }})
                            .then(r => r.json())
                            .then(data => this.form = data);
                    },
                    close() {
                        this.show = false;
                    },
                    reset() {
                        this.form = {
                            title: '',
                            description: '',
                            type: 'discount',
                            value: '',
                            percentage: '',
                            unit: '',
                            conditions: '',
                            start_date: '',
                            end_date: '',
                            is_featured: 0,
                            is_active: 1,
                            sort_order: 0,
                        };
                    }
                }
            }
        </script>
    </div>
</x-app-layout>
