<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('notifications.index')">Notifications</x-nav-link>
        <span class="mx-2 text-gray-400">/</span>
        <x-nav-link :href="route('notifications.show', $campaign)" :active="true">{{ $campaign->title }}</x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg space-y-6">

                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $campaign->title }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $campaign->message }}</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold rounded
                        @class([
                            'bg-gray-200 text-gray-800' => $campaign->status === 'draft',
                            'bg-blue-200 text-blue-800' => $campaign->status === 'scheduled',
                            'bg-yellow-200 text-yellow-800' => $campaign->status === 'sending',
                            'bg-green-200 text-green-800' => $campaign->status === 'sent',
                            'bg-red-200 text-red-800' => $campaign->status === 'failed',
                        ])">
                        {{ ucfirst($campaign->status) }}
                    </span>
                </div>

                @if ($campaign->getFirstMediaUrl('image'))
                    <img src="{{ $campaign->getFirstMediaUrl('image') }}" class="rounded-lg max-h-64">
                @endif

                <div class="grid md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Type</div>
                        <div class="font-medium">{{ ucfirst(str_replace('_', ' ', $campaign->type)) }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Priority</div>
                        <div class="font-medium">{{ ucfirst($campaign->priority) }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Target</div>
                        <div class="font-medium">{{ $campaign->target === 'all' ? 'All Users' : implode(', ', $campaign->roles ?? []) }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Deep Link</div>
                        <div class="font-medium">{{ $campaign->deep_link_type ?? 'none' }}@if($campaign->deep_link_value) — {{ $campaign->deep_link_value }} @endif</div>
                    </div>
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Created By</div>
                        <div class="font-medium">{{ $campaign->creator?->name ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Scheduled For</div>
                        <div class="font-medium">{{ $campaign->scheduled_at?->format('d M Y, h:i A') ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Sent At</div>
                        <div class="font-medium">{{ $campaign->sent_at?->format('d M Y, h:i A') ?? '—' }}</div>
                    </div>
                </div>

                @if (in_array($campaign->status, ['sent', 'sending']))
                    <div class="grid md:grid-cols-3 gap-4">
                        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900 text-center">
                            <div class="text-2xl font-semibold">{{ $campaign->total_recipients }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Recipients</div>
                        </div>
                        <div class="p-4 rounded-lg bg-green-50 dark:bg-gray-900 text-center">
                            <div class="text-2xl font-semibold text-green-700">{{ $campaign->delivered_count }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Delivered</div>
                        </div>
                        <div class="p-4 rounded-lg bg-red-50 dark:bg-gray-900 text-center">
                            <div class="text-2xl font-semibold text-red-700">{{ $campaign->failed_count }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Failed</div>
                        </div>
                    </div>
                @endif

                @if (! in_array($campaign->status, ['sent', 'sending']) && auth()->user()->can('delete_notifications'))
                    <form method="POST" action="{{ route('notifications.destroy', $campaign) }}"
                        onsubmit="return confirm('Delete this notification?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline text-sm">
                            <i class="bi bi-trash3"></i> Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
