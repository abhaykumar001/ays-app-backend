<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('kioskSlides.index')" :active="true">AYS Kiosk Slides</x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Kiosk Slides</h2>
                <x-button-link :href="route('kioskSlides.create')">+ Add Slide</x-button-link>
            </div>

            @if (session('status') === 'success')
                <div class="mb-4 p-3 rounded bg-green-50 border border-green-200 text-green-700 text-sm">
                    {{ session('message') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                    <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3">Order</th>
                            <th class="px-4 py-3">Image</th>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($slides as $slide)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3 text-center">{{ $slide->display_order }}</td>
                                <td class="px-4 py-3">
                                    @if ($slide->getFirstMediaUrl('images'))
                                        <img src="{{ $slide->getFirstMediaUrl('images', 'resize') ?: $slide->getFirstMediaUrl('images') }}"
                                             class="w-20 h-14 object-cover rounded" alt="{{ $slide->title }}">
                                    @else
                                        <div class="w-20 h-14 bg-gray-100 rounded flex items-center justify-center text-gray-400 text-xs">No image</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-medium">{{ $slide->title }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded text-xs {{ $slide->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                        {{ $slide->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 flex gap-2">
                                    <a href="{{ route('kioskSlides.edit', $slide) }}"
                                       class="text-xs px-3 py-1 rounded bg-blue-50 text-blue-700 hover:bg-blue-100">Edit</a>
                                    <form method="POST" action="{{ route('kioskSlides.destroy', $slide) }}"
                                          onsubmit="return confirm('Delete this slide?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="text-xs px-3 py-1 rounded bg-red-50 text-red-700 hover:bg-red-100">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-400">No kiosk slides yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
