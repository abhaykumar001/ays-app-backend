@php
    $canEdit = auth()->user()->can('edit_personal-info-update');
@endphp
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Personal Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your website's personal information.") }}
        </p>
    </header>


    <form method="post" action="{{ route('personal.info.update') }}" class="mt-6 space-y-6">
        @csrf
        @if($canEdit)
            @method('put')
        @endif
        <div class="grid md:grid-cols-2 gap-5">
           
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $settingsArr['email'] ?? '')"
                     autocomplete="username" :disabled="!$canEdit" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>
             <div>
                <x-input-label for="phone_number" :value="__('Phone Number')" />
                <x-text-input id="phone_number" name="phone_number" type="tel" class="mt-1 block w-full" :value="old('phone_number', $settingsArr['phone_number'] ?? '')"
                     autofocus autocomplete="phone_number" :disabled="!$canEdit" />
                <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
            </div>
            <div>
                <x-input-label for="facebook" :value="__('Facebook')" />
                <x-text-input id="facebook" name="facebook" type="url" class="mt-1 block w-full" :value="old('facebook', $settingsArr['facebook'] ?? '')"
                     autocomplete="username" :disabled="!$canEdit" />
                <x-input-error class="mt-2" :messages="$errors->get('facebook')" />
            </div>
            <div>
                <x-input-label for="instagram" :value="__('Instagram')" />
                <x-text-input id="instagram" name="instagram" type="url" class="mt-1 block w-full" :value="old('instagram', $settingsArr['instagram'] ?? '')"
                     autocomplete="username" :disabled="!$canEdit" />
                <x-input-error class="mt-2" :messages="$errors->get('instagram')" />
            </div>
            <div>
                <x-input-label for="linkedin" :value="__('LinkedIn')" />
                <x-text-input id="linkedin" name="linkedin" type="url" class="mt-1 block w-full" :value="old('linkedin', $settingsArr['linkedin'] ?? '')"
                     autocomplete="username" :disabled="!$canEdit" />
                <x-input-error class="mt-2" :messages="$errors->get('linkedin')" />
            </div>
            <div>
                <x-input-label for="youtube" :value="__('YouTube')" />
                <x-text-input id="youtube" name="youtube" type="url" class="mt-1 block w-full" :value="old('youtube', $settingsArr['youtube'] ?? '')"
                     autocomplete="username" :disabled="!$canEdit" />
                <x-input-error class="mt-2" :messages="$errors->get('youtube')" />
            </div>
            <div>
                <x-input-label for="twitter" :value="__('X (Twitter)')" />
                <x-text-input id="twitter" name="twitter" type="url" class="mt-1 block w-full" :value="old('twitter', $settingsArr['twitter'] ?? '')"
                     autocomplete="username" :disabled="!$canEdit" />
                <x-input-error class="mt-2" :messages="$errors->get('twitter')" />
            </div>
            <div>
                <x-input-label for="pinterest" :value="__('Pinterest')" />
                <x-text-input id="pinterest" name="pinterest" type="url" class="mt-1 block w-full" :value="old('pinterest', $settingsArr['pinterest'] ?? '')"
                     autocomplete="username" :disabled="!$canEdit" />
                <x-input-error class="mt-2" :messages="$errors->get('pinterest')" />
            </div>
            <div>
                <x-input-label for="tiktok" :value="__('TikTok')" />
                <x-text-input id="tiktok" name="tiktok" type="url" class="mt-1 block w-full" :value="old('tiktok', $settingsArr['tiktok'] ?? '')"
                     autocomplete="username" :disabled="!$canEdit" />
                <x-input-error class="mt-2" :messages="$errors->get('tiktok')" />
            </div>
        </div>
        @if($canEdit) 
        <div class="flex flex-col items-center justify-center gap-4">
            <x-primary-button>{{ __('Update') }}</x-primary-button>
        </div>
        @endif
    </form>
</section>
