@props([
    'id' => 'datatable',
    'columns' => [],
    'data' => collect(),
])

<div class="p-4 mt-8">
    <div class="relative overflow-x-auto">
        <table id="{{ $id }}" class="w-full text-sm text-left">
            <thead class="text-xs uppercase">
                <tr>
                    @foreach ($columns as $col)
                        <th class="px-6 py-3 relative cursor-pointer">{{ $col['label'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $index => $item)

                    <tr class="border-b border-gray-600">
                        @foreach ($columns as $colIndex => $col)
                            <td class="px-6 py-3">

                                {{-- Index --}}
                                @if ($colIndex === 0)
                                    {{ $index + 1 }}
                                @elseif(isset($col['type']) && $col['type'] === 'image')
                                    @php
                                        $imageUrl = $item->{$col['key']} ?? null;
                                    @endphp
                                    @if ($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="{{ $col['label'] }}" class="logoImg">
                                    @else
                                        <span class="text-gray-400">No Image</span>
                                    @endif
                                    {{-- Badge --}}
                                @elseif(isset($col['image']) && $col['image'] === true)

                                @elseif(isset($col['badge']) && $col['badge'] === true)
                                    @php
                                        $value = $item[$col['key']] ?? '-';
                                        if (isset($col['badgeMap']) && is_array($col['badgeMap'])) {
                                            $badgeText = $col['badgeMap'][$value]['text'] ?? $value;
                                            $badgeColor =
                                                $col['badgeMap'][$value]['color'] ??
                                                ($col['color'] ?? 'bg-gray-500 text-white');
                                        } else {
                                            $badgeText = $value;
                                            $badgeColor = $col['color'] ?? 'bg-gray-500 text-white';
                                        }
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded {{ $badgeColor }}">
                                        {{ $badgeText }}
                                    </span>

                                    {{-- Actions passed via Blade --}}
                                @elseif(isset($col['actions']) && is_array($col['actions']))
                                    @foreach ($col['actions'] as $action)
                                        @php
                                            $label = $action['label'] ?? ucfirst($action['type']);
                                            $routeName = $action['url'] ?? '#';
                                        @endphp
                                        @if ($action['type'] === 'view')
                                            <a href="{{ route($routeName, $item['id']) }}" target="_blank"
                                                class="text-yellow-500 hover:underline mr-2">
                                                <i class="bi bi-eye"></i> {{ $label }}
                                            </a>
                                        @elseif ($action['type'] === 'edit')
                                            @if (isset($action['click']))
                                                {{-- Dispatch Alpine event --}}
                                                <button type="button"
                                                    class="text-blue-500 hover:underline cursor-pointer mr-2"
                                                    @click.prevent="openEdit({{ $item['id'] }})">
                                                    <i class="bi bi-pencil-square"></i> {{ $label }}
                                                </button>
                                            @else
                                                <a href="{{ route($routeName, array_merge($action['params'] ?? [], [$item['id']])) }}"
                                                    class="text-blue-500 hover:underline mr-2">
                                                    <i class="bi bi-pencil-square"></i> {{ $label }}
                                                </a>
                                            @endif
                                        @elseif($action['type'] === 'delete')
                                            <form method="POST"
                                                action="{{ route($action['url'], array_merge($action['params'] ?? [], [$item['id']])) }}"
                                                class="inline-block mr-2"
                                                onsubmit="return confirm('Are you sure you want to delete this item?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="text-red-500 hover:underline">
                                                    <i class="bi bi-trash3"></i> {{ $action['label'] }}
                                                </button>
                                            </form>
                                        @elseif($action['type'] === 'phase')
                                            <a href="{{ route($routeName, $item['id']) }}"
                                                class="text-yellow-500 hover:underline mr-2">
                                                <i class="bi bi-list"></i> {{ $label }}
                                            </a>
                                        @elseif($action['type'] === 'highlight')
                                            <a href="{{ route($routeName, $item['id']) }}"
                                                class="text-violet-500 hover:underline mr-2">
                                                <i class="bi bi-star"></i> {{ $label }}
                                            </a>
                                        @elseif($action['type'] === 'virtualTour')
                                            <a href="{{ route($routeName, $item['id']) }}"
                                                class=" text-emerald-500 hover:underline mr-2">
                                                <i class="bi bi-camera-reels"></i> {{ $label }}
                                            </a>
                                        @elseif($action['type'] === 'unit')
                                            <a href="{{ route($routeName, $item['id']) }}"
                                                class="text-green-500 hover:underline mr-2">
                                                <i class="bi bi-list"></i> {{ $label }}
                                            </a>
                                        @elseif($action['type'] === 'paymentPlan')
                                            <a href="{{ route($routeName, $item['id']) }}"
                                                class="text-orange-500 hover:underline mr-2">
                                                <i class="bi bi-credit-card-2-front"></i> {{ $label }}
                                            </a>
                                        @elseif($action['type'] === 'constructionUpdate')
                                            <a href="{{ route($routeName, $item['id']) }}"
                                                class=" text-amber-500 hover:underline mr-2">
                                                <i class="bi bi-building-gear"></i> {{ $label }}
                                            </a>
                                        @endif
                                    @endforeach


                                    {{-- Normal value --}}
                                @else
                                    @php
                                        $value = $item;
                                        $expression = $col['key'];

                                        // Handle expressions like roles->first()?->name
                                        try {
                                            // Evaluate dynamically but safely
                                            $value = eval('return $item->' . $expression . ';');
                                        } catch (\Throwable $e) {
                                            $value = null;
                                        }
                                    @endphp
                                    {{ $value ?? '-' }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) }}" class="text-center py-6 text-gray-500">
                            No data available
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('[id^="datatable"]').forEach(table => {
                    const dt = new DataTable(`#${table.id}`, {
                        searchable: true,
                        perPageSelect: [5, 10, 20, 50, 100, 500, 1000],
                        perPage: 10,
                        labels: {
                            placeholder: "Search...",
                            noRows: "No data available",
                            info: "Showing {start} to {end} of {rows} entries"
                        }
                    });

                    // Dark theme header
                    table.querySelectorAll('thead').forEach(thead => {
                        thead.style.setProperty('background-color', '#374151',
                            'important'); // bg-gray-700
                        thead.style.setProperty('color', '#f3f4f6', 'important'); // text-gray-100
                    });
                });
            });
        </script>
    @endpush
@endonce
