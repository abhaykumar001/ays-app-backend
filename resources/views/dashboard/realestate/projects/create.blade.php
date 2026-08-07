<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('projects.index')" :active="true">
            {{ __('Projects') }}
        </x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div class="my-auto">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Create New Project') }}
                        </h2>
                    </div>
                </div>

                <form method="POST" action="{{ route('projects.store') }}" class="mt-6 space-y-8"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="grid md:grid-cols-12 gap-5">
                        {{-- Project Name --}}
                        <div class="md:col-span-6">
                            <x-input-label for="name" :value="__('Project Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Project Code --}}
                        <div class="md:col-span-6">
                            <x-input-label for="project_code" :value="__('Project Code')" />
                            <x-text-input id="project_code" name="project_code" type="text" class="mt-1 block w-full"
                                :value="old('project_code')" />
                            <x-input-error :messages="$errors->get('project_code')" class="mt-2" />
                        </div>

                        {{-- Community --}}
                        <div class="md:col-span-4">
                            <x-input-label for="community_id" :value="__('Community')" />
                            <x-select name="community_id" required :options="$communities->pluck('name', 'id')->toArray()" :value="old('community_id')" />
                            <x-input-error :messages="$errors->get('community_id')" class="mt-2" />
                        </div>

                        {{-- Sub Community --}}
                        <div class="md:col-span-4">
                            <x-input-label for="sub_community" :value="__('Sub Community')" />
                            <x-text-input id="sub_community" name="sub_community" type="text"
                                class="mt-1 block w-full" :value="old('sub_community')" />
                            <x-input-error :messages="$errors->get('sub_community')" class="mt-2" />
                        </div>

                        {{-- City --}}
                        <div class="md:col-span-4">
                            <x-input-label for="city" :value="__('City')" />
                            <x-text-input id="city" name="city" type="text"
                                class="mt-1 block w-full" :value="old('city')" />
                            <x-input-error :messages="$errors->get('city')" class="mt-2" />
                        </div>

                        {{-- Project Status --}}
                        <div class="md:col-span-4">
                            <x-input-label for="project_status" :value="__('Project Status')" />
                            <x-select name="project_status" required :options="[
                                'off_plan' => 'Off Plan',
                                'ready' => 'Ready',
                                'under_construction' => 'Under Construction',
                            ]" :value="old('project_status')" />
                            <x-input-error :messages="$errors->get('project_status')" class="mt-2" />
                        </div>

                        {{-- Sales Status --}}
                        <div class="md:col-span-4">
                            <x-input-label for="sales_status" :value="__('Sales Status')" />
                            <x-select name="sales_status" required :options="[
                                'available' => 'Available',
                                'sold_out' => 'Sold Out',
                                'coming_soon' => 'Coming Soon',
                            ]" :value="old('sales_status')" />
                            <x-input-error :messages="$errors->get('sales_status')" class="mt-2" />
                        </div>

                        {{-- Is Featured --}}
                        <div class="md:col-span-4">
                            <x-input-label for="is_featured" :value="__('Is Featured')" />
                            <x-select name="is_featured" :options="['1' => 'Yes', '0' => 'No']" :value="old('is_featured', '0')" />
                            <x-input-error :messages="$errors->get('is_featured')" class="mt-2" />
                        </div>
                        {{-- Is New Launch--}}
                        <div class="md:col-span-4">
                            <x-input-label for="is_new_launch" :value="__('Is New Launch')" />
                            <x-select name="is_new_launch" :options="['1' => 'Yes', '0' => 'No']" :value="old('is_new_launch', '0')" />
                            <x-input-error :messages="$errors->get('is_new_launch')" class="mt-2" />
                        </div>
                        {{-- Is Hot Selling --}}
                        <div class="md:col-span-4">
                            <x-input-label for="is_hot_selling" :value="__('Is Hot Selling')" />
                            <x-select name="is_hot_selling" :options="['1' => 'Yes', '0' => 'No']" :value="old('is_hot_selling', '0')" />
                            <x-input-error :messages="$errors->get('is_hot_selling')" class="mt-2" />
                        </div>
                        {{-- Is Active --}}
                        <div class="md:col-span-4">
                            <x-input-label for="is_active" :value="__('Is Active')" />
                            <x-select name="is_active" :options="['true' => 'Active', 'false' => 'Inactive']" :value="old('is_active', 'true')" />
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

                        {{-- Price Display --}}
                        <div class="md:col-span-4">
                            <x-input-label for="price_status" :value="__('Price Display')" />
                            <x-select name="price_status" required :options="[
                                'price' => 'Show Starting Price',
                                'on_request' => 'Price on Request',
                                'coming_soon' => 'Coming Soon',
                                'sold_out' => 'Sold Out',
                            ]" :value="old('price_status', 'price')" onchange="
                                const inp = document.getElementById('starting_price');
                                if (this.value === 'price') { inp.disabled = false; inp.focus(); }
                                else { inp.value = ''; inp.disabled = true; }
                            " />
                            <x-input-error :messages="$errors->get('price_status')" class="mt-2" />
                        </div>

                        {{-- Starting Price --}}
                        <div class="md:col-span-4">
                            <x-input-label for="starting_price" :value="__('Starting Price')" />
                            <x-text-input id="starting_price" name="starting_price" type="text"
                                class="mt-1 block w-full" :value="old('starting_price')"
                                :disabled="old('price_status', 'price') !== 'price'" />
                            <x-input-error :messages="$errors->get('starting_price')" class="mt-2" />
                        </div>

                        {{-- Price per SqFt --}}
                        <div class="md:col-span-4">
                            <x-input-label for="price_per_sqft" :value="__('Price per SqFt')" />
                            <x-text-input id="price_per_sqft" name="price_per_sqft" type="text"
                                class="mt-1 block w-full" :value="old('price_per_sqft')" />
                            <x-input-error :messages="$errors->get('price_per_sqft')" class="mt-2" />
                        </div>

                        {{-- Total Units --}}
                        <div class="md:col-span-4">
                            <x-input-label for="total_units" :value="__('Total Units')" />
                            <x-text-input id="total_units" name="total_units" type="number" class="mt-1 block w-full"
                                :value="old('total_units')" />
                            <x-input-error :messages="$errors->get('total_units')" class="mt-2" />
                        </div>

                        {{-- Available Units --}}
                        <div class="md:col-span-4">
                            <x-input-label for="available_units" :value="__('Available Units')" />
                            <x-text-input id="available_units" name="available_units" type="number"
                                class="mt-1 block w-full" :value="old('available_units')" />
                            <x-input-error :messages="$errors->get('available_units')" class="mt-2" />
                        </div>

                        {{-- ROI --}}
                        <div class="md:col-span-4">
                            <x-input-label for="roi" :value="__('Highest ROI')" />
                            <x-text-input id="roi" name="roi" type="number" class="mt-1 block w-full"
                                :value="old('roi')" />
                            <x-input-error :messages="$errors->get('roi')" class="mt-2" />
                        </div>

                        {{-- Construction Progress --}}
                        <div class="md:col-span-4">
                            <x-input-label for="construction_progress" :value="__('Construction Progress (%)')" />
                            <x-text-input id="construction_progress" name="construction_progress" type="number"
                                class="mt-1 block w-full" :value="old('construction_progress')" />
                            <x-input-error :messages="$errors->get('construction_progress')" class="mt-2" />
                        </div>
                        {{-- Bedrooms --}}
                        <div class="md:col-span-4">
                            <x-input-label for="bedrooms" :value="__('Bedrooms (1-4)')" />
                            <x-text-input id="bedrooms" name="bedrooms" type="text"
                                class="mt-1 block w-full" :value="old('bedrooms')" />
                            <x-input-error :messages="$errors->get('bedrooms')" class="mt-2" />
                        </div>
                        {{-- Bathrooms --}}
                        <div class="md:col-span-4">
                            <x-input-label for="bathrooms" :value="__('Bathrooms (1-4)')" />
                            <x-text-input id="bathrooms" name="bathrooms" type="text"
                                class="mt-1 block w-full" :value="old('bathrooms')" />
                            <x-input-error :messages="$errors->get('bathrooms')" class="mt-2" />
                        </div>
                        {{-- Minimum Size --}}
                        <div class="md:col-span-4">
                            <x-input-label for="min_size" :value="__('Minimum Size in Sqft')" />
                            <x-text-input id="min_size" name="min_size" type="number"
                                class="mt-1 block w-full" :value="old('min_size')" />
                            <x-input-error :messages="$errors->get('min_size')" class="mt-2" />
                        </div>
                        {{-- Maximum Size --}}
                        <div class="md:col-span-4">
                            <x-input-label for="max_size" :value="__('Maximum Size in Sqft')" />
                            <x-text-input id="max_size" name="max_size" type="number"
                                class="mt-1 block w-full" :value="old('max_size')" />
                            <x-input-error :messages="$errors->get('max_size')" class="mt-2" />
                        </div>
                        {{-- Launch Date --}}
                        <div class="md:col-span-3">
                            <x-input-label for="launch_date" :value="__('Launch Date')" />
                            <x-text-input id="launch_date" name="launch_date" type="date"
                                class="mt-1 block w-full" :value="old('launch_date')" />
                            <x-input-error :messages="$errors->get('launch_date')" class="mt-2" />
                        </div>
                        {{-- Handover --}}
                        <div class="md:col-span-3">
                            <x-input-label for="handover" :value="__('Handover (Eg: Dec 2028)')" />
                            <x-text-input id="handover" name="handover" type="text"
                                class="mt-1 block w-full" :value="old('handover')" />
                            <x-input-error :messages="$errors->get('handover')" class="mt-2" />
                        </div>
                        {{-- On Handover Payment --}}
                        <div class="md:col-span-3">
                            <x-input-label for="on_handover_payment" :value="__('On Handover Payment (e.g. 60/40)')" />
                            <x-text-input id="on_handover_payment" name="on_handover_payment" type="text"
                                class="mt-1 block w-full" :value="old('on_handover_payment')" />
                            <x-input-error :messages="$errors->get('on_handover_payment')" class="mt-2" />
                        </div>

                        {{-- Post Handover Payment --}}
                        <div class="md:col-span-3">
                            <x-input-label for="post_handover_payment" :value="__('Post Handover Payment (e.g. 44 MO)')" />
                            <x-text-input id="post_handover_payment" name="post_handover_payment" type="text"
                                class="mt-1 block w-full" :value="old('post_handover_payment')" />
                            <x-input-error :messages="$errors->get('post_handover_payment')" class="mt-2" />
                        </div>

                        {{-- Handover Date --}}
                        <div class="md:col-span-3">
                            <x-input-label for="handover_date" :value="__('Handover Date')" />
                            <x-text-input id="handover_date" name="handover_date" type="date"
                                class="mt-1 block w-full" :value="old('handover_date')" />
                            <x-input-error :messages="$errors->get('handover_date')" class="mt-2" />
                        </div>
                        {{-- Order --}}
                        <div class="md:col-span-3">
                            <x-input-label for="sort_order" :value="__('Order')" />
                            <x-text-input id="sort_order" name="sort_order" type="number"
                                class="mt-1 block w-full" :value="old('sort_order')" />
                            <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                        </div>
                        {{-- Map --}}
                        <div class="md:col-span-12">
                            <x-input-label for="address" :value="__('Location')" />
                            <x-text-input id="address" name="address" type="text"
                                class="mt-1 block w-full map-input" :value="old('address')" />
                            <x-text-input type="hidden" name="latitude" id="latitude" value="0" />
                            <x-text-input type="hidden" name="longitude" id="longitude" value="0" />
                            <x-input-error class="mt-2" :messages="$errors->get('address')" />
                        </div>
                        <div class="md:col-span-12">
                            <div id="location-map-container" class="w-full h-52">
                                <div class="w-full h-full" id="location-map"></div>
                            </div>
                        </div>

                        {{-- Short Description --}}
                        <div class="md:col-span-12">
                            <x-input-label for="short_description" :value="__('Short Description')" />
                            <x-text-input id="short_description" name="short_description" type="text" class="mt-1 block w-full"
                                :value="old('short_description', '')" autofocus autocomplete="short_description" />
                            <x-input-error :messages="$errors->get('short_description')" class="mt-2" />
                        </div>

                        {{-- Full Description --}}
                        <div class="md:col-span-12">
                            <x-input-label for="description" :value="__('Full Description')" />
                            <x-text-textarea id="description" name="description" class="mt-1 block w-full">
                                {{ old('description') }}
                            </x-text-textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        {{-- Title Description (project detail page tagline) --}}
                        <div class="md:col-span-12">
                            <x-input-label for="title_description" :value="__('Title Description (tagline shown under project name)')" />
                            <x-text-input id="title_description" name="title_description" type="text" class="mt-1 block w-full"
                                :value="old('title_description', '')" />
                            <x-input-error :messages="$errors->get('title_description')" class="mt-2" />
                        </div>

                        {{-- Quote Description --}}
                        <div class="md:col-span-12">
                            <x-input-label for="quote_description" :value="__('Description Quotation (quote block on project detail page)')" />
                            <x-text-textarea id="quote_description" name="quote_description" class="mt-1 block w-full">
                                {{ old('quote_description') }}
                            </x-text-textarea>
                            <x-input-error :messages="$errors->get('quote_description')" class="mt-2" />
                        </div>

                        {{-- Materiality Title --}}
                        <div class="md:col-span-12">
                            <x-input-label for="materiality_title" :value="__('Materiality Section Title')" />
                            <x-text-input id="materiality_title" name="materiality_title" type="text" class="mt-1 block w-full"
                                :value="old('materiality_title', '')" />
                            <x-input-error :messages="$errors->get('materiality_title')" class="mt-2" />
                        </div>

                        {{-- Materiality Description --}}
                        <div class="md:col-span-12">
                            <x-input-label for="materiality_description" :value="__('Materiality Section Description')" />
                            <x-text-textarea id="materiality_description" name="materiality_description" class="mt-1 block w-full">
                                {{ old('materiality_description') }}
                            </x-text-textarea>
                            <x-input-error :messages="$errors->get('materiality_description')" class="mt-2" />
                        </div>

                        {{-- Materiality Images --}}
                        <div class="md:col-span-12">
                            <x-input-label for="materiality_images" :value="__('Materiality Images (Multiple)')" />
                            <x-text-input id="materiality_images" name="materiality_images[]" type="file" accept="image/*" multiple
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('materiality_images')" class="mt-2" />
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
                        {{-- Brochure --}}
                        <div class="md:col-span-6">
                            <x-input-label for="brochure" :value="__('Brochure (PDF)')" />
                            <x-text-input id="brochure" name="brochure" type="file" accept="application/pdf"
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('brochure')" class="mt-2" />
                        </div>

                        {{-- Main Image --}}
                        <div class="md:col-span-6">
                            <x-input-label for="image" :value="__('Project Image')" />
                            <x-text-input id="image" name="image" type="file" accept="image/*"
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        {{-- Gallery --}}
                        <div class="md:col-span-12">
                            <x-input-label for="gallery" :value="__('Project Gallery (Multiple Images)')" />
                            <x-text-input id="gallery" name="gallery[]" type="file" accept="image/*" multiple
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('gallery')" class="mt-2" />
                        </div>

                        {{-- Floorplans --}}
                        <div class="md:col-span-12">
                            <x-input-label for="floorplans" :value="__('Floorplan')" />
                            <x-text-input id="floorplans" name="floorplan" type="file" accept="application/pdf,image/*"
                             class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('floorplans')" class="mt-2" />
                        </div>

                        {{-- Payment Plans --}}
                        <div class="md:col-span-12">
                            <x-input-label for="payment_plans" :value="__('Payment Plan')" />
                            <x-text-input id="payment_plans" name="payment_plan" type="file"
                                accept="application/pdf,image/*" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('payment_plans')" class="mt-2" />
                        </div>

                        {{-- Video --}}
                        <div class="md:col-span-12">
                            <x-input-label for="video" :value="__('Project Video (MP4, MOV, AVI, WebM – max 256 MB)')" />
                            <x-text-input id="video" name="video" type="file"
                                accept="video/mp4,video/quicktime,video/avi,video/webm,video/*"
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('video')" class="mt-2" />
                        </div>

                        {{-- Virtual Tour (360°) URL --}}
                        <div class="md:col-span-12">
                            <x-input-label for="virtual_tour_url" :value="__('360° Virtual Tour URL')" />
                            <x-text-input id="virtual_tour_url" name="virtual_tour_url" type="url"
                                class="mt-1 block w-full"
                                placeholder="https://example.com/virtual-tour"
                                :value="old('virtual_tour_url')" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Paste the full URL of the 360° virtual tour website. When provided, a 360° button appears in the app for this project.</p>
                            <x-input-error :messages="$errors->get('virtual_tour_url')" class="mt-2" />
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
