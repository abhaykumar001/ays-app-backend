<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ConstructionStage;
use App\Models\ConstructionUpdate;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class ConstructionUpdatesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_construction_updates')->only(['index', 'show']);
        $this->middleware('permission:create_construction_updates')->only(['create', 'store']);
        $this->middleware('permission:edit_construction_updates')->only(['edit', 'update', 'destroyMedia']);
        $this->middleware('permission:delete_construction_updates')->only(['destroy']);
        $this->middleware('permission:edit_construction_updates')->only(['updateOverallProgress']);
    }
    public function index(Project $project)
    {
        $updates = $project->constructionUpdates()->with('stage')->orderBy('sort_order')->get();
        $stages = ConstructionStage::orderBy('sort_order')->orderBy('name')->get();

        // Reuse $updates (already stage-eager-loaded) for the weighted auto-calc.
        $project->setRelation('constructionUpdates', $updates);
        $autoProgress = $project->autoConstructionProgress();

        // Per-stage breakdown for the "Progress % × Weight % = Contribution %" table —
        // one row per stage in the master list, 0% progress for any stage with no update yet.
        $activeUpdatesByStage = $updates->where('is_active', true)->keyBy('construction_stage_id');
        $breakdown = $stages->map(function ($stage) use ($activeUpdatesByStage) {
            $progress = (float) ($activeUpdatesByStage->get($stage->id)?->progress_percentage ?? 0);
            $weight = (float) $stage->weight_percentage;
            return [
                'name' => $stage->name,
                'progress' => $progress,
                'weight' => $weight,
                'contribution' => $progress * $weight / 100,
            ];
        });

        return view('dashboard.realestate.constructionUpdates.index', compact('project', 'updates', 'stages', 'autoProgress', 'breakdown'));
    }

    /** Set or clear the manual override for the project's overall construction progress. */
    public function updateOverallProgress(Request $request, Project $project)
    {
        $request->validate([
            'overall_progress_override' => 'nullable|integer|min:0|max:100',
        ]);

        $project->update([
            'overall_progress_override' => $request->filled('overall_progress_override')
                ? $request->integer('overall_progress_override')
                : null,
        ]);

        return back()->with('success', 'Overall progress updated.');
    }

    /** Only one update per stage is allowed per project. */
    private function stageUniqueRule(Project $project, ?int $ignoreId = null): Unique
    {
        return Rule::unique('construction_updates', 'construction_stage_id')
            ->where(fn ($q) => $q->where('updatable_type', Project::class)->where('updatable_id', $project->id))
            ->whereNull('deleted_at')
            ->ignore($ignoreId);
    }

    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'progress_percentage' => 'nullable|numeric|min:0|max:100',
            'update_date' => 'nullable|date',
            'construction_stage_id' => [
                'required',
                'exists:construction_stages,id',
                $this->stageUniqueRule($project),
            ],
            'is_public' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:jpg,png,jpeg,webp,mp4,zip',
            'link' => 'nullable|url',
        ], [
            'construction_stage_id.unique' => 'This project already has an update for that stage. Edit the existing one instead.',
        ]);

        $data['is_public'] = $request->boolean('is_public');
        $data['is_active'] = $request->boolean('is_active', true);

        // A soft-deleted update may still occupy this stage's unique slot;
        // revive and overwrite it instead of inserting a new row.
        $update = ConstructionUpdate::onlyTrashed()
            ->where('updatable_type', Project::class)
            ->where('updatable_id', $project->id)
            ->where('construction_stage_id', $data['construction_stage_id'])
            ->first();

        if ($update) {
            $update->restore();
            $update->clearMediaCollection('updates');
            $update->update($data);
        } else {
            $update = $project->constructionUpdates()->create($data);
        }

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
            'progress_percentage' => 'nullable|numeric|min:0|max:100',
            'update_date' => 'nullable|date',
            'construction_stage_id' => [
                'required',
                'exists:construction_stages,id',
                $this->stageUniqueRule($project, $constructionUpdate->id),
            ],
            'is_public' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:jpg,png,jpeg,webp,mp4,zip',
            'link' => 'nullable|url',
        ], [
            'construction_stage_id.unique' => 'This project already has an update for that stage. Edit the existing one instead.',
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
