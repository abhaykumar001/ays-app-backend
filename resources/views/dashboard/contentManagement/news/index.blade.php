<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('news.index')" :active="true">AYS News</x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">All News Articles</h2>
                <x-button-link :href="route('news.create')">+ Add News</x-button-link>
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
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Author</th>
                            <th class="px-4 py-3">Published</th>
                            <th class="px-4 py-3">Featured</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($news as $i => $article)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3">{{ $i + 1 }}</td>
                                <td class="px-4 py-3 font-medium">{{ $article->title }}</td>
                                <td class="px-4 py-3">{{ $article->author ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $article->published_at?->format('M d, Y') ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded text-xs {{ $article->is_featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $article->is_featured ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded text-xs {{ $article->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                        {{ $article->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 flex gap-2">
                                    <a href="{{ route('news.edit', $article) }}"
                                       class="text-xs px-3 py-1 rounded bg-blue-50 text-blue-700 hover:bg-blue-100">Edit</a>
                                    <form method="POST" action="{{ route('news.destroy', $article) }}"
                                          onsubmit="return confirm('Delete this article?')">
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
                                <td colspan="7" class="px-4 py-6 text-center text-gray-400">No news articles yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
