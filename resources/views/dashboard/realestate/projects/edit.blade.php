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
                            {{ __('Edit Project') }}: {{ $project->name }}
                        </h2>
                    </div>
                </div>

                <form method="POST" action="{{ route('projects.update', $project->id) }}" class="mt-6 space-y-8"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid md:grid-cols-12 gap-5">
                        @can('edit_projects')
                        {{-- Project Name --}}
                        <div class="md:col-span-6">
                            <x-input-label for="name" :value="__('Project Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                :value="old('name', $project->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Project Code --}}
                        <div class="md:col-span-6">
                            <x-input-label for="project_code" :value="__('Project Code')" />
                            <x-text-input id="project_code" name="project_code" type="text" class="mt-1 block w-full"
                                :value="old('project_code', $project->project_code)" />
                            <x-input-error :messages="$errors->get('project_code')" class="mt-2" />
                        </div>

                        {{-- Community --}}
                        <div class="md:col-span-4">
                            <x-input-label for="community_id" :value="__('Community')" />
                            <x-select name="community_id" required :options="$communities->pluck('name', 'id')->toArray()" :value="old('community_id', $project->community_id)" />
                            <x-input-error :messages="$errors->get('community_id')" class="mt-2" />
                        </div>

                        {{-- Sub Community --}}
                        <div class="md:col-span-4">
                            <x-input-label for="sub_community" :value="__('Sub Community')" />
                            <x-text-input id="sub_community" name="sub_community" type="text"
                                class="mt-1 block w-full" :value="old('sub_community', $project->sub_community)" />
                            <x-input-error :messages="$errors->get('sub_community')" class="mt-2" />
                        </div>

                        {{-- City --}}
                        <div class="md:col-span-4">
                            <x-input-label for="city" :value="__('City')" />
                            <x-text-input id="city" name="city" type="text"
                                class="mt-1 block w-full" :value="old('city', $project->city)" />
                            <x-input-error :messages="$errors->get('city')" class="mt-2" />
                        </div>

                        {{-- Project Status --}}
                        <div class="md:col-span-4">
                            <x-input-label for="project_status" :value="__('Project Status')" />
                            <x-select name="project_status" required :options="[
                                'off_plan' => 'Off Plan',
                                'ready' => 'Ready',
                                'under_construction' => 'Under Construction',
                            ]" :value="old('project_status', $project->project_status)" />
                            <x-input-error :messages="$errors->get('project_status')" class="mt-2" />
                        </div>

                        {{-- Sales Status --}}
                        <div class="md:col-span-4">
                            <x-input-label for="sales_status" :value="__('Sales Status')" />
                            <x-select name="sales_status" required :options="[
                                'available' => 'Available',
                                'sold_out' => 'Sold Out',
                                'coming_soon' => 'Coming Soon',
                            ]" :value="old('sales_status', $project->sales_status)" />
                            <x-input-error :messages="$errors->get('sales_status')" class="mt-2" />
                        </div>

                        {{-- Is Featured --}}
                        <div class="md:col-span-4">
                            <x-input-label for="is_featured" :value="__('Is Featured')" />
                            <x-select name="is_featured" :options="['1' => 'Yes', '0' => 'No']" :value="old('is_featured', $project->is_featured ? '1' : '0')" />
                            <x-input-error :messages="$errors->get('is_featured')" class="mt-2" />
                        </div>

                        {{-- Is New Launch --}}
                        <div class="md:col-span-4">
                            <x-input-label for="is_new_launch" :value="__('Is New Launch')" />
                            <x-select name="is_new_launch" :options="['1' => 'Yes', '0' => 'No']" :value="old('is_new_launch', $project->is_new_launch ? '1' : '0')" />
                            <x-input-error :messages="$errors->get('is_new_launch')" class="mt-2" />
                        </div>

                        {{-- Is Hot Selling --}}
                        <div class="md:col-span-4">
                            <x-input-label for="is_hot_selling" :value="__('Is Hot Selling')" />
                            <x-select name="is_hot_selling" :options="['1' => 'Yes', '0' => 'No']" :value="old('is_hot_selling', $project->is_hot_selling ? '1' : '0')" />
                            <x-input-error :messages="$errors->get('is_hot_selling')" class="mt-2" />
                        </div>

                        {{-- Is Active --}}
                        <div class="md:col-span-4">
                            <x-input-label for="is_active" :value="__('Is Active')" />
                            <x-select name="is_active" :options="['true' => 'Active', 'false' => 'Inactive']" :value="old('is_active', $project->is_active ? 'true' : 'false')" />
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>

                        {{-- Accommodations --}}
                        <div class="md:col-span-12">
                            <x-input-label for="accommodations" :value="__('Project Accommodations')" />
                            <select id="accommodations" name="accommodations[]" multiple
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm"
                                placeholder="Select accommodations">
                                @foreach ($accommodations as $acc)
                                    <option value="{{ $acc->id }}"
                                        {{ $project->accommodations->contains($acc->id) || collect(old('accommodations'))->contains($acc->id) ? 'selected' : '' }}>
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
                                        {{ $project->amenities->contains($amenity->id) || collect(old('amenities'))->contains($amenity->id) ? 'selected' : '' }}>
                                        {{ $amenity->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('amenities')" class="mt-2" />
                        </div>

                        @endcan

                        {{-- Starting Price / Price per SqFt — visible to full project editors and to
                             Financial Team (edit_project_pricing), the only fields the latter can touch --}}
                        @canany(['edit_projects', 'edit_project_pricing'])
                        <div class="md:col-span-4">
                            <x-input-label for="starting_price" :value="__('Starting Price')" />
                            <x-text-input id="starting_price" name="starting_price" type="text"
                                class="mt-1 block w-full" :value="old('starting_price', $project->starting_price)" />
                            <x-input-error :messages="$errors->get('starting_price')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label for="price_per_sqft" :value="__('Price per SqFt')" />
                            <x-text-input id="price_per_sqft" name="price_per_sqft" type="text"
                                class="mt-1 block w-full" :value="old('price_per_sqft', $project->price_per_sqft)" />
                            <x-input-error :messages="$errors->get('price_per_sqft')" class="mt-2" />
                        </div>
                        @endcanany

                        @can('edit_projects')
                        {{-- Total Units --}}
                        <div class="md:col-span-4">
                            <x-input-label for="total_units" :value="__('Total Units')" />
                            <x-text-input id="total_units" name="total_units" type="number" class="mt-1 block w-full"
                                :value="old('total_units', $project->total_units)" />
                            <x-input-error :messages="$errors->get('total_units')" class="mt-2" />
                        </div>

                        {{-- Available Units --}}
                        <div class="md:col-span-4">
                            <x-input-label for="available_units" :value="__('Available Units')" />
                            <x-text-input id="available_units" name="available_units" type="number"
                                class="mt-1 block w-full" :value="old('available_units', $project->available_units)" />
                            <x-input-error :messages="$errors->get('available_units')" class="mt-2" />
                        </div>

                        {{-- ROI --}}
                        <div class="md:col-span-4">
                            <x-input-label for="roi" :value="__('Highest ROI')" />
                            <x-text-input id="roi" name="roi" type="number" class="mt-1 block w-full"
                                :value="old('roi', $project->roi)" />
                            <x-input-error :messages="$errors->get('roi')" class="mt-2" />
                        </div>

                        {{-- Construction Progress --}}
                        <div class="md:col-span-4">
                            <x-input-label for="construction_progress" :value="__('Construction Progress (%)')" />
                            <x-text-input id="construction_progress" name="construction_progress" type="number"
                                class="mt-1 block w-full" :value="old('construction_progress', $project->construction_progress)" />
                            <x-input-error :messages="$errors->get('construction_progress')" class="mt-2" />
                        </div>

                        {{-- Bedrooms --}}
                        <div class="md:col-span-4">
                            <x-input-label for="bedrooms" :value="__('Bedrooms (e.g. 1-4)')" />
                            <x-text-input id="bedrooms" name="bedrooms" type="text"
                                class="mt-1 block w-full" :value="old('bedrooms', $project->bedrooms)" />
                            <x-input-error :messages="$errors->get('bedrooms')" class="mt-2" />
                        </div>
                        <div class="md:col-span-4">
                            <x-input-label for="bathrooms" :value="__('Bathrooms (e.g. 1-4)')" />
                            <x-text-input id="bathrooms" name="bathrooms" type="text"
                                class="mt-1 block w-full" :value="old('bathrooms', $project->bathrooms)" />
                            <x-input-error :messages="$errors->get('bathrooms')" class="mt-2" />
                        </div>

                        {{-- Min Size --}}
                        <div class="md:col-span-4">
                            <x-input-label for="min_size" :value="__('Minimum Size in Sqft')" />
                            <x-text-input id="min_size" name="min_size" type="number"
                                class="mt-1 block w-full" :value="old('min_size', $project->min_size)" />
                            <x-input-error :messages="$errors->get('min_size')" class="mt-2" />
                        </div>

                        {{-- Max Size --}}
                        <div class="md:col-span-4">
                            <x-input-label for="max_size" :value="__('Maximum Size in Sqft')" />
                            <x-text-input id="max_size" name="max_size" type="number"
                                class="mt-1 block w-full" :value="old('max_size', $project->max_size)" />
                            <x-input-error :messages="$errors->get('max_size')" class="mt-2" />
                        </div>

                        {{-- Launch Date --}}
                        <div class="md:col-span-3">
                            <x-input-label for="launch_date" :value="__('Launch Date')" />
                            <x-text-input id="launch_date" name="launch_date" type="date"
                                class="mt-1 block w-full" :value="old('launch_date', $project->launch_date)" />
                            <x-input-error :messages="$errors->get('launch_date')" class="mt-2" />
                        </div>

                        {{-- Handover --}}
                        <div class="md:col-span-3">
                            <x-input-label for="handover" :value="__('Handover (e.g. Dec 2028)')" />
                            <x-text-input id="handover" name="handover" type="text"
                                class="mt-1 block w-full" :value="old('handover', $project->handover)" />
                            <x-input-error :messages="$errors->get('handover')" class="mt-2" />
                        </div>

                        {{-- Handover Date --}}
                        <div class="md:col-span-3">
                            <x-input-label for="handover_date" :value="__('Handover Date')" />
                            <x-text-input id="handover_date" name="handover_date" type="date"
                                class="mt-1 block w-full" :value="old('handover_date', $project->handover_date)" />
                            <x-input-error :messages="$errors->get('handover_date')" class="mt-2" />
                        </div>

                        {{-- Sort Order --}}
                        <div class="md:col-span-3">
                            <x-input-label for="sort_order" :value="__('Order')" />
                            <x-text-input id="sort_order" name="sort_order" type="number"
                                class="mt-1 block w-full" :value="old('sort_order', $project->sort_order)" />
                            <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                        </div>

                        {{-- Location Map --}}
                        <div class="md:col-span-12">
                            <x-input-label for="address" :value="__('Location')" />
                            <x-text-input id="address" name="address" type="text"
                                class="mt-1 block w-full map-input" :value="old('address', $project->address)" />
                            <x-text-input type="hidden" name="latitude" id="latitude" :value="old('latitude', $project->latitude ?? 0)" />
                            <x-text-input type="hidden" name="longitude" id="longitude" :value="old('longitude', $project->longitude ?? 0)" />
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
                            <x-text-input id="short_description" name="short_description" type="text"
                                class="mt-1 block w-full" :value="old('short_description', $project->short_description)" />
                            <x-input-error :messages="$errors->get('short_description')" class="mt-2" />
                        </div>

                        {{-- Full Description --}}
                        <div class="md:col-span-12">
                            <x-input-label for="description" :value="__('Full Description')" />
                            <x-text-textarea id="description" name="description" class="mt-1 block w-full">
                                {{ old('description', $project->description) }}
                            </x-text-textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        {{-- Title Description (project detail page tagline) --}}
                        <div class="md:col-span-12">
                            <x-input-label for="title_description" :value="__('Title Description (tagline shown under project name)')" />
                            <x-text-input id="title_description" name="title_description" type="text" class="mt-1 block w-full"
                                :value="old('title_description', $project->title_description)" />
                            <x-input-error :messages="$errors->get('title_description')" class="mt-2" />
                        </div>

                        {{-- Quote Description --}}
                        <div class="md:col-span-12">
                            <x-input-label for="quote_description" :value="__('Description Quotation (quote block on project detail page)')" />
                            <x-text-textarea id="quote_description" name="quote_description" class="mt-1 block w-full">
                                {{ old('quote_description', $project->quote_description) }}
                            </x-text-textarea>
                            <x-input-error :messages="$errors->get('quote_description')" class="mt-2" />
                        </div>

                        {{-- Materiality Title --}}
                        <div class="md:col-span-12">
                            <x-input-label for="materiality_title" :value="__('Materiality Section Title')" />
                            <x-text-input id="materiality_title" name="materiality_title" type="text" class="mt-1 block w-full"
                                :value="old('materiality_title', $project->materiality_title)" />
                            <x-input-error :messages="$errors->get('materiality_title')" class="mt-2" />
                        </div>

                        {{-- Materiality Description --}}
                        <div class="md:col-span-12">
                            <x-input-label for="materiality_description" :value="__('Materiality Section Description')" />
                            <x-text-textarea id="materiality_description" name="materiality_description" class="mt-1 block w-full">
                                {{ old('materiality_description', $project->materiality_description) }}
                            </x-text-textarea>
                            <x-input-error :messages="$errors->get('materiality_description')" class="mt-2" />
                        </div>

                        {{-- Existing Materiality Images --}}
                        @if ($project->getMedia('materiality')->isNotEmpty())
                            <div class="md:col-span-12">
                                <x-input-label :value="__('Current Materiality Images')" />
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @foreach ($project->getMedia('materiality') as $media)
                                        <img src="{{ $media->getUrl() }}" class="h-24 w-auto rounded" alt="Materiality image">
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Materiality Images --}}
                        <div class="md:col-span-12">
                            <x-input-label for="materiality_images" :value="__('Add Materiality Images (appended to existing)')" />
                            <x-text-input id="materiality_images" name="materiality_images[]" type="file" accept="image/*" multiple
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('materiality_images')" class="mt-2" />
                        </div>

                        {{-- SEO --}}
                        <div class="md:col-span-12">
                            <x-input-label for="meta_title" :value="__('Meta Title')" />
                            <x-text-input id="meta_title" name="meta_title" type="text" class="mt-1 block w-full"
                                :value="old('meta_title', $project->meta_title)" />
                            <x-input-error :messages="$errors->get('meta_title')" class="mt-2" />
                        </div>
                        <div class="md:col-span-12">
                            <x-input-label for="meta_keywords" :value="__('Meta Keywords')" />
                            <x-text-input id="meta_keywords" name="meta_keywords" type="text"
                                class="mt-1 block w-full" :value="old('meta_keywords', $project->meta_keywords)" />
                            <x-input-error :messages="$errors->get('meta_keywords')" class="mt-2" />
                        </div>
                        <div class="md:col-span-12">
                            <x-input-label for="meta_description" :value="__('Meta Description')" />
                            <x-text-textarea id="meta_description" name="meta_description" class="mt-1 block w-full">
                                {{ old('meta_description', $project->meta_description) }}
                            </x-text-textarea>
                            <x-input-error :messages="$errors->get('meta_description')" class="mt-2" />
                        </div>

                        {{-- Existing Image --}}
                        @if ($project->getFirstMediaUrl('images'))
                            <div class="md:col-span-12">
                                <x-input-label :value="__('Current Project Image')" />
                                <img src="{{ $project->getFirstMediaUrl('images', 'resize') ?: $project->getFirstMediaUrl('images') }}"
                                    class="h-32 w-auto rounded mt-1" alt="Project image">
                            </div>
                        @endif

                        {{-- New Image --}}
                        <div class="md:col-span-6">
                            <x-input-label for="image" :value="__('Replace Project Image')" />
                            <x-text-input id="image" name="image" type="file" accept="image/*"
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        {{-- Brochure --}}
                        <div class="md:col-span-6">
                            <x-input-label for="brochure" :value="__('Replace Brochure (PDF)')" />
                            <x-text-input id="brochure" name="brochure" type="file" accept="application/pdf"
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('brochure')" class="mt-2" />
                        </div>

                        {{-- Gallery --}}
                        <div class="md:col-span-12">
                            <x-input-label for="gallery" :value="__('Add Gallery Images (appended to existing)')" />
                            <x-text-input id="gallery" name="gallery[]" type="file" accept="image/*" multiple
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('gallery')" class="mt-2" />
                        </div>

                        {{-- Floorplan --}}
                        <div class="md:col-span-12">
                            <x-input-label for="floorplans" :value="__('Replace Floorplan')" />
                            <x-text-input id="floorplans" name="floorplan" type="file" accept="application/pdf,image/*"
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('floorplans')" class="mt-2" />
                        </div>

                        {{-- Payment Plan --}}
                        <div class="md:col-span-12">
                            <x-input-label for="payment_plans" :value="__('Replace Payment Plan')" />
                            <x-text-input id="payment_plans" name="payment_plan" type="file"
                                accept="application/pdf,image/*" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('payment_plans')" class="mt-2" />
                        </div>

                        {{-- Virtual Tour (360°) URL --}}
                        <div class="md:col-span-12">
                            <x-input-label for="virtual_tour_url" :value="__('360° Virtual Tour URL')" />
                            <x-text-input id="virtual_tour_url" name="virtual_tour_url" type="url"
                                class="mt-1 block w-full"
                                placeholder="https://example.com/virtual-tour"
                                :value="old('virtual_tour_url', $project->virtual_tour_url)" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Paste the full URL of the 360° virtual tour website. When provided, a 360° button appears in the app for this project.</p>
                            <x-input-error :messages="$errors->get('virtual_tour_url')" class="mt-2" />
                        </div>

                        {{-- Video --}}
                        <div class="md:col-span-12">
                            <x-input-label for="video" :value="__('Replace Project Video (MP4, MOV, AVI, WebM – max 256 MB)')" />
                            @if ($project->hasMedia('videos'))
                                <div class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                    Current: {{ $project->getFirstMedia('videos')?->file_name }}
                                    <video src="{{ $project->getFirstMediaUrl('videos') }}" controls class="mt-1 h-32 rounded"></video>
                                </div>
                            @endif
                            <x-text-input id="video" name="video" type="file"
                                accept="video/mp4,video/quicktime,video/avi,video/webm,video/*"
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('video')" class="mt-2" />
                        </div>
                        @endcan
                    </div>

                    <div class="flex flex-col items-center justify-center gap-4">
                        <x-primary-button>{{ __('Update Project') }}</x-primary-button>

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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            ['amenities', 'accommodations'].forEach(function (id) {
                const el = document.getElementById(id);
                if (el) {
                    new TomSelect(el, {
                        create: false,
                        persist: false,
                        plugins: ['remove_button'],
                        onItemAdd: function () {
                            this.setTextboxValue('');
                            this.refreshOptions();
                        },
                    });
                }
            });
        });
    </script>

    <script>
        function initialize() {
            const locationInputs = document.getElementsByClassName("map-input");
            const autocompletes = [];
            for (let i = 0; i < locationInputs.length; i++) {
                const input = locationInputs[i];
                const latitude  = parseFloat(document.getElementById("latitude").value)  || 25.0762805;
                const longitude = parseFloat(document.getElementById("longitude").value) || 54.8978354;
                const map = new google.maps.Map(document.getElementById('location-map'), {
                    center: { lat: latitude, lng: longitude },
                    zoom: 16,
                    mapId: "{{ config('app.mapDashboard') }}"
                });
                const marker = new google.maps.marker.AdvancedMarkerElement({
                    map: map,
                    position: { lat: latitude, lng: longitude },
                });
                const autocomplete = new google.maps.places.Autocomplete(input);
                autocompletes.push({ input, map, marker, autocomplete });
            }
            autocompletes.forEach(obj => {
                google.maps.event.addListener(obj.autocomplete, 'place_changed', function () {
                    obj.marker.map = null;
                    const place = obj.autocomplete.getPlace();
                    if (!place.geometry) { obj.input.value = ""; return; }
                    obj.marker.position = place.geometry.location;
                    obj.marker.map      = obj.map;
                    obj.map.setCenter(place.geometry.location);
                    obj.map.setZoom(17);
                    document.getElementById("latitude").value  = place.geometry.location.lat();
                    document.getElementById("longitude").value = place.geometry.location.lng();
                });
            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('app.mapapi') }}&libraries=places,marker&callback=initialize" async defer></script>
</x-app-layout>
