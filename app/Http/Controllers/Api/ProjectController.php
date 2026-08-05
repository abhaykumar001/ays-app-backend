<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConstructionUpdateResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\UnitResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Agents see both active and inactive projects; everyone else
     * (guests, Client, Owner) only sees active projects.
     */
    private function visibleProjects(Request $request)
    {
        $role = $request->user('sanctum')?->getRoleNames()->first();

        return $role === 'Agent' ? Project::query() : Project::active();
    }

    /**
     * List all active projects.
     *
     * Query params:
     *   status  = ready | off_plan | under_construction
     *   search  = string (matches name or city)
     *   per_page = int (default 20)
     */
    public function index(Request $request): JsonResponse
    {
        $query = $this->visibleProjects($request)
            ->with(['amenities', 'community', 'accommodations'])
            ->orderBy('sort_order')
            ->orderByDesc('is_featured');

        // Filter by community
        if ($request->filled('community_id')) {
            $query->where('community_id', $request->community_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('project_status', $request->status);
        }

        // Search by name or city
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('city', 'like', "%{$term}%");
            });
        }

        $projects   = $query->paginate($request->integer('per_page', 20));
        $collection = ProjectResource::collection($projects->items());

        return response()->json([
            'success' => true,
            'data'    => $collection,
            'meta'    => [
                'current_page' => $projects->currentPage(),
                'last_page'    => $projects->lastPage(),
                'per_page'     => $projects->perPage(),
                'total'        => $projects->total(),
            ],
        ]);
    }

    /**
     * Return a single project with its units and amenities.
     */
    public function show(Request $request, string $slug): JsonResponse
    {
        $project = $this->visibleProjects($request)
            ->with([
                'amenities', 'community', 'accommodations', 'units.amenities', 'units.paymentPlans.milestones',
                'paymentPlans' => fn($q) => $q->where('is_active', true)->orderBy('id'),
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        // Wire the inverse relation so milestone amount calculation doesn't re-query per plan.
        foreach ($project->units as $unit) {
            foreach ($unit->paymentPlans as $plan) {
                $plan->setRelation('unit', $unit);
            }
        }

        return response()->json([
            'success' => true,
            'data'    => new ProjectResource($project, withUnits: true),
        ]);
    }

    /**
     * Return public construction updates for a project.
     */
    public function constructionUpdates(Request $request, string $slug): JsonResponse
    {
        $project = $this->visibleProjects($request)
            ->where('slug', $slug)
            ->firstOrFail();

        $updates = $project->constructionUpdates()
            ->where('is_active', true)
            ->orderByDesc('update_date')
            ->orderByDesc('sort_order')
            ->get();

        // Collect all media across all updates for the gallery; fall back to the project thumbnail
        $galleryFromUpdates = $updates->flatMap(fn($u) => $u->getMedia('updates')->map(fn($m) => $m->getUrl()))->values()->all();
        $projectThumb = $project->getFirstMediaUrl('images', 'resize') ?: $project->getFirstMediaUrl('images');
        $gallery = !empty($galleryFromUpdates) ? $galleryFromUpdates : ($projectThumb ? [$projectThumb] : []);

        // Most recent update date for display
        $lastUpdate = $updates->first()?->update_date;

        // Current stage = most recent update's stage
        $currentStage = $updates->first()?->stage;

        // Use the most recent update's progress_percentage; fall back to the project-level field
        $overallProgress = (int) (
            $updates->first()?->progress_percentage
            ?? $project->construction_progress
            ?? 0
        );

        return response()->json([
            'success' => true,
            'data'    => [
                'project_name'     => $project->name,
                'overall_progress' => $overallProgress,
                'last_updated'     => $lastUpdate
                    ? \Carbon\Carbon::parse($lastUpdate)->format('F j, Y')
                    : null,
                'current_stage'    => $currentStage,
                'gallery'          => $gallery,
                'updates'          => $updates->map(fn($u) => (new ConstructionUpdateResource($u))->resolve())->values(),
            ],
        ]);
    }

    /**
     * Return all units for a project (useful for inventory tab).
     */
    public function units(Request $request, string $slug): JsonResponse
    {
        $project = $this->visibleProjects($request)
            ->with(['units.amenities', 'units.paymentPlans.milestones', 'amenities'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Wire the inverse relation so milestone amount calculation doesn't re-query per plan.
        foreach ($project->units as $unit) {
            foreach ($unit->paymentPlans as $plan) {
                $plan->setRelation('unit', $unit);
            }
        }

        return response()->json([
            'success' => true,
            'data'    => UnitResource::collection(
                $project->units,
                // pass project amenities as fallback when unit has none
            ),
        ]);
    }
}
