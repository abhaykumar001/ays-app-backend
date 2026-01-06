<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('tags.index')" :active="true">
            {{ __('Tags Management') }}
        </x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <!-- Create / Edit Tag Form -->
            @canany(['create_tags', 'edit_tags'])
                <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ isset($editTag) ? 'Edit Tag' : 'Create New Tag' }}
                    </h2>

                    <form method="POST"
                        action="{{ isset($editTag) ? route('tags.update', $editTag->id) : route('tags.store') }}">
                        @csrf
                        @if (isset($editTag))
                            @method('PUT')
                        @endif

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="name" value="Tag Name" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                    value="{{ old('name', $editTag->name ?? '') }}" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div class="mt-6">
                                @if (!isset($editTag) && auth()->user()->can('create_tags'))
                                    <x-primary-button>
                                        Create Tag
                                    </x-primary-button>
                                @elseif(isset($editTag) && auth()->user()->can('edit_tags'))
                                    <x-primary-button>
                                        Update Tag
                                    </x-primary-button>
                                @endif

                                @if (isset($editTag))
                                    <x-button-link href="{{ route('tags.index') }}" class="ml-2">
                                        Cancel
                                    </x-button-link>
                                @endif
                            </div>
                        </div>


                    </form>
                </div>
            @endcanany
            <!-- Tags Table -->
            @can('view_tags')
            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('Existing Tags') }}
                </h2>

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr class="text-left text-gray-600 dark:text-gray-300">
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">Name</th>
                            @canany(['delete_tags', 'edit_tags']) <th class="px-4 py-2 text-right">Actions</th> @endcanany
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($tags as $index => $tag)
                            <tr class="text-gray-700 dark:text-gray-200">
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2">{{ $tag->name }}</td>

                                <td class="px-4 py-2 text-right space-x-2">
                                    @can('edit_tags')
                                    <form action="{{ route('tags.edit', $tag->id) }}" method="GET" class="inline">
                                        <x-secondary-button type="submit" size="sm">Edit</x-secondary-button>
                                    </form>
                                    @endcan
                                    @can('delete_tags')
                                    <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Are you sure you want to delete this tag?');">
                                        @csrf
                                        @method('DELETE')
                                        <x-danger-button type="submit" size="sm">Delete</x-danger-button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $tags->links() }}
                </div>
            </div>
            @endcan
        </div>
    </div>
</x-app-layout>
