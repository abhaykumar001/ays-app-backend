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
        $this->middleware('permission:edit_construction_updates')->only(['edit', 'update', 'destroyMedia']);
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
            'is_public' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:jpg,png,jpeg,webp,mp4,zip',
            'link' => 'nullable|url',
        ]);

        $data['is_public'] = $request->boolean('is_public');
        $data['is_active'] = $request->boolean('is_active', true);

        $update = $project->constructionUpdates()->create($data);

        foreach ($request->file('files', []) as $file) {
            $update->addMedia($file)->toMediaCollection('updates');
        }

        return back()->with('success', 'Construction Update saved.');
    }

    public function edit(Project $project, ConstructionUpdate $constructionUpdate)
    {
        $constructionUpdate->media_items = $constructionUpdate->getMedia('updates')
            ->map(fn ($m) => ['id' => $m->id, 'url' => $m->getUrl()])
            ->values();
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
            'is_public' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:jpg,png,jpeg,webp,mp4,zip',
            'link' => 'nullable|url',
        ]);

        $data['is_public'] = $request->boolean('is_public');
        $data['is_active'] = $request->boolean('is_active', true);

        $constructionUpdate->update($data);

        foreach ($request->file('files', []) as $file) {
            $constructionUpdate->addMedia($file)->toMediaCollection('updates');
        }

        return back()->with('success', 'Construction Update updated.');
    }

    public function destroy(Project $project, ConstructionUpdate $constructionUpdate)
    {
        $constructionUpdate->clearMediaCollection('updates');
        $constructionUpdate->delete();

        return back()->with('success', 'Construction Update deleted.');
    }

    public function destroyMedia(Project $project, ConstructionUpdate $constructionUpdate, int $media)
    {
        $constructionUpdate->media()->where('id', $media)->firstOrFail()->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Image removed.');
    }
}
