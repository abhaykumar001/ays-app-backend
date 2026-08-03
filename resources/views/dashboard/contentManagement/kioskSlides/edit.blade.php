<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('kioskSlides.index')">AYS Kiosk Slides</x-nav-link>
        <span class="mx-2 text-gray-400">/</span>
        <x-nav-link :href="route('kioskSlides.edit', $kioskSlide)" :active="true">Edit Slide</x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Edit Kiosk Slide</h2>

                @if (session('status') === 'success')
                    <div class="mb-4 p-3 rounded bg-green-50 border border-green-200 text-green-700 text-sm">
                        {{ session('message') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('kioskSlides.update', $kioskSlide) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf @method('PUT')
                    <div class="grid md:grid-cols-12 gap-5">

                        <div class="md:col-span-8">
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                :value="old('title', $kioskSlide->title)" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label for="display_order" :value="__('Display Order')" />
                            <x-text-input id="display_order" name="display_order" type="number" min="0"
                                class="mt-1 block w-full" :value="old('display_order', $kioskSlide->display_order)" />
                            <x-input-error :messages="$errors->get('display_order')" class="mt-2" />
                        </div>

                        <div class="md:col-span-6">
                            <x-input-label for="is_active" :value="__('Status')" />
                            <x-select name="is_active" :options="['1' => 'Active (visible in app)', '0' => 'Inactive (hidden)']"
                                :value="old('is_active', $kioskSlide->is_active ? '1' : '0')" />
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>

                        <div class="md:col-span-6">
                            <x-input-label for="image" :value="__('Slide Image (leave blank to keep current)')" />
                            @if ($kioskSlide->getFirstMediaUrl('images'))
                                <img src="{{ $kioskSlide->getFirstMediaUrl('images', 'resize') ?: $kioskSlide->getFirstMediaUrl('images') }}"
                                     class="mt-2 mb-3 w-40 h-28 object-cover rounded border" alt="Current image">
                            @endif
                            <x-text-input id="image" name="image" type="file" accept="image/*"
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>Update Slide</x-primary-button>
                        <a href="{{ route('kioskSlides.index') }}" class="text-sm text-gray-500 hover:underline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
