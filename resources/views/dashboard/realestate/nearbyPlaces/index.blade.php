<x-app-layout>
    <div x-data="nearByModal({
        community_id: @js(old('community_id')),
        name: @js(old('name')),
        type: @js(old('type')),
        distance_km: @js(old('distance_km')),
        {{-- optional if you store previous image --}}
    })" x-init="@if ($errors->any()) show = true @endif">
        <x-slot name="header">
            <x-nav-link :href="route('communities.index')" :active="true">
                {{ __('Nearby Place Data - ') }} {{$community->name}}
            </x-nav-link>
        </x-slot>

        <div class="py-6">
            <div class="sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                    <!-- Header -->
                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div class="my-auto">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Nearby Place Data for  {{$community->name}}
                            </h2>
                        </div>

                        @can('create_nearby_places')
                            <div class="md:text-end">
                                <x-button-link href="#" @click.prevent="openCreate()">
                                    Create Nearby Data
                                </x-button-link>
                            </div>
                        @endcan
                    </div>

                    <!-- Table -->
                    @can('view_nearby_places')
                        @php
                            $actions = [];

                            if (auth()->user()->can('edit_nearby_places')) {
                                $actions[] = [
                                    'type' => 'edit',
                                    'label' => 'Edit',
                                    'click' => 'true',
                                ];
                            }

                            if (auth()->user()->can('delete_nearby_places')) {
                                $actions[] = [
                                    'type' => 'delete',
                                    'url' => 'communities.nearbyPlaces.destroy',
                                    'params' => [$community->id], // nearbyPlace id will be added in blade
                                    'label' => 'Delete',
                                ];
                            }

                            $columns = collect([
                                ['label' => '#'],
                                ['label' => 'Name', 'key' => 'name'],
                                ['label' => 'Type', 'key' => 'type'],
                                ['label' => 'Distance', 'key' => 'distance_km'],

                                count($actions) ? ['label' => 'Actions', 'actions' => $actions] : null,
                            ])
                                ->filter()
                                ->values()
                                ->toArray();
                        @endphp

                        <x-datatable :data="$nearbyPlaces" :columns="$columns" />
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
                    <span x-text="isEdit ? 'Edit Nearby Place Data' : 'Create Nearby Place Data'"></span>
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
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                            x-model="form.name" required autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="type" :value="__('Type')" />
                        <select id="type" name="type" x-model="form.type"
                            class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm">
                            <option value="">Select Type</option>
                            <option value="school">school</option>
                            <option value="mall">mall</option>
                            <option value="hospital">hospital</option>
                            <option value="metro">metro</option>
                            <option value="park">park</option>
                            <option value="restaurant">restaurant</option>
                            <option value="other">other</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="distance_km" :value="__('Distance')" />
                        <x-text-input id="distance_km" name="distance_km" type="text" class="mt-1 block w-full"
                            x-model="form.distance_km" required autocomplete="distance_km" />
                        <x-input-error :messages="$errors->get('distance_km')" class="mt-2" />
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
            function nearByModal() {
                return {
                    show: false,
                    isEdit: false,

                    communityId: {{ $community->id }},

                    createUrl: "{{ route('communities.nearbyPlaces.store', $community) }}",
                    updateUrl: "",

                    form: {
                        name: '',
                        type: '',
                        distance_km: '',
                    },

                    openCreate() {
                        this.isEdit = false;
                        this.reset();
                        this.show = true;
                    },

                    openEdit(nearbyPlaceId) {
                        this.isEdit = true;
                        this.show = true;

                        // Correct update URL
                        this.updateUrl =
                            `/dashboard/communities/${this.communityId}/nearbyPlaces/${nearbyPlaceId}`;

                        // Correct edit fetch URL
                        fetch(
                                `/dashboard/communities/${this.communityId}/nearbyPlaces/${nearbyPlaceId}/edit`, {
                                    headers: {
                                        'Accept': 'application/json'
                                    }
                                }
                            )
                            .then(res => {
                                if (!res.ok) throw new Error('Failed to fetch data');
                                return res.json();
                            })
                            .then(place => {
                                this.form = {
                                    name: place.name,
                                    type: place.type,
                                    distance_km: place.distance_km,
                                };
                            })
                            .catch(err => {
                                console.error(err);
                                this.close();
                            });
                    },

                    close() {
                        this.show = false;
                    },

                    reset() {
                        this.form = {
                            name: '',
                            type: '',
                            distance_km: '',
                        };
                    }
                }
            }
        </script>

    </div>
</x-app-layout>
