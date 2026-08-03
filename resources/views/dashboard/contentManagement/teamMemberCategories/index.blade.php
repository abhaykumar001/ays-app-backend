<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('teamMembers.index')">Team Members</x-nav-link>
        <span class="mx-2 text-gray-400">/</span>
        <x-nav-link :href="route('teamMemberCategories.index')" :active="true">Team Categories</x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-3 rounded {{ session('status') === 'success' ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-red-50 border border-red-200 text-red-700' }} text-sm">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Create / Edit Category Form -->
            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    {{ isset($editCategory) ? 'Edit Category' : 'Create New Category' }}
                </h2>

                <form method="POST"
                    action="{{ isset($editCategory) ? route('teamMemberCategories.update', $editCategory->id) : route('teamMemberCategories.store') }}">
                    @csrf
                    @if (isset($editCategory))
                        @method('PUT')
                    @endif

                    <div class="grid md:grid-cols-12 gap-4">
                        <div class="md:col-span-6">
                            <x-input-label for="name" value="Category Name" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                value="{{ old('name', $editCategory->name ?? '') }}" placeholder="e.g. Sales Director" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="md:col-span-3">
                            <x-input-label for="sort_order" value="Display Order" />
                            <x-text-input id="sort_order" name="sort_order" type="number" min="0" class="mt-1 block w-full"
                                value="{{ old('sort_order', $editCategory->sort_order ?? 0) }}" />
                            <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                        </div>

                        <div class="md:col-span-3 flex items-end gap-2">
                            <x-primary-button>{{ isset($editCategory) ? 'Update Category' : 'Create Category' }}</x-primary-button>
                            @if (isset($editCategory))
                                <x-button-link href="{{ route('teamMemberCategories.index') }}">Cancel</x-button-link>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Categories Table -->
            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Existing Categories</h2>

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr class="text-left text-gray-600 dark:text-gray-300">
                            <th class="px-4 py-2">Order</th>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Team Members</th>
                            <th class="px-4 py-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($categories as $category)
                            <tr class="text-gray-700 dark:text-gray-200">
                                <td class="px-4 py-2">{{ $category->sort_order }}</td>
                                <td class="px-4 py-2 font-medium">{{ $category->name }}</td>
                                <td class="px-4 py-2">{{ $category->team_members_count }}</td>
                                <td class="px-4 py-2 text-right space-x-2">
                                    <a href="{{ route('teamMemberCategories.index', ['edit' => $category->id]) }}">
                                        <x-secondary-button type="button" size="sm">Edit</x-secondary-button>
                                    </a>
                                    <form action="{{ route('teamMemberCategories.destroy', $category) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Delete this category? This only works if no team members are assigned to it.');">
                                        @csrf
                                        @method('DELETE')
                                        <x-danger-button type="submit" size="sm">Delete</x-danger-button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-400">No categories yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
