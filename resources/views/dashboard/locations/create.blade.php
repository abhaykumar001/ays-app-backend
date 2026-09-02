<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('locations.index')">Locations</x-nav-link>
        <span class="mx-2 text-gray-400">/</span>
        <x-nav-link :href="route('locations.create')" :active="true">Add Location</x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Add Location</h2>

                <form method="POST" action="{{ route('locations.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="grid md:grid-cols-12 gap-5">

                        {{-- Title --}}
                        <div class="md:col-span-8">
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                :value="old('title')" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        {{-- Sort Order --}}
                        <div class="md:col-span-4">
                            <x-input-label for="sort_order" :value="__('Sort Order')" />
                            <x-text-input id="sort_order" name="sort_order" type="number" min="0"
                                class="mt-1 block w-full" :value="old('sort_order', 0)" />
                            <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                        </div>

                        {{-- Description --}}
                        <div class="md:col-span-12">
                            <x-input-label for="description" :value="__('Description')" />
                            <x-text-textarea id="description" name="description" class="mt-1 block w-full" required>{{ old('description') }}</x-text-textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        {{-- Image --}}
                        <div class="md:col-span-6">
                            <x-input-label for="image" :value="__('Main Image')" />
                            <x-text-input id="image" name="image" type="file" accept="image/*"
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        {{-- Gallery --}}
                        <div class="md:col-span-6">
                            <x-input-label for="gallery" :value="__('Gallery (Multiple Images)')" />
                            <x-text-input id="gallery" name="gallery[]" type="file" accept="image/*" multiple
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('gallery')" class="mt-2" />
                        </div>

                        {{-- Active --}}
                        <div class="md:col-span-6 flex items-center">
                            <label for="is_active" class="flex items-center gap-2 mt-6 cursor-pointer">
                                <input type="checkbox" id="is_active" name="is_active" value="1"
                                    {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                    class="rounded border-gray-300 dark:border-gray-700 text-primary focus:ring-primary-light">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Active (visible in app)') }}</span>
                            </label>
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>

                        {{-- Phone --}}
                        <div class="md:col-span-4">
                            <x-input-label for="phone" :value="__('Phone')" />
                            <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full"
                                :value="old('phone')" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        {{-- Email --}}
                        <div class="md:col-span-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                :value="old('email')" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- Website URL --}}
                        <div class="md:col-span-4">
                            <x-input-label for="website_url" :value="__('Website URL')" />
                            <x-text-input id="website_url" name="website_url" type="url" class="mt-1 block w-full"
                                :value="old('website_url')" />
                            <x-input-error :messages="$errors->get('website_url')" class="mt-2" />
                        </div>

                        {{-- Opening Hours --}}
                        <div class="md:col-span-12">
                            <x-input-label :value="__('Opening Hours')" />
                        </div>
                        @foreach (['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'] as $day => $label)
                            <div class="md:col-span-3">
                                <x-input-label for="opening_hours_{{ $day }}" :value="__($label)" />
                                <x-text-input id="opening_hours_{{ $day }}" name="opening_hours[{{ $day }}]" type="text"
                                    class="mt-1 block w-full" placeholder="e.g. 9 AM - 6 PM"
                                    :value="old('opening_hours.' . $day)" />
                                <x-input-error :messages="$errors->get('opening_hours.' . $day)" class="mt-2" />
                            </div>
                        @endforeach

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

                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>Save Location</x-primary-button>
                        <a href="{{ route('locations.index') }}" class="text-sm text-gray-500 hover:underline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Google Maps --}}
    <script>
        function initializeLocationMap() {
            const locationInputs = document.getElementsByClassName("map-input");
            const autocompletes = [];
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
        src="https://maps.googleapis.com/maps/api/js?key={{ config('app.mapapi') }}&libraries=places,marker&callback=initializeLocationMap"
        async defer></script>

</x-app-layout>
