<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('user.index')" :active="true">
            {{ __('Users') }}
        </x-nav-link>
    </x-slot>

    @php
        $statusMap = [
            'pending' => ['text' => 'Pending Approval', 'color' => 'bg-amber-500 text-white'],
            'active' => ['text' => 'Active', 'color' => 'bg-green-500 text-white'],
            'deactivated' => ['text' => 'Deactivated', 'color' => 'bg-red-500 text-white'],
        ];
        $status = $statusMap[$user->approval_status] ?? ['text' => $user->approval_status, 'color' => 'bg-gray-500 text-white'];
    @endphp

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <div class="my-auto">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('User Details') }}
                        </h2>
                    </div>
                    @can('edit_user')
                        <div class="md:text-end">
                            <x-button-link href="{{ route('user.edit', $user->id) }}">
                                Edit User
                            </x-button-link>
                        </div>
                    @endcan
                </div>

                <div class="grid md:grid-cols-12 gap-5">
                    <div class="md:col-span-6">
                        <x-input-label :value="__('Full Name')" />
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                    </div>

                    <div class="md:col-span-6">
                        <x-input-label :value="__('Email Address')" />
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $user->email }}</p>
                    </div>

                    <div class="md:col-span-6">
                        <x-input-label :value="__('Phone')" />
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $user->phone ?? '-' }}</p>
                    </div>

                    <div class="md:col-span-6">
                        <x-input-label :value="__('Role')" />
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $user->roles->first()?->name ?? '-' }}</p>
                    </div>

                    <div class="md:col-span-6">
                        <x-input-label :value="__('Registered')" />
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $user->registered_at }}</p>
                    </div>

                    <div class="md:col-span-6">
                        <x-input-label :value="__('Status')" />
                        <p class="mt-1">
                            <span class="px-2 py-1 text-xs font-semibold rounded {{ $status['color'] }}">
                                {{ $status['text'] }}
                            </span>
                        </p>
                    </div>

                    @if ($user->hasAnyRole(['External Agent', 'External Agency']))
                        <div class="md:col-span-6">
                            <x-input-label :value="__('Company Name')" />
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $user->company_name ?? '-' }}</p>
                        </div>

                        <div class="md:col-span-6">
                            <x-input-label :value="__('Official Registration Number (ORN)')" />
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $user->official_registration_number ?? '-' }}</p>
                        </div>
                    @endif
                </div>

                @if (! $user->is_approved && auth()->user()->can('edit_user'))
                    <div class="mt-8">
                        <form method="POST" action="{{ route('user.approve', $user->id) }}"
                            onsubmit="return confirm('Approve this account and send them the activation email?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg">
                                <i class="bi bi-patch-check"></i> Approve Account
                            </button>
                        </form>
                    </div>
                @endif

                @if (session('status') === 'success')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                        class="mt-4 text-sm text-green-600 dark:text-green-600">
                        {{ session('message') }}
                    </p>
                @endif
            </div>

            @if ($user->hasRole('External Agent'))
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Broker Documents') }}
                    </h2>

                    <div class="grid md:grid-cols-2 gap-5">
                        @foreach (['passport' => 'Passport', 'emirates_id' => 'Emirates ID'] as $type => $label)
                            <div class="p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">{{ $label }}</p>
                                @if ($documents[$type])
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                                        {{ $documents[$type]['file_name'] }} &middot; uploaded {{ $documents[$type]['uploaded_at'] }}
                                    </p>
                                    <a href="{{ route('user.document.view', [$user->id, $type]) }}" target="_blank"
                                        class="text-yellow-500 hover:underline mr-3">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="{{ route('user.document.download', [$user->id, $type]) }}"
                                        class="text-blue-500 hover:underline">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                @else
                                    <p class="text-sm text-gray-400 dark:text-gray-500">Not uploaded</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($user->hasRole('External Agency'))
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Bank & Tax Details') }}
                    </h2>

                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <x-input-label :value="__('Bank Name')" />
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $user->bank_name ?? '-' }}</p>
                        </div>
                        <div>
                            <x-input-label :value="__('IBAN Number')" />
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $user->iban_number ?? '-' }}</p>
                        </div>
                        <div>
                            <x-input-label :value="__('Account Number')" />
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $user->account_number ?? '-' }}</p>
                        </div>
                        <div>
                            <x-input-label :value="__('Tax Registration Number (TRN)')" />
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $user->trn_number ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Agency Documents') }}
                    </h2>

                    @php
                        $ownerDocLabel = match ($user->owner_document_type) {
                            'poa' => 'Power of Attorney',
                            'passport_eid' => 'Passport / Emirates ID',
                            default => 'Owner Identity Document',
                        };
                    @endphp

                    <div class="grid md:grid-cols-2 gap-5">
                        @foreach (['trade_license' => 'Trade License', 'owner_identity_document' => $ownerDocLabel] as $type => $label)
                            <div class="p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">{{ $label }}</p>
                                @if ($documents[$type])
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                                        {{ $documents[$type]['file_name'] }} &middot; uploaded {{ $documents[$type]['uploaded_at'] }}
                                    </p>
                                    <a href="{{ route('user.document.view', [$user->id, $type]) }}" target="_blank"
                                        class="text-yellow-500 hover:underline mr-3">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="{{ route('user.document.download', [$user->id, $type]) }}"
                                        class="text-blue-500 hover:underline">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                @else
                                    <p class="text-sm text-gray-400 dark:text-gray-500">Not uploaded</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
