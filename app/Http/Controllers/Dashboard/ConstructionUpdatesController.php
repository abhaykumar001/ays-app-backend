<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ConstructionUpdate;
use App\Models\Project;
use Illuminate\Http\Request;

class ConstructionUpdatesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_construction_updates')->only(['index', 'show']);
        $this->middleware('permission:create_construction_updates')->only(['create', 'store']);
        $this->middleware('permission:edit_construction_updates')->only(['edit', 'update']);
        $this->middleware('permission:delete_construction_updates')->only(['destroy']);
    }
    public function index(Project $project)
    {
        $updates = $project->constructionUpdates()->orderBy('sort_order')->get();
        return view('dashboard.realestate.constructionUpdates.index', compact('project', 'updates'));
    }

    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'update_date' => 'nullable|date',
            'stage' => 'nullable|in:foundation,structure,facade,interior,finishing',
            'is_public' => 'required|boolean',
            'is_active' => 'required|boolean',
            'file' => 'nullable|file|mimes:jpg,png,jpeg,mp4,zip',
            'link' => 'nullable|url',
        ]);

        $update = $project->constructionUpdates()->create($data);

        if ($request->hasFile('file')) {
            $update->addMedia($request->file('file'))->toMediaCollection('updates');
        }

        return back()->with('success', 'Construction Update saved.');
    }

    public function edit(Project $project, ConstructionUpdate $constructionUpdate)
    {
        $constructionUpdate->file_url = $constructionUpdate->getFirstMediaUrl('updates');
        return response()->json($constructionUpdate);
    }

    public function update(Request $request, Project $project, ConstructionUpdate $constructionUpdate)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'update_date' => 'nullable|date',
            'stage' => 'nullable|in:foundation,structure,facade,interior,finishing',
            'is_public' => 'required|boolean',
            'is_active' => 'required|boolean',
            'file' => 'nullable|file|mimes:jpg,png,jpeg,mp4,zip',
            'link' => 'nullable|url',
        ]);

        $constructionUpdate->update($data);

        if ($request->hasFile('file')) {
            $constructionUpdate->clearMediaCollection('updates');
            $constructionUpdate->addMedia($request->file('file'))->toMediaCollection('updates');
        }

        return back()->with('success', 'Construction Update updated.');
    }

    public function destroy(Project $project, ConstructionUpdate $constructionUpdate)
    {
        $constructionUpdate->clearMediaCollection('updates');
        $constructionUpdate->delete();

        return back()->with('success', 'Construction Update deleted.');
    }
}
