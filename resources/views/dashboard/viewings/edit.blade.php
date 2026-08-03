<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-medium">Viewing Details</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <x-input-label value="Name" />
                    <p class="mt-1">{{ $viewing->contact_name }}</p>
                </div>
                <div>
                    <x-input-label value="Email" />
                    <p class="mt-1">{{ $viewing->contact_email ?? '-' }}</p>
                </div>
                <div>
                    <x-input-label value="Phone" />
                    <p class="mt-1">{{ $viewing->contact_phone ?? '-' }}</p>
                </div>
                <div>
                    <x-input-label value="Type" />
                    <p class="mt-1">{{ ucfirst(str_replace('_', ' ', $viewing->viewing_type)) }}</p>
                </div>
                <div>
                    <x-input-label value="Project" />
                    <p class="mt-1">{{ $viewing->project?->name ?? '-' }}</p>
                </div>
                <div>
                    <x-input-label value="Unit" />
                    <p class="mt-1">{{ $viewing->unit?->title ?? '-' }}</p>
                </div>
                <div>
                    <x-input-label value="Team Member" />
                    <p class="mt-1">{{ $viewing->teamMember?->name ?? '-' }}</p>
                </div>
                <div>
                    <x-input-label value="Scheduled For" />
                    <p class="mt-1">{{ $viewing->scheduled_at_formatted ?? '-' }}</p>
                </div>
                <div class="md:col-span-2">
                    <x-input-label value="Notes" />
                    <p class="mt-1 whitespace-pre-line">{{ $viewing->notes ?? '-' }}</p>
                </div>
                <div>
                    <x-input-label value="Submitted" />
                    <p class="mt-1">{{ $viewing->created_at }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('viewings.update', $viewing) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label value="Status" />
                    <select name="status" class="form-input w-full">
                        @foreach(['pending','completed','cancelled'] as $status)
                            <option value="{{ $status }}" @selected($viewing->status === $status)>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <x-secondary-button type="button" onclick="history.back()">Cancel</x-secondary-button>
                    <x-primary-button>Update</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
