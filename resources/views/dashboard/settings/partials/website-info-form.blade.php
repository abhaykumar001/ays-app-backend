@php
    $canEdit2 = auth()->user()->can('edit_website-info-update');
@endphp
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Website Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Update your website information below.') }}
        </p>
    </header>

    <form method="post" action="{{ route('website.info.update') }}" class=" mt-6 space-y-8" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="grid md:grid-cols-12 gap-5">
            <div class="md:col-span-6">
                <x-input-label for="website_name" :value="__('Website Name')" />
                <x-text-input id="website_name" name="website_name" type="text" class="mt-1 block w-full"
                    :value="old('website_name', $settingsArr['website_name'] ?? '')" autofocus autocomplete="website_name"  :disabled="!$canEdit2" />
                <x-input-error :messages="$errors->get('website_name')" class="mt-2" />
            </div>

            <div class="md:col-span-6">
                <x-input-label for="website_slogan" :value="__('Website Slogan')" />
                <x-text-input id="website_slogan" name="website_slogan" type="text" class="mt-1 block w-full"
                    :value="old('website_slogan', $settingsArr['website_slogan'] ?? '')" autofocus autocomplete="website_slogan"  :disabled="!$canEdit2" />
                <x-input-error :messages="$errors->get('website_slogan')" class="mt-2" />
            </div>
            <div class="md:col-span-12">
                <x-input-label for="website_url" :value="__('Website URL')" />
                <x-text-input id="website_url" name="website_url" type="text" class="mt-1 block w-full"
                    :value="old('website_url', $settingsArr['website_url'] ?? '')" autofocus autocomplete="website_url"   :disabled="!$canEdit2"/>
                <x-input-error :messages="$errors->get('website_url')" class="mt-2" />
            </div>
            {{-- <div class="md:col-span-6">
                <x-input-label for="footer_text" :value="__('Footer Text')" />
                <x-text-input id="footer_text" name="footer_text" type="text" class="mt-1 block w-full"
                    :value="old('footer_text', $settingsArr['footer_text'] ?? '')" autofocus autocomplete="footer_text"  :disabled="!$canEdit2" />
                <x-input-error :messages="$errors->get('footer_text')" class="mt-2" />
            </div> --}}
            <div class="md:col-span-12">
                <x-input-label for="website_description" :value="__('Website Description')" />
                <x-text-textarea id="website_description" name="website_description" class="mt-1 block w-full" autofocus
                    autocomplete="website_description"  :disabled="!$canEdit2">
                    {{ old('website_description', $settingsArr['website_description'] ?? '') }}
                </x-text-textarea>
                <x-input-error :messages="$errors->get('website_description')" class="mt-2" />
            </div>

            <div class="md:col-span-12">
                <x-input-label for="website_keywords" :value="__('Website Keywords')" />
                <x-text-input id="website_keywords" name="website_keywords" type="text" class="mt-1 block w-full"
                    :value="old('website_keywords', $settingsArr['website_keywords'] ?? '')" autofocus autocomplete="website_keywords"  :disabled="!$canEdit2"/>
                <x-input-error :messages="$errors->get('website_keywords')" class="mt-2" />
            </div>
            <div class="md:col-span-6">
                <x-input-label for="logo" :value="__('Logo')" />
                <x-text-input id="logo" name="logo" type="file" allowed="image/*"
                    class="mt-1 block w-full" :disabled="!$canEdit2"/>
                <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                @if (isset($settingsArr['logo']) && $settingsArr['logo'] != '')
                    <div class="mt-2">
                        <img src="{{ asset($settingsArr['logo']) }}" alt="Current Logo" class="h-16 w-auto">
                    </div>
                @endif
            </div>
            <div class="md:col-span-6">
                <x-input-label for="favicon" :value="__('Favicon')" />
                <x-text-input id="favicon" name="favicon" type="file" allowed="image/*"
                    class="mt-1 block w-full" :disabled="!$canEdit2" />
                <x-input-error :messages="$errors->get('favicon')" class="mt-2" />
                @if (isset($settingsArr['favicon']) && $settingsArr['favicon'] != '')
                    <div class="mt-2">
                        <img src="{{ asset($settingsArr['favicon']) }}" alt="Current Favicon" class="h-16 w-auto">
                    </div>
                @endif
            </div>
            <div class="md:col-span-12">
                <x-input-label for="login_banner" :value="__('App Login Screen Banner')" />
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('This image appears at the top of the mobile app login screen. Recommended size: 1080×1200px.') }}</p>
                <x-text-input id="login_banner" name="login_banner" type="file" allowed="image/*"
                    class="mt-1 block w-full" :disabled="!$canEdit2" />
                <x-input-error :messages="$errors->get('login_banner')" class="mt-2" />
                @if (isset($settingsArr['login_banner']) && $settingsArr['login_banner'] != '')
                    <div class="mt-2">
                        <img src="{{ $settingsArr['login_banner'] }}" alt="Current Login Banner" class="h-48 w-auto rounded-lg object-cover">
                    </div>
                @endif
            </div>

            <div class="md:col-span-12 border-t pt-6 mt-2 dark:border-gray-700">
                <h3 class="text-md font-medium text-gray-900 dark:text-gray-100">{{ __('AYS Screen — Partnership Hero') }}</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Video, title and subtitle shown in the full-bleed hero at the top of the AYS screen in the mobile app.') }}</p>
            </div>

            <div class="md:col-span-6">
                <x-input-label for="partnership_hero_title" :value="__('Hero Title (gold text)')" />
                <x-text-input id="partnership_hero_title" name="partnership_hero_title" type="text" class="mt-1 block w-full"
                    :value="old('partnership_hero_title', $settingsArr['partnership_hero_title'] ?? '')" :disabled="!$canEdit2" />
                <x-input-error :messages="$errors->get('partnership_hero_title')" class="mt-2" />
            </div>
            <div class="md:col-span-6">
                <x-input-label for="partnership_hero_subtitle" :value="__('Hero Subtitle (white text)')" />
                <x-text-input id="partnership_hero_subtitle" name="partnership_hero_subtitle" type="text" class="mt-1 block w-full"
                    :value="old('partnership_hero_subtitle', $settingsArr['partnership_hero_subtitle'] ?? '')" :disabled="!$canEdit2" />
                <x-input-error :messages="$errors->get('partnership_hero_subtitle')" class="mt-2" />
            </div>
            <div class="md:col-span-12">
                <x-input-label for="partnership_hero_video" :value="__('Hero Video')" />
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('MP4/MOV, up to 50MB. Plays muted and looped behind the hero content in the app.') }}</p>
                <x-text-input id="partnership_hero_video" name="partnership_hero_video" type="file" allowed="video/*"
                    class="mt-1 block w-full" :disabled="!$canEdit2" />
                <x-input-error :messages="$errors->get('partnership_hero_video')" class="mt-2" />
                @if (isset($settingsArr['partnership_hero_video']) && $settingsArr['partnership_hero_video'] != '')
                    <div class="mt-2">
                        <video src="{{ $settingsArr['partnership_hero_video'] }}" controls muted class="h-48 w-auto rounded-lg bg-black"></video>
                    </div>
                @endif
            </div>
        </div>
        @if($canEdit2)
        <div class="flex flex-col items-center justify-center gap-4">
            <x-primary-button>{{ __('Update') }}</x-primary-button>
        </div>
        @endif
    </form>
</section>
