<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('events.index')" :active="true">
            {{ __('Events') }}
        </x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div class="my-auto">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Create New Event') }}
                        </h2>
                    </div>
                </div>

                <form method="POST" action="{{ route('events.store') }}" class="mt-6 space-y-8"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="grid md:grid-cols-12 gap-5">
                        <div class="md:col-span-12">
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                :value="old('title', '')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="md:col-span-6">
                            <x-input-label for="type" :value="__('Event Type')" />
                            <x-select name="type" :options="[
                                'launch' => 'Launch',
                                'open_house' => 'Open House',
                                'site_visit' => 'Site Visit',
                                'broker_meet' => 'Broker Meet',
                                'webinar' => 'Webinar',
                                'handover' => 'Handover',
                                'other' => 'Other',
                            ]" :value="old('type', 'other')" required />
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div class="md:col-span-6">
                            <x-input-label for="event_date" :value="__('Event Date')" />
                            <x-text-input id="event_date" name="event_date" type="date" class="mt-1 block w-full"
                                :value="old('event_date', '')" required />
                            <x-input-error :messages="$errors->get('event_date')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label for="start_time" :value="__('Start Time')" />
                            <x-text-input id="start_time" name="start_time" type="time" class="mt-1 block w-full"
                                :value="old('start_time', '')" />
                            <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label for="end_time" :value="__('End Time')" />
                            <x-text-input id="end_time" name="end_time" type="time" class="mt-1 block w-full"
                                :value="old('end_time', '')" />
                            <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label for="venue" :value="__('Venue')" />
                            <x-text-input id="venue" name="venue" type="text" class="mt-1 block w-full"
                                :value="old('venue', '')" />
                            <x-input-error :messages="$errors->get('venue')" class="mt-2" />
                        </div>

                        <div class="md:col-span-12">
                            <x-input-label for="description" :value="__('Description')" />
                            <div class="richBoxHeight editor mt-1" data-target="description">{!! old('description', '') !!}</div>
                            <textarea name="description" id="description" style="display:none"></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label for="is_virtual" :value="__('Virtual Event')" />
                            <x-select name="is_virtual" :options="['1' => 'Yes', '0' => 'No']"
                                :value="old('is_virtual', '0')" />
                            <x-input-error :messages="$errors->get('is_virtual')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label for="requires_registration" :value="__('Requires Registration')" />
                            <x-select name="requires_registration" :options="['1' => 'Yes', '0' => 'No']"
                                :value="old('requires_registration', '1')" />
                            <x-input-error :messages="$errors->get('requires_registration')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label for="capacity" :value="__('Capacity')" />
                            <x-text-input id="capacity" name="capacity" type="number" min="1" class="mt-1 block w-full"
                                :value="old('capacity', '')" />
                            <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                        </div>

                        <div class="md:col-span-6">
                            <x-input-label for="registration_deadline" :value="__('Registration Deadline')" />
                            <x-text-input id="registration_deadline" name="registration_deadline" type="datetime-local"
                                class="mt-1 block w-full" :value="old('registration_deadline', '')" />
                            <x-input-error :messages="$errors->get('registration_deadline')" class="mt-2" />
                        </div>

                        <div class="md:col-span-6">
                            <x-input-label for="status" :value="__('Status')" />
                            <x-select name="status" :options="[
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'cancelled' => 'Cancelled',
                                'completed' => 'Completed',
                            ]" :value="old('status', 'draft')" required />
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label for="is_featured" :value="__('Featured (Life at AYS)')" />
                            <x-select name="is_featured" :options="['1' => 'Yes', '0' => 'No']"
                                :value="old('is_featured', '0')" />
                            <x-input-error :messages="$errors->get('is_featured')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label for="is_public" :value="__('Public')" />
                            <x-select name="is_public" :options="['1' => 'Yes', '0' => 'No']"
                                :value="old('is_public', '1')" />
                            <x-input-error :messages="$errors->get('is_public')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label for="sort_order" :value="__('Sort Order')" />
                            <x-text-input id="sort_order" name="sort_order" type="number" class="mt-1 block w-full"
                                :value="old('sort_order', '0')" />
                            <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label for="image" :value="__('Event Image')" />
                            <x-text-input id="image" name="image" type="file" accept="image/*"
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label for="video" :value="__('Event Video')" />
                            <x-text-input id="video" name="video" type="file"
                                accept="video/mp4,video/quicktime,video/avi,video/webm,video/*"
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('video')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label for="thumbnail" :value="__('Thumbnail')" />
                            <x-text-input id="thumbnail" name="thumbnail" type="file" accept="image/*"
                                class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('thumbnail')" class="mt-2" />
                        </div>
                    </div>
                    <!-- Submit -->
                    <div class="flex flex-col items-center justify-center gap-4">
                        <x-primary-button>{{ __('Submit') }}</x-primary-button>

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
