<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('design-philosophy.edit')">Design Philosophy</x-nav-link>
        <span class="mx-2 text-gray-400">/</span>
        <x-nav-link :href="route('design-philosophy.sections.create')" :active="true">Add Section</x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">
                    Add Content Section
                </h2>

                <form method="POST" action="{{ route('design-philosophy.sections.store') }}"
                      enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid md:grid-cols-12 gap-5">

                        <div class="md:col-span-8">
                            <x-input-label for="title" :value="__('Section Title')" />
                            <x-text-input id="title" name="title" type="text"
                                class="mt-1 block w-full" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label for="sort_order" :value="__('Sort Order')" />
                            <x-text-input id="sort_order" name="sort_order" type="number" min="0"
                                class="mt-1 block w-full" :value="old('sort_order', 0)" />
                            <p class="mt-1 text-xs text-gray-500">Lower = appears first</p>
                            <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                        </div>

                        <div class="md:col-span-12">
                            <x-input-label for="description" :value="__('Description')" />
                            <x-text-textarea id="description" name="description"
                                class="mt-1 block w-full" rows="5">{{ old('description') }}</x-text-textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="md:col-span-12">
                            <x-input-label for="images" :value="__('Images (select one or more)')" />
                            <x-text-input id="images" name="images[]" type="file"
                                accept="image/*" multiple class="mt-1 block w-full" />
                            <p class="mt-1 text-xs text-gray-500">
                                1 image → displayed full-width. 2 images → side by side. More → shown as a row.
                            </p>
                            <x-input-error :messages="$errors->get('images.*')" class="mt-2" />
                        </div>

                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Add Section') }}</x-primary-button>
                        <a href="{{ route('design-philosophy.edit') }}"
                           class="text-sm text-gray-600 hover:underline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
