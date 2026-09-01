<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('constructionStages.index')" :active="true">Construction Stages</x-nav-link>
    </x-slot>

    @php
        $pct = min(round($totalWeight, 2), 100);
        $isComplete = round($totalWeight, 2) == 100;
    @endphp

    <style>
        /* 
         * Custom CSS System 
         * Replaces Tailwind/Bootstrap with semantic, highly specific classes
         */
        :root {
            --cs-primary: #4f46e5;
            --cs-primary-hover: #4338ca;
            --cs-primary-light: #e0e7ff;
            --cs-success: #10b981;
            --cs-success-light: #d1fae5;
            --cs-success-border: #a7f3d0;
            --cs-warning: #f59e0b;
            --cs-danger: #ef4444;
            --cs-danger-hover: #dc2626;
            --cs-danger-light: #fee2e2;
            --cs-bg-body: #f9fafb;
            --cs-bg-card: #ffffff;
            --cs-bg-hover: #f3f4f6;
            --cs-text-main: #111827;
            --cs-text-muted: #6b7280;
            --cs-border: #e5e7eb;
            --cs-radius: 12px;
            --cs-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --cs-shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --cs-transition: all 0.2s ease-in-out;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --cs-primary-light: rgba(79, 70, 229, 0.2);
                --cs-success-light: rgba(16, 185, 129, 0.1);
                --cs-success-border: rgba(16, 185, 129, 0.2);
                --cs-danger-light: rgba(239, 68, 68, 0.15);
                --cs-bg-body: #111827;
                --cs-bg-card: #1f2937;
                --cs-bg-hover: #374151;
                --cs-text-main: #f9fafb;
                --cs-text-muted: #9ca3af;
                --cs-border: #374151;
            }
        }

        [x-cloak] { display: none !important; }

        .cs-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            font-family: inherit;
        }

        /* Intro Section */
        .cs-intro {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }
        .cs-intro-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            background-color: var(--cs-primary-light);
            color: var(--cs-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        .cs-intro-text h1 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--cs-text-main);
            margin: 0;
        }
        .cs-intro-text p {
            font-size: 0.875rem;
            color: var(--cs-text-muted);
            margin: 0.25rem 0 0 0;
            max-width: 42rem;
            line-height: 1.5;
        }

        /* Alerts */
        .cs-alert {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .cs-alert-success {
            background-color: var(--cs-success-light);
            border: 1px solid var(--cs-success-border);
            color: var(--cs-success);
        }

        /* Cards */
        .cs-card {
            background-color: var(--cs-bg-card);
            border-radius: var(--cs-radius);
            box-shadow: var(--cs-shadow);
            border: 1px solid var(--cs-border);
            overflow: hidden;
        }
        .cs-card-body {
            padding: 1.5rem;
        }

        /* Progress Bar */
        .cs-progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }
        .cs-progress-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--cs-text-main);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .cs-progress-title i { color: var(--cs-primary); }
        .cs-progress-value {
            font-size: 0.875rem;
            font-weight: 700;
        }
        .cs-text-success { color: var(--cs-success); }
        .cs-text-warning { color: var(--cs-warning); }
        
        .cs-progress-track {
            height: 0.75rem;
            width: 100%;
            background-color: var(--cs-bg-hover);
            border-radius: 9999px;
            overflow: hidden;
        }
        .cs-progress-fill {
            height: 100%;
            border-radius: 9999px;
            transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .cs-bg-success { background-color: var(--cs-success); }
        .cs-bg-warning { background-color: var(--cs-warning); }
        .cs-progress-hint {
            font-size: 0.75rem;
            color: var(--cs-warning);
            margin-top: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        /* Forms */
        .cs-form-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
            font-size: 1rem;
            font-weight: 600;
            color: var(--cs-text-main);
        }
        .cs-form-header i { color: var(--cs-primary); }
        
        .cs-form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        @media (min-width: 768px) {
            .cs-form-grid { grid-template-columns: repeat(12, 1fr); }
            .cs-col-5 { grid-column: span 5 / span 5; }
            .cs-col-2 { grid-column: span 2 / span 2; }
            .cs-col-3 { grid-column: span 3 / span 3; display: flex; align-items: flex-end; gap: 0.5rem; }
        }
        
        .cs-input-group { position: relative; }
        .cs-input-suffix {
            position: absolute;
            top: 50%;
            right: 0.75rem;
            transform: translateY(-50%);
            color: var(--cs-text-muted);
            font-size: 0.875rem;
            pointer-events: none;
        }

        /* Tables */
        .cs-table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--cs-border);
        }
        .cs-table-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--cs-text-main);
            margin: 0;
        }
        .cs-table-desc {
            font-size: 0.75rem;
            color: var(--cs-text-muted);
            margin: 0.25rem 0 0 0;
        }
        .cs-badge-count {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--cs-text-muted);
            background-color: var(--cs-bg-hover);
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
        }

        .cs-table-wrapper { overflow-x: auto; }
        .cs-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        .cs-table th {
            padding: 0.75rem 1.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
            color: var(--cs-text-muted);
            background-color: var(--cs-bg-body);
        }
        .cs-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--cs-border);
            vertical-align: middle;
            color: var(--cs-text-main);
            font-size: 0.875rem;
        }
        .cs-table tr:last-child td { border-bottom: none; }
        .cs-table tbody tr { transition: var(--cs-transition); }
        .cs-table tbody tr:hover { background-color: var(--cs-bg-hover); }
        
        .cs-order-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.75rem;
            height: 1.75rem;
            border-radius: 50%;
            background-color: var(--cs-bg-hover);
            color: var(--cs-text-muted);
            font-size: 0.75rem;
            font-weight: 600;
        }
        .cs-weight-bar {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .cs-weight-track {
            height: 0.375rem;
            width: 4rem;
            background-color: var(--cs-bg-hover);
            border-radius: 9999px;
            overflow: hidden;
            flex-shrink: 0;
        }
        .cs-weight-fill {
            height: 100%;
            background-color: var(--cs-primary);
            border-radius: 9999px;
        }
        .cs-weight-text {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--cs-text-muted);
        }
        .cs-updates-badge {
            display: inline-flex;
            align-items: center;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--cs-text-muted);
            background-color: var(--cs-bg-hover);
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
        }
        .cs-empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
            color: var(--cs-text-muted);
        }
        .cs-empty-state i {
            font-size: 2.5rem;
            opacity: 0.5;
        }

        /* Actions */
        .cs-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 1rem;
        }
        .cs-btn-icon {
            background: none;
            border: none;
            color: var(--cs-text-muted);
            cursor: pointer;
            font-size: 1.125rem;
            padding: 0;
            transition: var(--cs-transition);
        }
        .cs-btn-icon.cs-edit:hover { color: var(--cs-primary); }
        .cs-btn-icon.cs-delete:hover { color: var(--cs-danger); }

        /* Modal Overrides (Integrating with provided CSS format) */
        .cs-modal-backdrop {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0, 0, 0, 0.6); z-index: 9998; backdrop-filter: blur(4px);
        }
        .cs-modal-wrapper {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 9999;
            display: flex; align-items: center; justify-content: center; padding: 1rem;
        }
        .cs-modal-box {
            background-color: var(--cs-bg-card); border-radius: var(--cs-radius);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            width: 100%; max-width: 480px; overflow: hidden; border: 1px solid var(--cs-border);
        }
        .cs-modal-body { padding: 1.5rem; display: flex; align-items: flex-start; gap: 1rem; }
        .cs-modal-icon {
            flex-shrink: 0; width: 3rem; height: 3rem; border-radius: 50%;
            background-color: var(--cs-danger-light); display: flex; align-items: center; justify-content: center;
            color: var(--cs-danger); font-size: 1.5rem;
        }
        .cs-modal-text h3 { margin: 0 0 0.5rem 0; font-size: 1.125rem; font-weight: 600; color: var(--cs-text-main); line-height: 1.2; }
        .cs-modal-text p { margin: 0; font-size: 0.875rem; line-height: 1.5; color: var(--cs-text-muted); }
        .cs-modal-footer {
            background-color: var(--cs-bg-body); padding: 1rem 1.5rem; display: flex;
            justify-content: flex-end; gap: 0.75rem; border-top: 1px solid var(--cs-border);
        }
        .cs-btn {
            padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 600; border-radius: 0.5rem;
            cursor: pointer; transition: var(--cs-transition); display: inline-flex; justify-content: center; align-items: center;
        }
        .cs-btn-cancel { background-color: var(--cs-bg-card); color: var(--cs-text-main); border: 1px solid var(--cs-border); }
        .cs-btn-cancel:hover { background-color: var(--cs-bg-hover); }
        .cs-btn-danger { background-color: var(--cs-danger); color: #ffffff; border: 1px solid var(--cs-danger); }
        .cs-btn-danger:hover { background-color: var(--cs-danger-hover); border-color: var(--cs-danger-hover); }
    </style>

    <div class="cs-container"
        x-data="{
            deleteTarget: null,
            confirmDelete() {
                if (!this.deleteTarget) return;
                document.getElementById('delete-stage-form-' + this.deleteTarget.id).submit();
                this.deleteTarget = null;
            }
        }">

        <!-- Page Intro -->
        <div class="cs-intro">
            <div class="cs-intro-text">
                <h1>Construction Stages</h1>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('status'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
                class="cs-alert cs-alert-{{ session('status') }}">
                <i class="bi {{ session('status') === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill' }}"></i>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        <!-- Create / Edit Stage Form -->
        @can('edit_construction_updates')
            <div class="cs-card">
                <div class="cs-card-body">
                    <h2 class="cs-form-header">
                        <i class="bi {{ isset($editStage) ? 'bi-pencil-square' : 'bi-plus-circle' }}"></i>
                        {{ isset($editStage) ? 'Edit Stage: ' . $editStage->name : 'Create New Stage' }}
                    </h2>

                    <form method="POST" action="{{ isset($editStage) ? route('constructionStages.update', $editStage->id) : route('constructionStages.store') }}">
                        @csrf
                        @if (isset($editStage))
                            @method('PUT')
                        @endif

                        <div class="cs-form-grid">
                            <!-- Using standard blade components for logic, wrapped safely -->
                            <div class="cs-col-5">
                                <x-input-label for="name" value="Stage Name" />
                                <div style="margin-top: 0.25rem;">
                                    <x-text-input id="name" name="name" type="text" style="width: 100%;"
                                        value="{{ old('name', $editStage->name ?? '') }}" placeholder="e.g. Mobilization" required autofocus />
                                </div>
                                <x-input-error :messages="$errors->get('name')" style="margin-top: 0.5rem;" />
                            </div>

                            <div class="cs-col-2">
                                <x-input-label for="sort_order" value="Display Order" />
                                <div style="margin-top: 0.25rem;">
                                    <x-text-input id="sort_order" name="sort_order" type="number" min="0" style="width: 100%;"
                                        value="{{ old('sort_order', $editStage->sort_order ?? 0) }}" />
                                </div>
                                <x-input-error :messages="$errors->get('sort_order')" style="margin-top: 0.5rem;" />
                            </div>

                            <div class="cs-col-2">
                                <x-input-label for="weight_percentage" value="Weight" />
                                <div class="cs-input-group" style="margin-top: 0.25rem;">
                                    <x-text-input id="weight_percentage" name="weight_percentage" type="number" min="0" max="100" step="0.01"
                                        style="width: 100%; padding-right: 2rem;" placeholder="0"
                                        value="{{ old('weight_percentage', $editStage->weight_percentage ?? 0) }}" />
                                    <span class="cs-input-suffix">%</span>
                                </div>
                                <x-input-error :messages="$errors->get('weight_percentage')" style="margin-top: 0.5rem;" />
                            </div>

                            <div class="cs-col-3">
                                <x-primary-button style="display: flex; align-items: center; justify-content: center; width: 100%;">
                                    <i class="bi {{ isset($editStage) ? 'bi-check-lg' : 'bi-plus-lg' }}" style="margin-right: 0.375rem;"></i>
                                    {{ isset($editStage) ? 'Update' : 'Create' }}
                                </x-primary-button>
                                @if (isset($editStage))
                                    <x-button-link href="{{ route('constructionStages.index') }}" style="display: flex; align-items: center; justify-content: center; background-color: var(--cs-bg-card); border-color: var(--cs-border); color: var(--cs-text-main);">
                                        Cancel
                                    </x-button-link>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endcan

        <!-- Stages Table -->
        <div class="cs-card">
            <div class="cs-table-header">
                <div>
                    <h2 class="cs-table-title">Existing Stages</h2>
                    <p class="cs-table-desc">
                        Shown in this order on every project's Construction Update form.
                    </p>
                </div>
                <span class="cs-badge-count">
                    {{ $stages->count() }} {{ Str::plural('stage', $stages->count()) }}
                </span>
            </div>

            <div class="cs-table-wrapper">
                <table class="cs-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Name</th>
                            <th>Weight</th>
                            <th>Updates</th>
                            @can('edit_construction_updates')
                                <th style="text-align: right;">Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stages as $stage)
                            <tr>
                                <td>
                                    <span class="cs-order-badge">{{ $stage->sort_order }}</span>
                                </td>
                                <td style="font-weight: 500;">{{ $stage->name }}</td>
                                <td>
                                    <div class="cs-weight-bar">
                                        <div class="cs-weight-track">
                                            <div class="cs-weight-fill" style="width: {{ min($stage->weight_percentage, 100) }}%"></div>
                                        </div>
                                        <span class="cs-weight-text">
                                            {{ rtrim(rtrim(number_format($stage->weight_percentage, 2), '0'), '.') }}%
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="cs-updates-badge">
                                        {{ $stage->construction_updates_count }}
                                    </span>
                                </td>
                                @can('edit_construction_updates')
                                    <td>
                                        <div class="cs-actions">
                                            <a href="{{ route('constructionStages.index', ['edit' => $stage->id]) }}"
                                                class="cs-btn-icon cs-edit" title="Edit Stage">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form id="delete-stage-form-{{ $stage->id }}" action="{{ route('constructionStages.destroy', $stage) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button" class="cs-btn-icon cs-delete" title="Delete Stage"
                                                @click="deleteTarget = { id: {{ $stage->id }}, name: @js($stage->name), hasUpdates: {{ $stage->construction_updates_count > 0 ? 'true' : 'false' }} }">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    </td>
                                @endcan
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="cs-empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <p style="margin-top: 0.5rem; font-size: 0.875rem;">No stages yet. Create your first one above.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="deleteTarget" x-cloak>
            <div class="cs-modal-backdrop" x-show="deleteTarget" x-transition.opacity></div>

            <div class="cs-modal-wrapper" x-show="deleteTarget" x-transition
                @click.self="deleteTarget = null" @keydown.escape.window="deleteTarget = null">
                <div class="cs-modal-box">
                    <div class="cs-modal-body">
                        <div class="cs-modal-icon"><i class="bi bi-exclamation-triangle"></i></div>
                        <div class="cs-modal-text">
                            <h3>Delete this stage?</h3>
                            <p>
                                Are you sure you want to delete "<strong style="color: inherit;" x-text="deleteTarget?.name"></strong>"?
                                <template x-if="deleteTarget?.hasUpdates">
                                    <span>This stage still has construction updates attached. Deletion will be blocked until they are moved or removed.</span>
                                </template>
                                <template x-if="!deleteTarget?.hasUpdates">
                                    <span>This action cannot be undone.</span>
                                </template>
                            </p>
                        </div>
                    </div>
                    <div class="cs-modal-footer">
                        <button type="button" @click="deleteTarget = null" class="cs-btn cs-btn-cancel">Cancel</button>
                        <button type="button" @click="confirmDelete()" class="cs-btn cs-btn-danger">Yes, delete</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>