<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('projects.index')" :active="true">
            {{ __('Project Phases - ')  }} {{$project->name}}
        </x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div class="my-auto">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Create New Phase') }}
                        </h2>
                    </div>
                </div>

                <form method="POST" action="{{ route('projects.phases.store', $project) }}" class="mt-6 space-y-8"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="grid md:grid-cols-12 gap-5">

                        {{-- Project --}}
                        <div class="md:col-span-6">
                            <x-input-label for="project_id" :value="__('Project')" />
                            <x-text-input id="project_name" name="project_name" type="text"
                                          class="mt-1 block w-full"
                                          value="{{$project->name}}" readonly required autofocus />
                            <x-input-error :messages="$errors->get('project_id')" class="mt-2" />
                        </div>

                        {{-- Phase Name --}}
                        <div class="md:col-span-6">
                            <x-input-label for="name" :value="__('Phase Name')" />
                            <x-text-input id="name" name="name" type="text"
                                          class="mt-1 block w-full"
                                          :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Total Units --}}
                        <div class="md:col-span-4">
                            <x-input-label for="total_units" :value="__('Total Units')" />
                            <x-text-input id="total_units" name="total_units" type="number"
                                          class="mt-1 block w-full"
                                          :value="old('total_units')" />
                            <x-input-error :messages="$errors->get('total_units')" class="mt-2" />
                        </div>

                        {{-- Bedrooms --}}
                        <div class="md:col-span-4">
                            <x-input-label for="bedrooms" :value="__('Bedrooms')" />
                            <x-text-input id="bedrooms" name="bedrooms" type="text"
                                          class="mt-1 block w-full"
                                          :value="old('bedrooms')" />
                            <x-input-error :messages="$errors->get('bedrooms')" class="mt-2" />
                        </div>

                        {{-- Launch Date --}}
                        <div class="md:col-span-4">
                            <x-input-label for="launch_date" :value="__('Launch Date')" />
                            <x-text-input id="launch_date" name="launch_date" type="date"
                                          class="mt-1 block w-full"
                                          :value="old('launch_date')" />
                            <x-input-error :messages="$errors->get('launch_date')" class="mt-2" />
                        </div>

                        {{-- Handover --}}
                        <div class="md:col-span-4">
                            <x-input-label for="handover" :value="__('Handover (Eg: Q4 2028)')" />
                            <x-text-input id="handover" name="handover" type="text"
                                          class="mt-1 block w-full"
                                          :value="old('handover')" />
                            <x-input-error :messages="$errors->get('handover')" class="mt-2" />
                        </div>

                        {{-- Handover Date --}}
                        <div class="md:col-span-4">
                            <x-input-label for="handover_date" :value="__('Handover Date')" />
                            <x-text-input id="handover_date" name="handover_date" type="date"
                                          class="mt-1 block w-full"
                                          :value="old('handover_date')" />
                            <x-input-error :messages="$errors->get('handover_date')" class="mt-2" />
                        </div>

                        {{-- Status --}}
                        <div class="md:col-span-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <x-select name="status" :options="[
                                'planned' => 'Planned',
                                'under_construction' => 'Under Construction',
                                'completed' => 'Completed'
                            ]" :value="old('status')" />
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        {{-- Order --}}
                        <div class="md:col-span-4">
                            <x-input-label for="sort_order" :value="__('Order')" />
                            <x-text-input id="sort_order" name="sort_order" type="number"
                                          class="mt-1 block w-full"
                                          :value="old('sort_order')" />
                            <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                        </div>

                        {{-- Is Active --}}
                        <div class="md:col-span-4">
                            <x-input-label for="is_active" :value="__('Is Active')" />
                            <x-select name="is_active" :options="['1' => 'Active', '0' => 'Inactive']"
                                      :value="old('is_active', '1')" />
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>

                        {{-- Accommodations / Amenities --}}
                        <div class="md:col-span-12">
                            <x-input-label for="accommodations" :value="__('Project Accommodations')" />
                            <select id="accommodations" name="accommodations[]" multiple
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm"
                                placeholder="Select accommodations">
                                @foreach ($accommodations as $acc)
                                    <option value="{{ $acc->id }}"
                                        {{ collect(old('accommodations'))->contains($acc->id) ? 'selected' : '' }}>
                                        {{ $acc->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('accommodations')" class="mt-2" />
                        </div>
                        {{-- Amenities --}}
                        <div class="md:col-span-12">
                            <x-input-label for="amenities" :value="__('Amenities')" />
                            <select id="amenities" name="amenities[]" multiple
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm"
                                placeholder="Select amenities">
                                @foreach ($amenities as $amenity)
                                    <option value="{{ $amenity->id }}"
                                        {{ collect(old('amenities'))->contains($amenity->id) ? 'selected' : '' }}>
                                        {{ $amenity->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('amenities')" class="mt-2" />
                        </div>

                    </div>

                    <div class="flex flex-col items-center justify-center gap-4">
                        <x-primary-button>{{ __('Submit') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- TomSelect --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ['amenities', 'accommodations'].forEach(function(id) {
                const el = document.getElementById(id);
                if (el) {
                    new TomSelect(el, {
                        create: false,
                        persist: false,
                        plugins: ['remove_button'],
                        onItemAdd: function() {
                            this.setTextboxValue('');
                            this.refreshOptions();
                        },
                    });
                }
            });
        });
    </script>

</x-app-layout>
