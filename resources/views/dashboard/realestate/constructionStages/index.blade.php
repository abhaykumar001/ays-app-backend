<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('constructionStages.index')" :active="true">Construction Stages</x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-3 rounded {{ session('status') === 'success' ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-red-50 border border-red-200 text-red-700' }} text-sm">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Create / Edit Stage Form -->
            @can('edit_construction_updates')
                <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ isset($editStage) ? 'Edit Stage' : 'Create New Stage' }}
                    </h2>

                    <form method="POST"
                        action="{{ isset($editStage) ? route('constructionStages.update', $editStage->id) : route('constructionStages.store') }}">
                        @csrf
                        @if (isset($editStage))
                            @method('PUT')
                        @endif

                        <div class="grid md:grid-cols-12 gap-4">
                            <div class="md:col-span-5">
                                <x-input-label for="name" value="Stage Name" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                    value="{{ old('name', $editStage->name ?? '') }}" placeholder="e.g. Mobilization" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="sort_order" value="Display Order" />
                                <x-text-input id="sort_order" name="sort_order" type="number" min="0" class="mt-1 block w-full"
                                    value="{{ old('sort_order', $editStage->sort_order ?? 0) }}" />
                                <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="weight_percentage" value="Weight (%)" />
                                <x-text-input id="weight_percentage" name="weight_percentage" type="number" min="0" max="100" step="0.01"
                                    class="mt-1 block w-full" placeholder="e.g. 12"
                                    value="{{ old('weight_percentage', $editStage->weight_percentage ?? 0) }}" />
                                <x-input-error :messages="$errors->get('weight_percentage')" class="mt-2" />
                            </div>

                            <div class="md:col-span-3 flex items-end gap-2">
                                <x-primary-button>{{ isset($editStage) ? 'Update Stage' : 'Create Stage' }}</x-primary-button>
                                @if (isset($editStage))
                                    <x-button-link href="{{ route('constructionStages.index') }}">Cancel</x-button-link>
                                @endif
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">
                            Weight is how much this stage contributes to a project's overall progress
                            (e.g. Mobilization 4%, Superstructure 22%). Overall progress = Σ(stage progress % × stage weight %) / 100.
                        </p>
                    </form>
                </div>
            @endcan

            <!-- Stages Table -->
            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Existing Stages</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                    These stages appear in the same order below on every project's Construction Update form and in the app. A project can only have one update per stage.
                </p>
                <p class="text-xs mb-4 {{ round($totalWeight, 2) == 100 ? 'text-green-600' : 'text-amber-600' }}">
                    Total weight across all stages: {{ rtrim(rtrim(number_format($totalWeight, 2), '0'), '.') }}%
                    @if (round($totalWeight, 2) != 100)
                        — should usually add up to 100% so overall progress reads as a true percentage.
                    @endif
                </p>

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr class="text-left text-gray-600 dark:text-gray-300">
                            <th class="px-4 py-2">Order</th>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Weight %</th>
                            <th class="px-4 py-2">Updates Using This Stage</th>
                            @can('edit_construction_updates')
                                <th class="px-4 py-2 text-right">Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($stages as $stage)
                            <tr class="text-gray-700 dark:text-gray-200">
                                <td class="px-4 py-2">{{ $stage->sort_order }}</td>
                                <td class="px-4 py-2 font-medium">{{ $stage->name }}</td>
                                <td class="px-4 py-2">{{ rtrim(rtrim(number_format($stage->weight_percentage, 2), '0'), '.') }}%</td>
                                <td class="px-4 py-2">{{ $stage->construction_updates_count }}</td>
                                @can('edit_construction_updates')
                                    <td class="px-4 py-2 text-right space-x-2">
                                        <a href="{{ route('constructionStages.index', ['edit' => $stage->id]) }}">
                                            <x-secondary-button type="button" size="sm">Edit</x-secondary-button>
                                        </a>
                                        <form action="{{ route('constructionStages.destroy', $stage) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Delete this stage? This only works if no construction updates use it.');">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button type="submit" size="sm">Delete</x-danger-button>
                                        </form>
                                    </td>
                                @endcan
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-400">No stages yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
