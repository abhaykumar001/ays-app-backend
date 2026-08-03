<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-medium">Enquiry Details</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <x-input-label value="Name" />
                    <p class="mt-1">{{ $enquiry->contact_name }}</p>
                </div>
                <div>
                    <x-input-label value="Email" />
                    <p class="mt-1">{{ $enquiry->contact_email ?? '-' }}</p>
                </div>
                <div>
                    <x-input-label value="Phone" />
                    <p class="mt-1">{{ $enquiry->contact_phone ?? '-' }}</p>
                </div>
                <div>
                    <x-input-label value="Type" />
                    <p class="mt-1">{{ $enquiry->enquiry_type }}</p>
                </div>
                <div>
                    <x-input-label value="Project" />
                    <p class="mt-1">{{ $enquiry->project?->name ?? '-' }}</p>
                </div>
                <div>
                    <x-input-label value="Unit" />
                    <p class="mt-1">{{ $enquiry->unit?->title ?? '-' }}</p>
                </div>
                <div>
                    <x-input-label value="Team Member" />
                    <p class="mt-1">{{ $enquiry->teamMember?->name ?? '-' }}</p>
                </div>
                <div class="md:col-span-2">
                    <x-input-label value="Message" />
                    <p class="mt-1 whitespace-pre-line">{{ $enquiry->message ?? '-' }}</p>
                </div>
                <div>
                    <x-input-label value="Submitted" />
                    <p class="mt-1">{{ $enquiry->created_at }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('enquiries.update', $enquiry) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label value="Status" />
                    <select name="status" class="form-input w-full">
                        @foreach(['new','contacted','converted'] as $status)
                            <option value="{{ $status }}" @selected($enquiry->status === $status)>
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
