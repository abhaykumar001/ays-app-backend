<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('projects.index')" :active="true">
            {{ __('Projects') }}
        </x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg space-y-8">

                {{-- Header --}}
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ $project->name }}
                        </h2>
                        @if ($project->project_code)
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $project->project_code }}</p>
                        @endif
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded {{ $project->is_active ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                                {{ $project->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-200 text-gray-800">
                                {{ str($project->project_status)->replace('_', ' ')->title() }}
                            </span>
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-200 text-blue-800">
                                {{ str($project->sales_status)->replace('_', ' ')->title() }}
                            </span>
                            @if ($project->is_featured)
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-purple-200 text-purple-800">Featured</span>
                            @endif
                            @if ($project->is_new_launch)
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-teal-200 text-teal-800">New Launch</span>
                            @endif
                            @if ($project->is_hot_selling)
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-red-200 text-red-800">Hot Selling</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-2">
                        @canany(['edit_projects', 'edit_project_pricing'])
                            <x-button-link href="{{ route('projects.edit', $project->id) }}">
                                {{ __('Edit') }}
                            </x-button-link>
                        @endcanany
                    </div>
                </div>

                {{-- Overview --}}
                <div>
                    <h3 class="text-sm font-semibold uppercase text-gray-500 dark:text-gray-400 mb-3">Overview</h3>
                    <dl class="grid sm:grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-4 text-sm">
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Community</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $project->community?->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Sub Community</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $project->sub_community ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">City</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $project->city ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Starting Price</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $project->starting_price ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Price per SqFt</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $project->price_per_sqft ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Highest ROI</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $project->roi ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Total Units</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $project->total_units ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Available Units</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $project->available_units ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Bedrooms</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $project->bedrooms ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Bathrooms</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $project->bathrooms ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Size Range</dt>
                            <dd class="text-gray-900 dark:text-gray-100">
                                {{ $project->min_size || $project->max_size ? ($project->min_size ?? '-') . ' - ' . ($project->max_size ?? '-') . ' sqft' : '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Launch Date</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $project->launch_date ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Handover</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $project->handover ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Handover Date</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $project->handover_date ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-2 md:col-span-3">
                            <dt class="text-gray-500 dark:text-gray-400">Address</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $project->address ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Descriptions --}}
                @if ($project->short_description || $project->description || $project->title_description || $project->quote_description)
                    <div>
                        <h3 class="text-sm font-semibold uppercase text-gray-500 dark:text-gray-400 mb-3">Descriptions</h3>
                        <div class="space-y-3 text-sm text-gray-900 dark:text-gray-100">
                            @if ($project->title_description)
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400">Title Description</dt>
                                    <dd>{{ $project->title_description }}</dd>
                                </div>
                            @endif
                            @if ($project->short_description)
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400">Short Description</dt>
                                    <dd>{{ $project->short_description }}</dd>
                                </div>
                            @endif
                            @if ($project->description)
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400">Full Description</dt>
                                    <dd class="whitespace-pre-line">{{ $project->description }}</dd>
                                </div>
                            @endif
                            @if ($project->quote_description)
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400">Quote</dt>
                                    <dd class="italic">"{{ $project->quote_description }}"</dd>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Materiality --}}
                @if ($project->materiality_title || $project->materiality_description || $project->getMedia('materiality')->isNotEmpty())
                    <div>
                        <h3 class="text-sm font-semibold uppercase text-gray-500 dark:text-gray-400 mb-3">Materiality</h3>
                        @if ($project->materiality_title)
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $project->materiality_title }}</p>
                        @endif
                        @if ($project->materiality_description)
                            <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $project->materiality_description }}</p>
                        @endif
                        @if ($project->getMedia('materiality')->isNotEmpty())
                            <div class="flex flex-wrap gap-2 mt-3">
                                @foreach ($project->getMedia('materiality') as $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank">
                                        <img src="{{ $media->getUrl() }}" class="h-24 w-auto rounded" alt="Materiality image">
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Media --}}
                <div>
                    <h3 class="text-sm font-semibold uppercase text-gray-500 dark:text-gray-400 mb-3">Media</h3>

                    @if ($project->getFirstMediaUrl('images'))
                        <div class="mb-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Project Image</p>
                            <img src="{{ $project->getFirstMediaUrl('images', 'resize') ?: $project->getFirstMediaUrl('images') }}"
                                class="h-32 w-auto rounded" alt="Project image">
                        </div>
                    @endif

                    @if ($project->getMedia('images')->count() > 1)
                        <div class="mb-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Gallery ({{ $project->getMedia('images')->count() }})</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($project->getMedia('images') as $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank">
                                        <img src="{{ $media->getUrl() }}" class="h-20 w-auto rounded" alt="Gallery image">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($project->hasMedia('videos'))
                        <div class="mb-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Video</p>
                            <video src="{{ $project->getFirstMediaUrl('videos') }}" controls class="h-32 rounded"></video>
                        </div>
                    @endif

                    <div class="flex flex-wrap gap-4 text-sm">
                        @if ($project->hasMedia('brochures'))
                            <a href="{{ $project->getFirstMediaUrl('brochures') }}" target="_blank" class="text-primary hover:underline">
                                <i class="bi bi-file-earmark-pdf"></i> Brochure
                            </a>
                        @endif
                        @if ($project->hasMedia('floorplans'))
                            <a href="{{ $project->getFirstMediaUrl('floorplans') }}" target="_blank" class="text-primary hover:underline">
                                <i class="bi bi-file-earmark-image"></i> Floorplan
                            </a>
                        @endif
                        @if ($project->hasMedia('payment_plans'))
                            <a href="{{ $project->getFirstMediaUrl('payment_plans') }}" target="_blank" class="text-primary hover:underline">
                                <i class="bi bi-file-earmark-text"></i> Payment Plan
                            </a>
                        @endif
                        @if ($project->virtual_tour_url)
                            <a href="{{ $project->virtual_tour_url }}" target="_blank" class="text-primary hover:underline">
                                <i class="bi bi-badge-vr"></i> 360&deg; Virtual Tour
                            </a>
                        @endif
                    </div>

                    @if (!$project->getFirstMediaUrl('images') && $project->getMedia('images')->isEmpty() && !$project->hasMedia('videos') && !$project->hasMedia('brochures') && !$project->hasMedia('floorplans') && !$project->hasMedia('payment_plans') && !$project->virtual_tour_url)
                        <p class="text-sm text-gray-500 dark:text-gray-400">No media uploaded.</p>
                    @endif
                </div>

                {{-- Amenities & Accommodations --}}
                @if ($project->amenities->isNotEmpty() || $project->accommodations->isNotEmpty())
                    <div class="grid sm:grid-cols-2 gap-6">
                        @if ($project->amenities->isNotEmpty())
                            <div>
                                <h3 class="text-sm font-semibold uppercase text-gray-500 dark:text-gray-400 mb-3">Amenities</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($project->amenities as $amenity)
                                        <span class="px-2 py-1 text-xs rounded bg-gray-200 text-gray-800">{{ $amenity->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        @if ($project->accommodations->isNotEmpty())
                            <div>
                                <h3 class="text-sm font-semibold uppercase text-gray-500 dark:text-gray-400 mb-3">Accommodations</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($project->accommodations as $accommodation)
                                        <span class="px-2 py-1 text-xs rounded bg-gray-200 text-gray-800">{{ $accommodation->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Related --}}
                <div>
                    <h3 class="text-sm font-semibold uppercase text-gray-500 dark:text-gray-400 mb-3">Related</h3>
                    <div class="flex flex-wrap gap-3 text-sm">
                        @can('view_phases')
                            <a href="{{ route('projects.phases.index', $project->id) }}" class="text-yellow-500 hover:underline">
                                <i class="bi bi-list"></i> Phases ({{ $project->phases->count() }})
                            </a>
                        @endcan
                        @can('view_units')
                            <a href="{{ route('projects.units.index', $project->id) }}" class="text-green-500 hover:underline">
                                <i class="bi bi-list"></i> Units ({{ $project->units->count() }})
                            </a>
                        @endcan
                        @can('view_virtual_tours')
                            <a href="{{ route('projects.virtualTours.index', $project->id) }}" class="text-emerald-500 hover:underline">
                                <i class="bi bi-camera-reels"></i> Virtual Tours
                            </a>
                        @endcan
                        @can('view_highlights')
                            <a href="{{ route('projects.highlights.index', $project->id) }}" class="text-violet-500 hover:underline">
                                <i class="bi bi-star"></i> Highlights
                            </a>
                        @endcan
                        @can('view_payment_plans')
                            <a href="{{ route('projects.paymentPlans.index', $project->id) }}" class="text-orange-500 hover:underline">
                                <i class="bi bi-credit-card-2-front"></i> Payment Plans
                            </a>
                        @endcan
                        @can('view_construction_updates')
                            <a href="{{ route('projects.constructionUpdates.index', $project->id) }}" class="text-amber-500 hover:underline">
                                <i class="bi bi-building-gear"></i> Construction Updates
                            </a>
                        @endcan
                        @can('view_project_offers')
                            <a href="{{ route('projects.projectOffers.index', $project->id) }}" class="text-pink-500 hover:underline">
                                <i class="bi bi-tags"></i> Offers
                            </a>
                        @endcan
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
