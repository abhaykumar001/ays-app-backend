<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\VirtualTour;
use Illuminate\Http\Request;

class VirtualToursController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_virtual_tours')->only(['index', 'show']);
        $this->middleware('permission:create_virtual_tours')->only(['create', 'store']);
        $this->middleware('permission:edit_virtual_tours')->only(['edit', 'update']);
        $this->middleware('permission:delete_virtual_tours')->only(['destroy']);
    }
    public function index(Project $project)
    {
        $virtualTours = $project->virtualTours()->get();
        return view('dashboard.realestate.virtualTours.index', compact('project', 'virtualTours'));
    }

    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'type' => 'required|in:360,video,iframe,link',
            'url' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
            'file' => 'nullable|file|mimes:jpg,png,jpeg,mp4,zip',
        ]);

        $virtualTour = $project->virtualTours()->create([
            'title' => $data['title'] ?? null,
            'type' => $data['type'],
            'url' => $data['url'] ?? null,
            'is_active' => $data['is_active'],
        ]);

        if (!empty($data['file'])) {
            $virtualTour->addMedia($data['file'])->toMediaCollection('virtual_tours');
        }

        return back()->with('success', 'Virtual Tour saved.');
    }

    public function edit(Project $project, VirtualTour $virtualTour)
    {
        // Include media URL if exists
        $virtualTour->file_url = $virtualTour->hasMedia('virtual_tours')
            ? $virtualTour->getFirstMediaUrl('virtual_tours') : null;
        return response()->json($virtualTour);
    }

    public function update(Request $request, Project $project, VirtualTour $virtualTour)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'type' => 'required|in:360,video,iframe,link',
            'url' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
            'file' => 'nullable|file|mimes:jpg,png,jpeg,mp4,zip',
        ]);

        $virtualTour->update([
            'title' => $data['title'] ?? null,
            'type' => $data['type'],
            'url' => $data['url'] ?? null,
            'is_active' => $data['is_active'],
        ]);

        if (!empty($data['file'])) {
            // Remove old media if exists
            $virtualTour->clearMediaCollection('virtual_tours');

            $virtualTour->addMedia($data['file'])->toMediaCollection('virtual_tours');
        }

        return back()->with('success', 'Virtual Tour updated.');
    }

    public function destroy(Project $project, VirtualTour $virtualTour)
    {
        $virtualTour->clearMediaCollection('virtual_tours');
        $virtualTour->delete();

        return back()->with('success', 'Virtual Tour deleted.');
    }
}