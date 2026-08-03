<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('communities.index')" :active="true">
            {{ __('Community') }}
        </x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div class="my-auto">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Create New Community') }}
                        </h2>
                    </div>
                </div>

                <form method="POST" action="{{ route('communities.store') }}" class="mt-6 space-y-8"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="grid md:grid-cols-12 gap-5">
                        <div class="md:col-span-8">
                            <x-input-label for="name" :value="__('Community Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                :value="old('name', '')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="md:col-span-4">
                            <x-input-label for="city" :value="__('City')" />
                            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full"
                                :value="old('city', '')" required autofocus autocomplete="city" />
                            <x-input-error :messages="$errors->get('city')" class="mt-2" />
                        </div>
                        <div class="md:col-span-12">
                            <x-input-label for="short_description" :value="__('Short Description')" />
                            <x-text-textarea id="short_description" name="short_description" class="mt-1 block w-full"
                                autofocus required autocomplete="short_description">
                                {{ old('short_description', '') }}
                            </x-text-textarea>
                            <x-input-error :messages="$errors->get('short_description')" class="mt-2" />
                        </div>
                        <div class="md:col-span-12">
                            <x-input-label for="description" :value="__('Long Description (shown on Project Detail page)')" />
                            <x-text-textarea id="description" name="description" class="mt-1 block w-full"
                                autocomplete="description">
                                {{ old('description', '') }}
                            </x-text-textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
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

                        <div class="md:col-span-4">
                            <x-input-label for="starting_price" :value="__('Starting Price')" />
                            <x-text-input id="starting_price" name="starting_price" type="text" class="mt-1 block w-full"
                                :value="old('starting_price', '')" required autofocus autocomplete="starting_price" />
                            <x-input-error :messages="$errors->get('starting_price')" class="mt-2" />
                        </div>
                        <div class="md:col-span-4">
                            <x-input-label for="growth" :value="__('Growth')" />
                            <x-text-input id="growth" name="growth" type="text" class="mt-1 block w-full"
                                :value="old('growth', '')" required autofocus autocomplete="growth" />
                            <x-input-error :messages="$errors->get('growth')" class="mt-2" />
                        </div>
                        <div class="md:col-span-4">
                            <x-input-label for="roi" :value="__('Highest ROI')" />
                            <x-text-input id="roi" name="roi" type="text" class="mt-1 block w-full"
                                :value="old('roi', '')" required autofocus autocomplete="roi" />
                            <x-input-error :messages="$errors->get('roi')" class="mt-2" />
                        </div>
                        <div class="md:col-span-4">
                            <x-input-label for="category" :value="__('Category')" />
                            <x-select name="category" required :options="[
                                'Mixed' => 'Mixed Use',
                                'Residential' => 'Residential',
                                'Commercial' => 'Commercial',
                            ]" :value="old('category', 'Full Time')" />
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>
                        <div class="md:col-span-4">
                            <x-input-label for="is_featured" :value="__('Is Featured')" />
                            <x-select name="is_featured" :options="['1' => 'Yes', '0' => 'No']" :value="old('is_featured', '0')" />
                            <x-input-error :messages="$errors->get('is_featured')" class="mt-2" />
                        </div>
                        <div class="md:col-span-4">
                            <x-input-label for="sort_order" :value="__('Sort Order')" />
                            <x-text-input id="sort_order" name="sort_order" type="number" class="mt-1 block w-full"
                                :value="old('sort_order', '')" required autofocus autocomplete="sort_order" />
                            <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                        </div>
                        <div class="md:col-span-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <x-select name="status" required :options="['active' => 'Active', 'inactive' => 'Inactive']" :value="old('status', '')" />
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                        <div class="md:col-span-4">
                            <x-input-label for="video" :value="__('Community Video')" />
                            <x-text-input id="video" name="video" type="file" accept="video/*"
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('video')" class="mt-2" />
                        </div>
                        <div class="md:col-span-4">
                            <x-input-label for="image" :value="__('Community Image')" />
                            <x-text-input id="image" name="image" type="file" accept="image/*"
                                class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>
                        <div class="md:col-span-12">
                            <x-input-label for="address" :value="__('Location')" />
                            <x-text-input id="address" name="address" type="text"
                                class="mt-1 block w-full  map-input" :value="old('address', '')" autocomplete="address" />
                            <x-text-input type="hidden" name="latitude" id="latitude" value="0" />
                            <x-text-input type="hidden" name="longitude" id="longitude" value="0" />
                            <x-input-error class="mt-2" :messages="$errors->get('address')" />
                        </div>
                        <div class="md:col-span-12">
                            <div id="location-map-container" class="w-full h-52">
                                <div class="w-full h-full" id="location-map"></div>
                            </div>
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

                    </div>
                    <!-- Submit -->
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
        @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ts = new TomSelect('#amenities', {
                create: false, // allow new tags
                persist: false,
                plugins: ['remove_button'],
                onItemAdd: function() {
                    this.setTextboxValue('');
                    this.refreshOptions();
                },
            });
        });

    </script>
    <script>
        function initialize() {
            const locationInputs = document.getElementsByClassName("map-input");
            const autocompletes = [];
            const geocoder = new google.maps.Geocoder;
            for (let i = 0; i < locationInputs.length; i++) {
                const input = locationInputs[i];
                const fieldKey = input.id.replace("-input", "");
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
                    input: input,
                    map: map,
                    marker: marker,
                    autocomplete: autocomplete
                });
            }

            for (let i = 0; i < autocompletes.length; i++) {
                const input = autocompletes[i].input;
                const autocomplete = autocompletes[i].autocomplete;
                const map = autocompletes[i].map;
                const marker = autocompletes[i].marker;
                google.maps.event.addListener(autocomplete, 'place_changed', function() {
                    marker.map = null;
                    const place = autocomplete.getPlace();
                    geocoder.geocode({
                        'placeId': place.place_id
                    }, function(results, status) {
                        if (status === google.maps.GeocoderStatus.OK) {
                            const lat = results[0].geometry.location.lat();
                            const lng = results[0].geometry.location.lng();
                            setLocationCoordinates(lat, lng);
                        }
                    });

                    if (!place.geometry) {
                        window.alert("No details available for input: '" + place.name + "'");
                        input.value = "";
                        return;
                    }
                    if (place.geometry.viewport) {
                        map.fitBounds(place.geometry.viewport);
                    } else {
                        map.setCenter(place.geometry.location);
                        map.setZoom(17);
                    }
                    marker.position = place.geometry.location;
                    marker.map = map;
                });
            }
        }

        function setLocationCoordinates(lat, lng) {
            const latitudeField = document.getElementById("latitude");
            const longitudeField = document.getElementById("longitude");
            latitudeField.value = lat;
            longitudeField.value = lng;
        }
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key= {{ config('app.mapapi') }}&libraries=places,marker&callback=initialize&loading=async"
        async defer></script>
</x-app-layout>
