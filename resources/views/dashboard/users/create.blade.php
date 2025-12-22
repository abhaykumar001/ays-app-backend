<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('user.index')" :active="true">
            {{ __('Users') }}
        </x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div class="my-auto">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Create New User') }}
                        </h2>
                    </div>
                </div>

                <form method="POST" action="{{ route('user.store') }}" class="mt-6 space-y-8">
                    @csrf
                    <div class="grid md:grid-cols-12 gap-5">
                        <!-- Name -->
                        <div class="md:col-span-6">
                            <x-input-label for="name" :value="__('Full Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                :value="old('name')" required autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="md:col-span-6">
                            <x-input-label for="email" :value="__('Email Address')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                :value="old('email')" required autocomplete="email" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="md:col-span-6">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                                required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Role -->
                        <div class="md:col-span-6">
                            <x-input-label for="role" :value="__('Assign Role')" />
                            <select id="role" name="role_id"
                                class="block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-gray-200">
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
                        </div>
                    </div>

                    
                    <!-- Submit -->
                    <div class="flex flex-col items-center justify-center gap-4">
                        <x-primary-button>{{ __('Create User') }}</x-primary-button>

                        @if (session('status') === 'success')
                            <p x-data="{ show: true }" x-show="show" x-transition
                                x-init="setTimeout(() => show = false, 2000)"
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
