<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('teamMembers.index')" :active="true">Team Members</x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Team Members</h2>
                <div class="flex gap-2">
                    <x-button-link :href="route('teamMemberCategories.index')">Manage Categories</x-button-link>
                    <x-button-link :href="route('teamMembers.create')">+ Add Team Member</x-button-link>
                </div>
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
                            <th class="px-4 py-3">Photo</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Category</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Languages</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($members as $member)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3 text-center">{{ $member->display_order }}</td>
                                <td class="px-4 py-3">
                                    @if ($member->getFirstMediaUrl('images'))
                                        <img src="{{ $member->getFirstMediaUrl('images', 'resize') ?: $member->getFirstMediaUrl('images') }}"
                                             class="w-14 h-14 object-cover rounded-full" alt="{{ $member->name }}">
                                    @else
                                        <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 text-xs">No photo</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-medium">{{ $member->name }}</td>
                                <td class="px-4 py-3">{{ $member->category?->name }}</td>
                                <td class="px-4 py-3">{{ $member->email }}</td>
                                <td class="px-4 py-3">{{ $member->languages }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded text-xs {{ $member->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                        {{ $member->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 flex gap-2">
                                    <a href="{{ route('teamMembers.edit', $member) }}"
                                       class="text-xs px-3 py-1 rounded bg-blue-50 text-blue-700 hover:bg-blue-100">Edit</a>
                                    <form method="POST" action="{{ route('teamMembers.destroy', $member) }}"
                                          onsubmit="return confirm('Delete this team member?')">
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
                                <td colspan="8" class="px-4 py-6 text-center text-gray-400">No team members yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
