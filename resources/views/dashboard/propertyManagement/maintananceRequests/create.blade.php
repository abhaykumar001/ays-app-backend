<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('maintananceRequests.index')" :active="true">
            {{ __('Maintenance Requests') }}
        </x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div class="my-auto">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Create New Request') }}
                        </h2>
                    </div>
                </div>
                <form method="POST" action="{{ route('maintananceRequests.store') }}" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf
                    <!-- Service -->
                    <div>
                        <x-input-label value="Service" />
                        <select name="service_id"
                            class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm">
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Unit -->
                    <div>
                        <x-input-label value="Unit (Optional)" />
                        <select name="unit_id"
                            class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm">
                            <option value="">—</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Unit -->
                    <div>
                        <x-input-label value="Owner" />
                        <select name="owner_id"
                            class="mt-1 border-r-8 border-gray-300 dark:border-gray-700 text-sm  w-full  dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm">
                            <option value="">—</option>
                            @foreach ($owners as $owner)
                                <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <x-input-label value="Problem Description" />
                        <x-text-textarea name="description" class="mt-1 block w-full" />
                    </div>

                    <!-- Images -->
                    <div>
                        <x-input-label value="Upload Images" />
                        <x-text-input type="file" name="images[]" multiple accept="image/*" id="image"
                            class="mt-1 block w-full" />
                    </div>

                    <div class="flex justify-end gap-2">
                        <x-secondary-button type="button" onclick="history.back()">Cancel</x-secondary-button>
                        <x-primary-button>Submit</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
