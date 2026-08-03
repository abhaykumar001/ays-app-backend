<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectOffer;
use Illuminate\Http\Request;

class ProjectOffersController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_project_offers')->only(['index', 'show']);
        $this->middleware('permission:create_project_offers')->only(['create', 'store']);
        $this->middleware('permission:edit_project_offers')->only(['edit', 'update']);
        $this->middleware('permission:delete_project_offers')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        $offers = $project->offers()
            ->withCount('offerUnits')
            ->orderByDesc('id')
            ->get();

        return view(
            'dashboard.realestate.projectOffers.index',
            compact('project', 'offers')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Project $project)
    {
        $units = $project->units()->orderBy('title')->get();

        return view(
            'dashboard.realestate.projectOffers.create',
            compact('project', 'units')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'required|in:exclusive,payment_plan,investment',
            'units'       => 'nullable|array',
            'units.*.included' => 'nullable|boolean',
            'units.*.price'    => 'nullable|numeric|min:0',
        ]);

        $offer = $project->offers()->create([
            'title'       => $request->title,
            'description' => $request->description ?: null,
            'category'    => $request->category,
            'is_active'   => $request->boolean('is_active', true),
            'sort_order'  => $request->integer('sort_order', 0),
        ]);

        $this->syncOfferUnits($offer, $request->input('units', []));

        return redirect()->route('projects.projectOffers.index', $project)
            ->with('status', 'success')
            ->with('message', 'Offer created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project, ProjectOffer $projectOffer)
    {
        $units = $project->units()->orderBy('title')->get();
        $offerUnits = $projectOffer->offerUnits->keyBy('unit_id');

        return view(
            'dashboard.realestate.projectOffers.edit',
            compact('project', 'projectOffer', 'units', 'offerUnits')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project, ProjectOffer $projectOffer)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'required|in:exclusive,payment_plan,investment',
            'units'       => 'nullable|array',
            'units.*.included' => 'nullable|boolean',
            'units.*.price'    => 'nullable|numeric|min:0',
        ]);

        $projectOffer->update([
            'title'       => $request->title,
            'description' => $request->description ?: null,
            'category'    => $request->category,
            'is_active'   => $request->boolean('is_active', true),
            'sort_order'  => $request->integer('sort_order', 0),
        ]);

        $projectOffer->offerUnits()->delete();
        $this->syncOfferUnits($projectOffer, $request->input('units', []));

        return redirect()->route('projects.projectOffers.index', $project)
            ->with('status', 'success')
            ->with('message', 'Offer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, ProjectOffer $projectOffer)
    {
        $projectOffer->delete();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Offer deleted successfully.');
    }

    /**
     * Create ProjectOfferUnit rows for every unit marked as included.
     */
    private function syncOfferUnits(ProjectOffer $offer, array $units): void
    {
        foreach ($units as $unitId => $data) {
            if (empty($data['included'])) {
                continue;
            }

            $offer->offerUnits()->create([
                'unit_id' => $unitId,
                'price'   => $data['price'] !== '' && $data['price'] !== null ? $data['price'] : null,
            ]);
        }
    }
}
