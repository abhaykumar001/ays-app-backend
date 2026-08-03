<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('design-philosophy.edit')" :active="true">
            Design Philosophy
        </x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">

            {{-- ── Main Settings ──────────────────────────────────────────── --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">
                    Hero &amp; Quote Settings
                </h2>

                <form method="POST" action="{{ route('design-philosophy.update') }}"
                      enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid md:grid-cols-2 gap-5">

                        {{-- Hero Title --}}
                        <div>
                            <x-input-label for="hero_title" :value="__('Hero Title')" />
                            <x-text-input id="hero_title" name="hero_title" type="text"
                                class="mt-1 block w-full"
                                :value="old('hero_title', $philosophy->hero_title)" required />
                            <p class="mt-1 text-xs text-gray-500">First line on the hero (e.g. "Our Design")</p>
                            <x-input-error :messages="$errors->get('hero_title')" class="mt-2" />
                        </div>

                        {{-- Hero Title Accent --}}
                        <div>
                            <x-input-label for="hero_title_accent" :value="__('Hero Title Accent (coloured line)')" />
                            <x-text-input id="hero_title_accent" name="hero_title_accent" type="text"
                                class="mt-1 block w-full"
                                :value="old('hero_title_accent', $philosophy->hero_title_accent)" required />
                            <p class="mt-1 text-xs text-gray-500">Second line in brand colour (e.g. "Philosophy")</p>
                            <x-input-error :messages="$errors->get('hero_title_accent')" class="mt-2" />
                        </div>

                        {{-- Hero Subtitle --}}
                        <div class="md:col-span-2">
                            <x-input-label for="hero_subtitle" :value="__('Hero Subtitle')" />
                            <x-text-input id="hero_subtitle" name="hero_subtitle" type="text"
                                class="mt-1 block w-full"
                                :value="old('hero_subtitle', $philosophy->hero_subtitle)" />
                            <x-input-error :messages="$errors->get('hero_subtitle')" class="mt-2" />
                        </div>

                        {{-- Quote --}}
                        <div class="md:col-span-2">
                            <x-input-label for="quote" :value="__('Quote Text')" />
                            <x-text-textarea id="quote" name="quote" class="mt-1 block w-full" rows="3">{{ old('quote', $philosophy->quote) }}</x-text-textarea>
                            <p class="mt-1 text-xs text-gray-500">Displayed in the large quote block beneath the hero.</p>
                            <x-input-error :messages="$errors->get('quote')" class="mt-2" />
                        </div>

                        {{-- Hero Image --}}
                        <div class="md:col-span-2">
                            <x-input-label for="hero_image" :value="__('Hero Background Image')" />
                            @if($philosophy->hasMedia('hero'))
                                <div class="mb-2">
                                    <img src="{{ $philosophy->getFirstMediaUrl('hero') }}"
                                         class="h-32 w-full object-cover rounded border" />
                                </div>
                            @endif
                            <x-text-input id="hero_image" name="hero_image" type="file"
                                accept="image/*" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('hero_image')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Save Settings') }}</x-primary-button>
                        @if (session('status') === 'success')
                            <p x-data="{ show: true }" x-show="show" x-transition
                               x-init="setTimeout(() => show = false, 3000)"
                               class="text-sm text-green-600">{{ session('message') }}</p>
                        @endif
                    </div>
                </form>
            </div>

            {{-- ── Sections ────────────────────────────────────────────────── --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Content Sections
                    </h2>
                    <x-button-link href="{{ route('design-philosophy.sections.create') }}">
                        + Add Section
                    </x-button-link>
                </div>

                @if($philosophy->allSections->isEmpty())
                    <p class="text-gray-500 text-sm">No sections yet. Click "Add Section" to get started.</p>
                @else
                    <div class="space-y-4">
                        @foreach($philosophy->allSections as $section)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 flex gap-4">

                                {{-- Section images preview --}}
                                <div class="flex gap-2 flex-shrink-0">
                                    @forelse($section->getMedia('images') as $media)
                                        <img src="{{ $media->getUrl() }}"
                                             class="h-16 w-20 object-cover rounded border" />
                                    @empty
                                        <div class="h-16 w-20 bg-gray-100 rounded border flex items-center justify-center text-gray-400 text-xs">
                                            No image
                                        </div>
                                    @endforelse
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs text-gray-400">Order: {{ $section->sort_order }}</span>
                                        @if(!$section->is_active)
                                            <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded">Hidden</span>
                                        @endif
                                    </div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100 truncate">
                                        {{ $section->title }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate mt-0.5">
                                        {{ Str::limit($section->description, 80) }}
                                    </p>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-3 flex-shrink-0">
                                    <a href="{{ route('design-philosophy.sections.edit', $section) }}"
                                       class="text-sm text-indigo-600 hover:underline">Edit</a>
                                    <form method="POST"
                                          action="{{ route('design-philosophy.sections.destroy', $section) }}"
                                          onsubmit="return confirm('Delete this section?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-sm text-red-500 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
