<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('blogs.index')" :active="true">
            {{ __('Blogs') }}
        </x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class=" gap-4 mb-4">
                    <div class="my-auto">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Edit Blog ') }} - {{$blog->title}}
                        </h2>
                    </div>
                </div>

                <form method="POST" action="{{ route('blogs.update', $blog->id) }}" class="mt-6 space-y-8"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid md:grid-cols-12 gap-5">
                        <div class="md:col-span-12">
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" name="title" type="text"
                                class="mt-1 block w-full"
                                :value="old('title',  $blog->title ?? '')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                        <!-- Status -->
                        <div class="md:col-span-12">
                            <x-input-label for="short_description" :value="__('Blog Short Description')" />
                            <x-text-textarea id="short_description" name="short_description" class="mt-1 block w-full"
                                autofocus required autocomplete="short_description">
                                {{ old('short_description',  $blog->short_description ?? '') }}
                            </x-text-textarea>
                            <x-input-error :messages="$errors->get('short_description')" class="mt-2" />
                        </div>
                        <div class="md:col-span-12">
                            <x-input-label for="description" :value="__('Description')" />
                            <div class="ql-wrapper mt-1">
                                <div class="richBoxHeight editor" data-target="description">{!! old('description', $blog->description) !!}</div>
                            </div>
                            <input type="hidden" required name="description" id="description">
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        <div class="md:col-span-12">
                            <x-input-label for="meta_title" :value="__('Meta Title')" />
                            <x-text-input id="meta_title" name="meta_title" type="text" class="mt-1 block w-full"
                                :value="old('meta_title',  $blog->meta_title ?? '')" autofocus  autocomplete="meta_title" />
                            <x-input-error :messages="$errors->get('meta_title')" class="mt-2" />
                        </div>
                        <div class="md:col-span-12">
                            <x-input-label for="meta_keywords" :value="__('Meta Keywords')" />
                            <x-text-input id="meta_keywords" name="meta_keywords" type="text" class="mt-1 block w-full"
                                :value="old('meta_keywords',  $blog->meta_keywords ?? '')" autofocus  autocomplete="meta_keywords" />
                            <x-input-error :messages="$errors->get('meta_keywords')" class="mt-2" />
                        </div>
                        <div class="md:col-span-12">
                            <x-input-label for="meta_description" :value="__('Meta Description')" />
                            <x-text-textarea id="meta_description" name="meta_description" class="mt-1 block w-full"
                                autofocus  autocomplete="meta_description">
                                {{ old('meta_description',  $blog->meta_description ?? '') }}
                            </x-text-textarea>
                            <x-input-error :messages="$errors->get('meta_description')" class="mt-2" />
                        </div>
                        <div class="md:col-span-6">
                            <x-input-label for="is_active" :value="__('Status')" />
                            <x-select name="is_active" :options="['1' => 'Active', '0' => 'Inactive']" :value="old('is_active', $blog->is_active ? '1' : '0')" />
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>
                        <div class="md:col-span-6">
                            <x-input-label for="image" :value="__('Blog Image')" />
                            <x-text-input id="image" name="image" type="file" accept="image/*"
                                class="mt-1 block w-full"  />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                                @if (isset($blog->image) && $blog->image != '')
                                <div class="mt-2">
                                    <img src="{{ asset($blog->getFirstMediaUrl('images', 'webp')) }}"
                                        alt="Current Venture Image" class="h-16 w-auto">
                                </div>
                            @endif
                        </div>
                        <!-- Published on Forbes -->
                        <div class="md:col-span-4">
                            <x-input-label for="published_at" :value="__('Published At')" />
                            <x-text-input id="published_at" name="published_at" type="date"
                                class="mt-1 block w-full"
                                :value="old('published_at', $blog->published_at ?? date('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
                        </div>

                        <!-- Is Featured -->
                        <div class="md:col-span-4">
                            <x-input-label for="is_featured" :value="__('Is Featured')" />
                            <x-select name="is_featured" :options="['1' => 'Yes', '0' => 'No']"
                                :value="old('is_featured',  $blog->is_featured ?? '0')" />
                            <x-input-error :messages="$errors->get('is_featured')" class="mt-2" />
                        </div>
                        <!-- Author -->
                        <div class="md:col-span-4">
                            <x-input-label for="author" :value="__('Author')" />
                            <x-text-input id="author" name="author" type="text" class="mt-1 block w-full"
                                :value="old('author', $blog->author ?? '')" autocomplete="author" />
                            <x-input-error :messages="$errors->get('author')" class="mt-2" />
                        </div>
                        <!-- Tags -->
                        <div class="md:col-span-12">
                            <x-input-label for="tags" :value="__('Tags')" />
                            <select id="tags" name="tags[]" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm" multiple placeholder="Select or type to add tags">
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}"
                                        @if (in_array($tag->id, old('tags', $blog->tags->pluck('id')->toArray()))) selected @endif>
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('tags')" class="mt-2" />
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
       <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ts = new TomSelect('#tags', {
                create: true,
                persist: false,
                plugins: ['remove_button'],
                onItemAdd: function(value) {
                    // Send only new (non-numeric) tag names to the server
                    if (!isNaN(value)) return;

                    fetch('{{ route('tag.addNewTag') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ name: value })
                        })
                        .then(res => res.json())
                        .then(data => {
                            const newTag = data.newTag;
                            const allTags = data.allTags;

                            // Update new tag id
                            const option = document.querySelector(`#tags option[value='${value}']`);
                            if (option) option.value = newTag.id;

                            // Preserve selected values
                            const selectedValues = Array.from(ts.getValue());

                            // Clear options and re-add
                            ts.clearOptions();
                            allTags.forEach(tag => {
                                ts.addOption({ value: tag.id, text: tag.name });
                            });

                            // Restore selected values
                            ts.setValue(selectedValues);

                            // ✅ Clear the input text
                            ts.setTextboxValue('');
                        })
                        .catch(err => console.error(err));
                }
            });
        });
    </script>
</x-app-layout>
