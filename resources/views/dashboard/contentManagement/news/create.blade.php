<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('news.index')" :active="true">AYS News</x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">
                    Add News Article
                </h2>

                <form method="POST" action="{{ route('news.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="grid md:grid-cols-12 gap-5">

                        <div class="md:col-span-12">
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="md:col-span-12">
                            <x-input-label for="short_description" :value="__('Short Description (shown on news list)')" />
                            <x-text-textarea id="short_description" name="short_description" class="mt-1 block w-full">{{ old('short_description') }}</x-text-textarea>
                            <x-input-error :messages="$errors->get('short_description')" class="mt-2" />
                        </div>

                        <div class="md:col-span-12">
                            <x-input-label for="description" :value="__('Full Article Body')" />
                            <div class="ql-wrapper mt-1">
                                <div class="richBoxHeight editor" data-target="description"></div>
                            </div>
                            <input type="hidden" name="description" id="description" value="{{ old('description') }}">
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label for="author" :value="__('Author')" />
                            <x-text-input id="author" name="author" type="text" class="mt-1 block w-full"
                                :value="old('author')" />
                            <x-input-error :messages="$errors->get('author')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label for="published_at" :value="__('Published Date')" />
                            <x-text-input id="published_at" name="published_at" type="date" class="mt-1 block w-full"
                                :value="old('published_at', date('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label for="is_featured" :value="__('Featured (shown large at top)')" />
                            <x-select name="is_featured" :options="['1' => 'Yes', '0' => 'No']"
                                :value="old('is_featured', '0')" />
                            <x-input-error :messages="$errors->get('is_featured')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label for="show_on_ays_screen" :value="__('Show on AYS Screen')" />
                            <x-select name="show_on_ays_screen" :options="['1' => 'Yes', '0' => 'No']"
                                :value="old('show_on_ays_screen', '0')" />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">The most recent article marked Yes is what shows on the app's AYS home screen.</p>
                            <x-input-error :messages="$errors->get('show_on_ays_screen')" class="mt-2" />
                        </div>

                        <div class="md:col-span-6">
                            <x-input-label for="is_active" :value="__('Status')" />
                            <x-select name="is_active" :options="['1' => 'Active (visible in app)', '0' => 'Inactive (hidden)']"
                                :value="old('is_active', '1')" />
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>

                        <div class="md:col-span-6">
                            <x-input-label for="image" :value="__('Thumbnail Image')" />
                            <x-text-input id="image" name="image" type="file" accept="image/*"
                                class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>Publish Article</x-primary-button>
                        <a href="{{ route('news.index') }}" class="text-sm text-gray-500 hover:underline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
