<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('projects.projectOffers.index', $project)" :active="true">
            {{ __('Offers') }} – {{ $project->name }}
        </x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div class="my-auto">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Create New Offer') }} – {{ $project->name }}
                        </h2>
                    </div>
                </div>

                <form method="POST" action="{{ route('projects.projectOffers.store', $project) }}" class="mt-6 space-y-8">
                    @csrf

                    <div class="grid md:grid-cols-12 gap-5">
                        {{-- Title --}}
                        <div class="md:col-span-6">
                            <x-input-label for="title" :value="__('Offer Title')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        {{-- Category --}}
                        <div class="md:col-span-3">
                            <x-input-label for="category" :value="__('Category')" />
                            <x-select name="category" :options="[
                                'exclusive' => 'Exclusive Offer',
                                'payment_plan' => 'Payment Plan',
                                'investment' => 'Investment Offer',
                            ]" :value="old('category', 'exclusive')" />
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

                        {{-- Status --}}
                        <div class="md:col-span-3">
                            <x-input-label for="is_active" :value="__('Status')" />
                            <x-select name="is_active" :options="['1' => 'Active', '0' => 'Inactive']"
                                :value="old('is_active', '1')" />
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>

                        {{-- Description --}}
                        <div class="md:col-span-12">
                            <x-input-label for="description" :value="__('Description')" />
                            <x-text-textarea id="description" name="description" class="mt-1 block w-full">{{ old('description') }}</x-text-textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        {{-- Sort order --}}
                        <div class="md:col-span-3">
                            <x-input-label for="sort_order" :value="__('Sort Order')" />
                            <x-text-input id="sort_order" name="sort_order" type="number" class="mt-1 block w-full"
                                :value="old('sort_order', 0)" />
                            <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Unit availability / pricing --}}
                    <div>
                        <h3 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">
                            {{ __('Unit Availability') }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                            {{ __('Tick the units to include in this offer and optionally set an "Approximate Funds" price for each. Leave the price blank to use the unit\'s own price.') }}
                        </p>

                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs uppercase bg-gray-700 text-gray-100">
                                    <tr>
                                        <th class="px-4 py-3">{{ __('Include') }}</th>
                                        <th class="px-4 py-3">{{ __('Unit') }}</th>
                                        <th class="px-4 py-3">{{ __('Bedrooms') }}</th>
                                        <th class="px-4 py-3">{{ __('Floor') }}</th>
                                        <th class="px-4 py-3">{{ __('Size (SqFt)') }}</th>
                                        <th class="px-4 py-3">{{ __('Unit Price') }}</th>
                                        <th class="px-4 py-3">{{ __('Approximate Funds (Override)') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($units as $unit)
                                        <tr class="border-b border-gray-600">
                                            <td class="px-4 py-3">
                                                <input type="checkbox" name="units[{{ $unit->id }}][included]" value="1"
                                                    {{ collect(old('units.' . $unit->id . '.included'))->first() ? 'checked' : '' }} />
                                            </td>
                                            <td class="px-4 py-3">{{ $unit->title }}</td>
                                            <td class="px-4 py-3">{{ $unit->bedrooms }}</td>
                                            <td class="px-4 py-3">{{ $unit->floor ?? '-' }}</td>
                                            <td class="px-4 py-3">{{ $unit->size_sqft }}</td>
                                            <td class="px-4 py-3">{{ number_format((float) $unit->price, 2) }}</td>
                                            <td class="px-4 py-3">
                                                <x-text-input name="units[{{ $unit->id }}][price]" type="number" step="0.01"
                                                    min="0" class="block w-40"
                                                    :value="old('units.' . $unit->id . '.price')"
                                                    placeholder="{{ number_format((float) $unit->price, 2) }}" />
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-6 text-gray-500">
                                                {{ __('This project has no units yet.') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Submit --}}
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
