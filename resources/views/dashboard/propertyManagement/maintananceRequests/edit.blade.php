<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-medium">Edit Maintenance Request</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">

            <form method="POST" action="{{ route('maintananceRequests.update', $request) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Status -->
                <div>
                    <x-input-label value="Status" />
                    <select name="status" class="form-input w-full">
                        @foreach(['pending','in_progress','completed','cancelled'] as $status)
                            <option value="{{ $status }}" @selected($request->status === $status)>
                                {{ ucfirst(str_replace('_',' ',$status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Assigned -->
                <div>
                    <x-input-label value="Assigned To" />
                    <x-text-input name="assigned_to" value="{{ $request->assigned_to }}" class="w-full" />
                </div>

                <!-- Completion -->
                <div>
                    <x-input-label value="Completed At" />
                    <x-text-input type="datetime-local" name="completed_at"
                        value="{{ optional($request->completed_at)->format('Y-m-d\TH:i') }}"
                        class="w-full" />
                </div>

                <!-- Materials -->
                <div>
                    <x-input-label value="Materials Used" />
                    <x-text-textarea name="materials_used" class="w-full">
                        {{ $request->materials_used }}
                    </x-text-textarea>
                </div>

                <!-- Technician Notes -->
                <div>
                    <x-input-label value="Special Instructions" />
                    <x-text-textarea name="special_instructions" class="w-full">
                        {{ $request->special_instructions }}
                    </x-text-textarea>
                </div>

                <!-- Add Images -->
                <div>
                    <x-input-label value="Add Images" />
                    <input type="file" name="images[]" multiple accept="image/*">
                </div>

                <!-- Existing Images -->
                @if($request->images?->count())
                    <div class="flex gap-2 flex-wrap">
                        @foreach($request->images as $image)
                            <img src="{{ asset('storage/'.$image->path) }}" class="h-20 rounded border">
                        @endforeach
                    </div>
                @endif

                <div class="flex justify-end gap-2">
                    <x-secondary-button type="button" onclick="history.back()">Cancel</x-secondary-button>
                    <x-primary-button>Update</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
