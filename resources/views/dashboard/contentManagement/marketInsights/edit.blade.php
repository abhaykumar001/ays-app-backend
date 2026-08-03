<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('marketInsights.index')" :active="true">
            {{ __('Market Insights') }}
        </x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class=" gap-4 mb-4">
                    <div class="my-auto">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Edit Market Insight ') }} - {{$marketInsight->title}}
                        </h2>
                    </div>
                </div>

                <form method="POST" action="{{ route('marketInsights.update', $marketInsight->id) }}" class="mt-6 space-y-8"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid md:grid-cols-12 gap-5">
                        <div class="md:col-span-12">
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" name="title" type="text"
                                class="mt-1 block w-full"
                                :value="old('title',  $marketInsight->title ?? '')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                        <!-- Status -->
                        <div class="md:col-span-12">
                            <x-input-label for="short_description" :value="__('Market Insight Short Description')" />
                            <x-text-textarea id="short_description" name="short_description" class="mt-1 block w-full"
                                autofocus required autocomplete="short_description">
                                {{ old('short_description',  $marketInsight->short_description ?? '') }}
                            </x-text-textarea>
                            <x-input-error :messages="$errors->get('short_description')" class="mt-2" />
                        </div>
                        <div class="md:col-span-12">
                            <x-input-label for="description" :value="__('Description')" />
                            <div class="ql-wrapper mt-1">
                                <div class="richBoxHeight editor" data-target="description">{!! old('description', $marketInsight->description) !!}</div>
                            </div>
                            <input type="hidden" required name="description" id="description">
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        <div class="md:col-span-12">
                            <x-input-label for="meta_title" :value="__('Meta Title')" />
                            <x-text-input id="meta_title" name="meta_title" type="text" class="mt-1 block w-full"
                                :value="old('meta_title',  $marketInsight->meta_title ?? '')" autofocus  autocomplete="meta_title" />
                            <x-input-error :messages="$errors->get('meta_title')" class="mt-2" />
                        </div>
                        <div class="md:col-span-12">
                            <x-input-label for="meta_keywords" :value="__('Meta Keywords')" />
                            <x-text-input id="meta_keywords" name="meta_keywords" type="text" class="mt-1 block w-full"
                                :value="old('meta_keywords',  $marketInsight->meta_keywords ?? '')" autofocus  autocomplete="meta_keywords" />
                            <x-input-error :messages="$errors->get('meta_keywords')" class="mt-2" />
                        </div>
                        <div class="md:col-span-12">
                            <x-input-label for="meta_description" :value="__('Meta Description')" />
                            <x-text-textarea id="meta_description" name="meta_description" class="mt-1 block w-full"
                                autofocus  autocomplete="meta_description">
                                {{ old('meta_description',  $marketInsight->meta_description ?? '') }}
                            </x-text-textarea>
                            <x-input-error :messages="$errors->get('meta_description')" class="mt-2" />
                        </div>
                        <div class="md:col-span-6">
                            <x-input-label for="is_active" :value="__('Status')" />
                            <x-select name="is_active" :options="['1' => 'Active', '0' => 'Inactive']" :value="old('is_active', $marketInsight->is_active ? '1' : '0')" />
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>
                        <div class="md:col-span-6">
                            <x-input-label for="image" :value="__('Market Insight Image')" />
                            <x-text-input id="image" name="image" type="file" accept="image/*"
                                class="mt-1 block w-full"  />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                                @if (isset($marketInsight->image) && $marketInsight->image != '')
                                <div class="mt-2">
                                    <img src="{{ asset($marketInsight->getFirstMediaUrl('images', 'webp')) }}"
                                        alt="Current Venture Image" class="h-16 w-auto">
                                </div>
                            @endif
                        </div>
                        <!-- Published on Forbes -->
                        <div class="md:col-span-4">
                            <x-input-label for="published_at" :value="__('Published At')" />
                            <x-text-input id="published_at" name="published_at" type="date"
                                class="mt-1 block w-full"
                                :value="old('published_at', $marketInsight->published_at ?? date('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
                        </div>

                        <!-- Is Featured -->
                        <div class="md:col-span-4">
                            <x-input-label for="is_featured" :value="__('Is Featured')" />
                            <x-select name="is_featured" :options="['1' => 'Yes', '0' => 'No']"
                                :value="old('is_featured',  $marketInsight->is_featured ?? '0')" />
                            <x-input-error :messages="$errors->get('is_featured')" class="mt-2" />
                        </div>
                     
                    </div>
                    <!-- Submit -->
                    <div class="flex flex-col items-center justify-center gap-4">
                        <x-primary-button>{{ __('Update') }}</x-primary-button>

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

</x-app-layout>
