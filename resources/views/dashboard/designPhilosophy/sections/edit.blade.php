<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('design-philosophy.edit')">Design Philosophy</x-nav-link>
        <span class="mx-2 text-gray-400">/</span>
        <x-nav-link :href="route('design-philosophy.sections.edit', $section)" :active="true">
            Edit Section
        </x-nav-link>
    </x-slot>

    <div class="py-6 space-y-6">
        <div class="sm:px-6 lg:px-8">

            {{-- ── Flash message ───────────────────────────────────────────── --}}
            @if (session('status') === 'success')
                <div x-data="{ show: true }" x-show="show" x-transition
                     x-init="setTimeout(() => show = false, 4000)"
                     class="mb-4 p-3 rounded bg-green-50 border border-green-200 text-green-700 text-sm">
                    {{ session('message') }}
                </div>
            @endif

            {{-- ── Current images (each image has its OWN standalone form — NOT nested) ── --}}
            @if($section->hasMedia('images'))
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                    </h3>
                    <div class="flex flex-wrap gap-4">
                        @foreach($section->getMedia('images') as $media)
                            <div class="relative w-36 h-28">
                                <img src="{{ $media->getUrl() }}"
                                     class="w-full h-full object-cover rounded border border-gray-200" />

                                <form method="POST"
                                      action="{{ route('design-philosophy.sections.media.destroy', [$section, $media->id]) }}"
                                      class="absolute inset-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Remove this image? This cannot be undone.')"
                                            class="absolute top-1 right-1 flex items-center justify-center
                                                   bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 text-xs
                                                   shadow cursor-pointer">
                                        ✕
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ── Main edit form (no nested forms inside) ────────────────────── --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">
                    Edit Section: <span class="text-gray-500">{{ $section->title }}</span>
                </h2>

                <form method="POST"
                      action="{{ route('design-philosophy.sections.update', $section) }}"
                      enctype="multipart/form-data"
                      class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid md:grid-cols-12 gap-5">

                        <div class="md:col-span-8">
                            <x-input-label for="title" :value="__('Section Title')" />
                            <x-text-input id="title" name="title" type="text"
                                class="mt-1 block w-full"
                                :value="old('title', $section->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="md:col-span-3">
                            <x-input-label for="sort_order" :value="__('Sort Order')" />
                            <x-text-input id="sort_order" name="sort_order" type="number" min="0"
                                class="mt-1 block w-full"
                                :value="old('sort_order', $section->sort_order)" />
                            <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                        </div>

                        <div class="md:col-span-1 flex items-end pb-1">
                            <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <input type="checkbox" name="is_active" value="1"
                                       class="rounded"
                                       {{ old('is_active', $section->is_active) ? 'checked' : '' }}>
                                Active
                            </label>
                        </div>

                        <div class="md:col-span-12">
                            <x-input-label for="description" :value="__('Description')" />
                            <x-text-textarea id="description" name="description"
                                class="mt-1 block w-full" rows="5">{{ old('description', $section->description) }}</x-text-textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="md:col-span-12">
                            <x-input-label for="images" :value="__('Add More Images')" />
                            <x-text-input id="images" name="images[]" type="file"
                                accept="image/*" multiple class="mt-1 block w-full" />
                            <p class="mt-1 text-xs text-gray-500">
                                1 image → full-width. 2 images → side by side. Added on top of existing images.
                            </p>
                            <x-input-error :messages="$errors->get('images.*')" class="mt-2" />
                        </div>

                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Save Changes') }}</x-primary-button>
                        <a href="{{ route('design-philosophy.edit') }}"
                           class="text-sm text-gray-600 hover:underline">Back to Design Philosophy</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
