<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('seoData.index')" :active="true">
            {{ __('Seo Meta Data') }}
        </x-nav-link>
    </x-slot>
    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div class="my-auto">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Create Meta Data for Website Page') }}
                        </h2>
                    </div>
                </div>


                <div class="">

                    <form method="post" action="{{ route('seoData.store') }}" class="mt-6 space-y-8"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="grid md:grid-cols-12 gap-5">

                            <!-- Page Name -->
                            <div class="md:col-span-6">
                                <x-input-label for="page_name" :value="__('Select Page')" />
                                <x-select name="page_name" :options="$frontendRoutes" :value="old('page_name', '')" />
                                <x-input-error :messages="$errors->get('page_name')" class="mt-2" />
                            </div>

                            <!-- Meta Title -->
                            <div class="md:col-span-6">
                                <x-input-label for="meta_title" :value="__('Meta Title')" />
                                <x-text-input id="meta_title" name="meta_title" type="text" class="mt-1 block w-full"
                                    :value="old('meta_title', '')" autocomplete="meta_title" />
                                <x-input-error :messages="$errors->get('meta_title')" class="mt-2" />
                            </div>

                            <!-- Meta Description -->
                            <div class="md:col-span-12">
                                <x-input-label for="meta_description" :value="__('Meta Description')" />
                                <x-text-textarea id="meta_description" name="meta_description" class="mt-1 block w-full"
                                    autocomplete="meta_description">
                                    {{ old('meta_description', '') }}
                                </x-text-textarea>
                                <x-input-error :messages="$errors->get('meta_description')" class="mt-2" />
                            </div>

                            <!-- Meta Keywords -->
                            <div class="md:col-span-12">
                                <x-input-label for="meta_keywords" :value="__('Meta Keywords')" />
                                <x-text-input id="meta_keywords" name="meta_keywords" type="text"
                                    class="mt-1 block w-full" :value="old('meta_keywords', '')" autocomplete="meta_keywords" />
                                <x-input-error :messages="$errors->get('meta_keywords')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div class="md:col-span-6">
                                <x-input-label for="status" :value="__('Status')" />
                                <x-select name="status" :options="['active' => 'Active', 'inactive' => 'Inactive']" :value="old('status', '')" />
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>


                            <!-- Image Upload -->
                            <div class="md:col-span-6">
                                <x-input-label for="image" :value="__('Image')" />
                                <x-text-input id="image" name="image" type="file" class="mt-1 block w-full"
                                    accept="image/*" />
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </div>

                        </div>

                        <!-- Submit Button -->
                        <div class="flex flex-col items-center justify-center gap-4">
                            <x-primary-button>{{ __('Submit') }}</x-primary-button>

                            @if (session('status') === 'success')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-green-600 dark:text-green-600">
                                    {{ __('Seo Data Added Successfully.') }}
                                </p>
                            @endif
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
