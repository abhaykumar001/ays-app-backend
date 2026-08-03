<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Phase;
use App\Models\Project;
use App\Models\Amenity;
use App\Models\Accommodation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PhasesController extends Controller
{
    /* =======================
     * INDEX
     * ======================= */
    public function index(Project $project)
    {
        $phases = $project->phases()
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return view(
            'dashboard.realestate.phases.index',
            compact('project', 'phases')
        );
    }

    /* =======================
     * CREATE
     * ======================= */
    public function create(Project $project)
    {
        $amenities = Amenity::active()->get();
        $accommodations = Accommodation::active()->get();

        return view(
            'dashboard.realestate.phases.create',
            compact('project', 'amenities', 'accommodations')
        );
    }

    /* =======================
     * STORE
     * ======================= */
    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'slug'          => 'nullable|string|max:255|unique:phases,slug',
            'total_units'   => 'nullable|integer',
            'bedrooms'      => 'nullable|string|max:100',
            'launch_date'   => 'nullable|date',
            'handover_date' => 'nullable|date',
            'handover'      => 'nullable|string|max:50',
            'status'        => 'required|in:planned,under_construction,completed',
            'sort_order'    => 'nullable|integer',
            'is_active'     => 'boolean',
            'amenities'     => 'array',
            'accommodations'=> 'array',
        ]);

        $data['slug'] = ($data['slug'] ?? null)
            ? Str::slug($data['slug'])
            : Str::slug($data['name']);

        $data['user_id'] = auth()->id();
        $data['is_active'] = $request->boolean('is_active');

        $phase = $project->phases()->create($data);

        // Pivot tables (recommended)
        $phase->amenities()->sync($request->amenities ?? []);
        $phase->accommodations()->sync($request->accommodations ?? []);

        return redirect()
            ->route('projects.phases.index', $project)
            ->with('success', 'Phase created successfully.');
    }

    /* =======================
     * EDIT
     * ======================= */
    public function edit(Project $project, Phase $phase)
    {
        abort_if($phase->project_id !== $project->id, 404);

        $amenities = Amenity::active()->get();
        $accommodations = Accommodation::active()->get();

        return view(
            'dashboard.realestate.phases.edit',
            compact('project', 'phase', 'amenities', 'accommodations')
        );
    }

    /* =======================
     * UPDATE
     * ======================= */
    public function update(Request $request, Project $project, Phase $phase)
    {
        abort_if($phase->project_id !== $project->id, 404);

        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'slug'          => 'nullable|string|max:255|unique:phases,slug,' . $phase->id,
            'total_units'   => 'nullable|integer',
            'bedrooms'      => 'nullable|string|max:100',
            'launch_date'   => 'nullable|date',
            'handover_date' => 'nullable|date',
            'handover'      => 'nullable|string|max:50',
            'status'        => 'required|in:planned,under_construction,completed',
            'sort_order'    => 'nullable|integer',
            'is_active'     => 'boolean',
            'amenities'     => 'array',
            'accommodations'=> 'array',
        ]);

        $data['slug'] = ($data['slug'] ?? null)
            ? Str::slug($data['slug'])
            : Str::slug($data['name']);

        $data['is_active'] = $request->boolean('is_active');

        $phase->update($data);

        $phase->amenities()->sync($request->amenities ?? []);
        $phase->accommodations()->sync($request->accommodations ?? []);

        return redirect()
            ->route('projects.phases.index', $project)
            ->with('success', 'Phase updated successfully.');
    }
}
