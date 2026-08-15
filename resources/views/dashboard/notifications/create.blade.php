<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('notifications.index')">Notifications</x-nav-link>
        <span class="mx-2 text-gray-400">/</span>
        <x-nav-link :href="route('notifications.create')" :active="true">Create Notification</x-nav-link>
    </x-slot>

    <div class="py-6"
        x-data="{
            target: '{{ old('target', 'all') }}',
            deepLinkType: '{{ old('deep_link_type', 'none') }}',
            status: '{{ old('status', 'draft') }}',
        }">
        <div class="sm:px-6 lg:px-8 space-y-6">

            @if (session('error'))
                <div class="p-3 rounded bg-red-50 border border-red-200 text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Create Notification</h2>

                <form method="POST" action="{{ route('notifications.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="status" x-model="status">

                    <div class="grid md:grid-cols-12 gap-5">

                        <div class="md:col-span-6">
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                :value="old('title')" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="md:col-span-6">
                            <x-input-label for="type" :value="__('Type')" />
                            <select id="type" name="type" class="mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm w-full">
                                @foreach (\App\Models\NotificationCampaign::TYPES as $type)
                                    <option value="{{ $type }}" {{ old('type') === $type ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div class="md:col-span-12">
                            <x-input-label for="message" :value="__('Message')" />
                            <x-text-textarea id="message" name="message" class="mt-1 block w-full" required>{{ old('message') }}</x-text-textarea>
                            <x-input-error :messages="$errors->get('message')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label :value="__('Priority')" />
                            <select name="priority" class="mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm w-full">
                                @foreach (['low', 'normal', 'high', 'urgent'] as $p)
                                    <option value="{{ $p }}" {{ old('priority', 'normal') === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ── Target audience ─────────────────────────────────────── --}}
                        <div class="md:col-span-8">
                            <x-input-label :value="__('Send To')" />
                            <div class="flex items-center gap-6 mt-2">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="target" value="all" x-model="target">
                                    <span>All Users</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="target" value="role" x-model="target">
                                    <span>By Role</span>
                                </label>
                            </div>

                            <div x-show="target === 'role'" x-cloak class="flex items-center gap-6 mt-3">
                                @foreach ($roles as $role)
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="roles[]" value="{{ $role }}"
                                            {{ in_array($role, old('roles', [])) ? 'checked' : '' }}>
                                        <span>{{ $role }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('roles')" class="mt-2" />
                        </div>

                        {{-- ── Deep link ────────────────────────────────────────────── --}}
                        <div class="md:col-span-4">
                            <x-input-label :value="__('On Tap, Open')" />
                            <select name="deep_link_type" x-model="deepLinkType" class="mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm w-full">
                                <option value="none">Nothing (just open notifications list)</option>
                                <option value="project">A Project</option>
                                <option value="offer">An Offer</option>
                                <option value="event">An Event</option>
                                <option value="url">A Web Link</option>
                            </select>
                        </div>

                        <div class="md:col-span-8" x-show="deepLinkType === 'project'" x-cloak>
                            <x-input-label :value="__('Project')" />
                            <select name="deep_link_value" class="mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 px-4 py-2 rounded-md shadow-sm w-full">
                                @foreach ($projects as $project)
                                    <option value="{{ $project->slug }}" {{ old('deep_link_value') === $project->slug ? 'selected' : '' }}>{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-8" x-show="deepLinkType === 'offer'" x-cloak>
                            <x-input-label :value="__('Offer')" />
                            <select name="deep_link_value" class="mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 px-4 py-2 rounded-md shadow-sm w-full">
                                @foreach ($offers as $offer)
                                    <option value="{{ $offer->id }}" {{ old('deep_link_value') == $offer->id ? 'selected' : '' }}>{{ $offer->project?->name }} — {{ $offer->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-8" x-show="deepLinkType === 'event'" x-cloak>
                            <x-input-label :value="__('Event')" />
                            <select name="deep_link_value" class="mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 px-4 py-2 rounded-md shadow-sm w-full">
                                @foreach ($events as $event)
                                    <option value="{{ $event->slug }}" {{ old('deep_link_value') === $event->slug ? 'selected' : '' }}>{{ $event->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-8" x-show="deepLinkType === 'url'" x-cloak>
                            <x-input-label :value="__('URL')" />
                            <x-text-input name="deep_link_value" type="text" class="mt-1 block w-full"
                                placeholder="https://…" :value="old('deep_link_value')" />
                        </div>
                        <x-input-error :messages="$errors->get('deep_link_value')" class="mt-2" />

                        {{-- ── Image ────────────────────────────────────────────────── --}}
                        {{-- Hidden for now (banner image upload not ready to expose yet).
                             Backend support (HasMedia collection, controller upload handling)
                             is already in place — just re-add this block to bring it back. --}}

                        {{-- ── Scheduling ───────────────────────────────────────────── --}}
                        <div class="md:col-span-6" x-show="status === 'scheduled'" x-cloak>
                            <x-input-label for="scheduled_at" :value="__('Scheduled For')" />
                            <input type="datetime-local" id="scheduled_at" name="scheduled_at" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                                value="{{ old('scheduled_at') }}">
                            <x-input-error :messages="$errors->get('scheduled_at')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" @click="status = 'draft'"
                            class="px-4 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200">
                            Save Draft
                        </button>
                        <button type="submit" @click="status = 'scheduled'"
                            class="px-4 py-2 text-sm rounded-md border border-blue-300 text-blue-700">
                            Schedule
                        </button>
                        <x-primary-button type="submit" @click="status = 'sent'">
                            Send Now
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
