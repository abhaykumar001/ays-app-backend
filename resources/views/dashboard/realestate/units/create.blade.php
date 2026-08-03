<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('projects.units.index', $project)" :active="true">
            {{ __('Projects') }} - {{$project->name}}
        </x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div class="my-auto">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Create New Unit') }}
                        </h2>
                    </div>
                </div>

                <form method="POST" action="{{ route('projects.units.store', $project) }}" class="mt-6 space-y-8"
                    enctype="multipart/form-data" x-data="unitPaymentPlans()">
                    @csrf

                    <div class="grid md:grid-cols-12 gap-5">
                        {{-- Project Name --}}
                        <div class="md:col-span-6">
                            <x-input-label for="name" :value="__('Unit Name')" />
                            <x-text-input id="name" name="title" type="text" class="mt-1 block w-full"
                                :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        {{-- Unit Number --}}
                        <div class="md:col-span-6">
                            <x-input-label for="unit_number" :value="__('Unit Number')" />
                            <x-text-input id="unit_number" name="unit_number" type="text" class="mt-1 block w-full"
                                :value="old('unit_number')" />
                            <x-input-error :messages="$errors->get('unit_number')" class="mt-2" />
                        </div>

                        {{-- Unit Type --}}
                        <div class="md:col-span-6">
                            <x-input-label for="unit_type" :value="__('Unit Type')" />
                            <x-text-input id="unit_type" name="unit_type" type="text" class="mt-1 block w-full"
                                :value="old('unit_type')" placeholder="e.g. 1BR-A, Corner Unit" />
                            <x-input-error :messages="$errors->get('unit_type')" class="mt-2" />
                        </div>

                        {{-- Project Phase --}}
                        <div class="md:col-span-6">
                            <x-input-label for="project_phase_id" :value="__('Project Phase')" />
                            <select id="project_phase_id" name="project_phase_id"
                                class="mt-1 blockborder-gray-300 w-full dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm">
                                <option value="">Select Phase</option>
                                @foreach ($phases as $phase)
                                    <option value="{{ $phase->id }}"
                                        {{ old('project_phase_id') == $phase->id ? 'selected' : '' }}>
                                        {{ $phase->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('project_phase_id')" class="mt-2" />
                        </div>

                        {{-- Accommodation ID --}}
                        <div class="md:col-span-6">
                            <x-input-label for="accommodation_id" :value="__('Main Accommodation')" />
                            <select id="accommodation_id" name="accommodation_id"
                                class="mt-1 blockborder-gray-300 w-full dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm">
                                <option value="">Select Accommodation</option>
                                @foreach ($accommodations as $acc)
                                    <option value="{{ $acc->id }}"
                                        {{ old('accommodation_id') == $acc->id ? 'selected' : '' }}>
                                        {{ $acc->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('accommodation_id')" class="mt-2" />
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

                        {{-- Starting Price --}}
                        <div class="md:col-span-4">
                            <div class="flex items-center justify-between mb-1">
                                <x-input-label for="price" :value="__('Unit Price')" class="mb-0" />
                                <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 cursor-pointer select-none">
                                    <input type="checkbox" id="price_on_request_toggle" class="rounded"
                                        onchange="
                                            const inp = document.getElementById('price');
                                            if (this.checked) { inp.value = ''; inp.disabled = true; }
                                            else { inp.disabled = false; inp.focus(); }
                                        ">
                                    Price on Request
                                </label>
                            </div>
                            <x-text-input id="price" name="price" type="text"
                                class="mt-1 block w-full" :value="old('price')" placeholder="e.g. AED 1,500,000" />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        {{-- Price per SqFt --}}
                        <div class="md:col-span-4">
                            <x-input-label for="price_per_sqft" :value="__('Price per SqFt')" />
                            <x-text-input id="price_per_sqft" name="price_per_sqft" type="text"
                                class="mt-1 block w-full" :value="old('price_per_sqft')" />
                            <x-input-error :messages="$errors->get('price_per_sqft')" class="mt-2" />
                        </div>

                        {{-- Bedrooms --}}
                        <div class="md:col-span-4">
                            <x-input-label for="bedrooms" :value="__('Bedrooms')" />
                            <x-text-input id="bedrooms" name="bedrooms" type="text" class="mt-1 block w-full"
                                :value="old('bedrooms')" placeholder="e.g. Studio, 1, 2, Duplex 1-3" />
                            <x-input-error :messages="$errors->get('bedrooms')" class="mt-2" />
                        </div>
                        {{-- Bathrooms --}}
                        <div class="md:col-span-4">
                            <x-input-label for="bathrooms" :value="__('Bathrooms')" />
                            <x-text-input id="bathrooms" name="bathrooms" type="text"
                                class="mt-1 block w-full" :value="old('bathrooms')" placeholder="e.g. 2, 1-2, 2-3" />
                            <x-input-error :messages="$errors->get('bathrooms')" class="mt-2" />
                        </div>

                        {{-- Parking --}}
                        <div class="md:col-span-4">
                            <x-input-label for="parking" :value="__('Parking Spaces')" />
                            <x-text-input id="parking" name="parking" type="number" min="0"
                                class="mt-1 block w-full" :value="old('parking')" />
                            <x-input-error :messages="$errors->get('parking')" class="mt-2" />
                        </div>

                        {{-- Plot Size --}}
                        <div class="md:col-span-4">
                            <x-input-label for="size_sqft" :value="__('Unit Size (SqFt)')" />
                            <x-text-input id="size_sqft" name="size_sqft" type="text"
                                class="mt-1 block w-full" :value="old('size_sqft')" placeholder="e.g. 1,200, 901-1,867" />
                            <x-input-error :messages="$errors->get('size_sqft')" class="mt-2" />
                        </div>

                        {{-- Floor --}}
                        <div class="md:col-span-4">
                            <x-input-label for="floor" :value="__('Floor')" />
                            <x-text-input id="floor" name="floor" type="text" class="mt-1 block w-full"
                                :value="old('floor')" placeholder="e.g. 5, 2-4-6-8, G-10" />
                            <x-input-error :messages="$errors->get('floor')" class="mt-2" />
                        </div>

                        {{-- View --}}
                        <div class="md:col-span-4">
                            <x-input-label for="view" :value="__('View')" />
                            <x-text-input id="view" name="view" type="text" class="mt-1 block w-full"
                                :value="old('view')" />
                            <x-input-error :messages="$errors->get('view')" class="mt-2" />
                        </div>

                        {{-- Full Description --}}
                        <div class="md:col-span-12">
                            <x-input-label for="description" :value="__('Full Description')" />
                            <x-text-textarea id="description" name="description" class="mt-1 block w-full">
                                {{ old('description') }}
                            </x-text-textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        <div class="md:col-span-12">
                            <x-input-label for="meta_title" :value="__('Meta Title')" />
                            <x-text-input id="meta_title" name="meta_title" type="text" class="mt-1 block w-full"
                                :value="old('meta_title', '')" autofocus autocomplete="meta_title" />
                            <x-input-error :messages="$errors->get('meta_title')" class="mt-2" />
                        </div>
                        <div class="md:col-span-12">
                            <x-input-label for="meta_keywords" :value="__('Meta Keywords')" />
                            <x-text-input id="meta_keywords" name="meta_keywords" type="text"
                                class="mt-1 block w-full" :value="old('meta_keywords', '')" autofocus autocomplete="meta_keywords" />
                            <x-input-error :messages="$errors->get('meta_keywords')" class="mt-2" />
                        </div>
                        <div class="md:col-span-12">
                            <x-input-label for="meta_description" :value="__('Meta Description')" />
                            <x-text-textarea id="meta_description" name="meta_description" class="mt-1 block w-full"
                                autofocus autocomplete="meta_description">
                                {{ old('meta_description', '') }}
                            </x-text-textarea>
                            <x-input-error :messages="$errors->get('meta_description')" class="mt-2" />
                        </div>

                        {{-- Main Image --}}
                        <div class="md:col-span-6">
                            <x-input-label for="image" :value="__('Unit Image')" />
                            <x-text-input id="image" name="image" type="file" accept="image/*"
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        {{-- Gallery --}}
                        <div class="md:col-span-6">
                            <x-input-label for="gallery" :value="__('Unit Gallery (Multiple Images)')" />
                            <x-text-input id="gallery" name="gallery[]" type="file" accept="image/*" multiple
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('gallery')" class="mt-2" />
                        </div>

                        {{-- Floorplans --}}
                        <div class="md:col-span-6">
                            <x-input-label for="floorplan" :value="__('Floorplan')" />
                            <x-text-input id="floorplan" name="floorplan" type="file"
                                accept="application/pdf,image/*" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('floorplan')" class="mt-2" />
                        </div>

                        {{-- Payment Plans --}}
                        <div class="md:col-span-6">
                            <x-input-label for="payment_plan" :value="__('Payment Plan')" />
                            <x-text-input id="payment_plan" name="payment_plan" type="file"
                                accept="application/pdf,image/*" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('payment_plan')" class="mt-2" />
                        </div>

                        {{-- Structured Payment Plans --}}
                        <div class="md:col-span-12 border-t dark:border-gray-700 pt-6 mt-2">
                            <div class="flex items-center justify-between mb-3">
                                <x-input-label :value="__('Payment Plans')" class="mb-0" />
                                <button type="button" class="text-sm text-primary font-medium" @click="addPlan()">+ Add Payment Plan</button>
                            </div>

                            <template x-for="(plan, pIndex) in plans" :key="pIndex">
                                <div class="border rounded-lg p-4 mb-4 dark:border-gray-700">
                                    <div class="flex flex-wrap gap-3 items-end mb-3">
                                        <div class="flex-1 min-w-[200px]">
                                            <x-input-label :value="__('Plan Name')" />
                                            <input type="text" :name="`payment_plans[${pIndex}][name]`" x-model="plan.name"
                                                placeholder="e.g. Plan A"
                                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm" required>
                                        </div>
                                        <div class="flex-1 min-w-[200px]">
                                            <x-input-label :value="__('Tentative Sale/Purchase Date')" />
                                            <input type="date" :name="`payment_plans[${pIndex}][tentative_sale_date]`"
                                                x-model="plan.tentative_sale_date"
                                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm">
                                        </div>
                                        <button type="button" class="text-red-500 text-sm mb-2" @click="plans.splice(pIndex, 1)">Remove Plan</button>
                                    </div>

                                    <div class="overflow-x-auto">
                                        <table class="w-full text-sm mb-2">
                                            <thead>
                                                <tr class="text-left text-gray-500 dark:text-gray-400">
                                                    <th class="pr-2 pb-1">Within (months of sale date)</th>
                                                    <th class="pr-2 pb-1">Percent (%)</th>
                                                    <th class="pr-2 pb-1">Auto-Calc</th>
                                                    <th class="pr-2 pb-1">Amount (AED)</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="(m, mIndex) in plan.milestones" :key="mIndex">
                                                    <tr>
                                                        <td class="pr-2 py-1">
                                                            <input type="number" min="0"
                                                                :name="`payment_plans[${pIndex}][milestones][${mIndex}][month_offset]`"
                                                                x-model.number="m.month_offset"
                                                                class="w-24 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 px-2 py-1 rounded-md shadow-sm" required>
                                                        </td>
                                                        <td class="pr-2 py-1">
                                                            <input type="number" step="0.01" min="0" max="100"
                                                                :name="`payment_plans[${pIndex}][milestones][${mIndex}][percent]`"
                                                                x-model.number="m.percent"
                                                                @input="if (!m.is_amount_manual) m.amount = computeAmount(m.percent)"
                                                                class="w-24 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 px-2 py-1 rounded-md shadow-sm" required>
                                                        </td>
                                                        <td class="pr-2 py-1 text-center">
                                                            <input type="hidden"
                                                                :name="`payment_plans[${pIndex}][milestones][${mIndex}][is_amount_manual]`"
                                                                :value="m.is_amount_manual ? 1 : 0">
                                                            <input type="checkbox" :checked="!m.is_amount_manual"
                                                                @change="m.is_amount_manual = !$event.target.checked; if (!m.is_amount_manual) m.amount = computeAmount(m.percent)">
                                                        </td>
                                                        <td class="pr-2 py-1">
                                                            <input type="number" step="0.01" min="0"
                                                                :name="`payment_plans[${pIndex}][milestones][${mIndex}][amount]`"
                                                                x-model.number="m.amount" :disabled="!m.is_amount_manual"
                                                                class="w-32 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 px-2 py-1 rounded-md shadow-sm disabled:opacity-60">
                                                        </td>
                                                        <td class="py-1">
                                                            <button type="button" class="text-red-500"
                                                                @click="plan.milestones.splice(mIndex, 1)">×</button>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                        <button type="button" class="text-blue-500 text-sm"
                                            @click="plan.milestones.push({ month_offset: 0, percent: '', is_amount_manual: false, amount: null })">
                                            + Add Milestone
                                        </button>
                                    </div>
                                </div>
                            </template>
                            <p class="text-sm text-gray-400" x-show="plans.length === 0">No payment plans added yet.</p>
                        </div>

                        {{-- Video --}}
                        <div class="md:col-span-12">
                            <x-input-label for="video" :value="__('Unit Video (MP4, MOV, AVI, WebM – max 256 MB)')" />
                            <x-text-input id="video" name="video" type="file"
                                accept="video/mp4,video/quicktime,video/avi,video/webm,video/*"
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('video')" class="mt-2" />
                        </div>

                        {{-- Availability Status --}}
                        <div class="md:col-span-4">
                            <x-input-label for="availability_status" :value="__('Availability Status')" />
                            <x-select name="availability_status" :options="[
                                'available' => 'Available',
                                'reserved' => 'Reserved',
                                'sold' => 'Sold',
                            ]" :value="old('availability_status', 'available')" />
                            <x-input-error :messages="$errors->get('availability_status')" class="mt-2" />
                        </div>

                        {{-- Is Active --}}
                        <div class="md:col-span-4">
                            <x-input-label for="is_active" :value="__('Is Active')" />
                            <x-select name="is_active" :options="['1' => 'Active', '0' => 'Inactive']" :value="old('is_active', '1')" />
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>
                        {{-- Is Featured --}}
                        <div class="md:col-span-4">
                            <x-input-label for="is_featured" :value="__('Is Featured')" />
                            <x-select name="is_featured" :options="['1' => 'Yes', '0' => 'No']" :value="old('is_featured', '0')" />
                            <x-input-error :messages="$errors->get('is_featured')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="flex flex-col items-center justify-center gap-4">
                        <x-primary-button>{{ __('Submit') }}</x-primary-button>

                        @if (session('status') === 'success')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-green-600 dark:text-green-600">
                                {{ session('message') }}
                            </p>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Structured Payment Plans --}}
    <script>
        function unitPaymentPlans() {
            return {
                plans: [],
                addPlan() {
                    this.plans.push({ name: '', tentative_sale_date: '', milestones: [] });
                },
                computeAmount(percent) {
                    const priceInput = document.getElementById('price');
                    const price = priceInput ? parseFloat(priceInput.value) : NaN;
                    if (!price || !percent) return null;
                    return Math.round((percent / 100) * price * 100) / 100;
                },
            };
        }
    </script>

    {{-- TomSelect for Accommodations --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // List of select IDs
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



    {{-- Google Maps --}}
    <script>
        function initialize() {
            const locationInputs = document.getElementsByClassName("map-input");
            const autocompletes = [];
            const geocoder = new google.maps.Geocoder;
            for (let i = 0; i < locationInputs.length; i++) {
                const input = locationInputs[i];
                const latitude = parseFloat(document.getElementById("latitude").value) || 25.0762805;
                const longitude = parseFloat(document.getElementById("longitude").value) || 54.8978354;
                const map = new google.maps.Map(document.getElementById('location-map'), {
                    center: {
                        lat: latitude,
                        lng: longitude
                    },
                    zoom: 16,
                    mapId: "{{ config('app.mapDashboard') }}"
                });
                const marker = new google.maps.marker.AdvancedMarkerElement({
                    map: map,
                    position: {
                        lat: latitude,
                        lng: longitude
                    },
                });
                const autocomplete = new google.maps.places.Autocomplete(input);
                autocompletes.push({
                    input,
                    map,
                    marker,
                    autocomplete
                });
            }

            autocompletes.forEach(obj => {
                google.maps.event.addListener(obj.autocomplete, 'place_changed', function() {
                    obj.marker.map = null;
                    const place = obj.autocomplete.getPlace();
                    if (!place.geometry) {
                        alert("No details available for input: '" + place.name + "'");
                        obj.input.value = "";
                        return;
                    }
                    obj.marker.position = place.geometry.location;
                    obj.marker.map = obj.map;
                    obj.map.setCenter(place.geometry.location);
                    obj.map.setZoom(17);
                    setLocationCoordinates(place.geometry.location.lat(), place.geometry.location.lng());
                });
            });
        }

        function setLocationCoordinates(lat, lng) {
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;
        }
    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('app.mapapi') }}&libraries=places,marker&callback=initialize"
        async defer></script>

</x-app-layout>
