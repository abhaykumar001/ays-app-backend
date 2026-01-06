<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Amenity;
use App\Models\Project;
use Illuminate\Http\Request;

class UnitsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_units')->only(['index', 'show']);
        $this->middleware('permission:create_units')->only(['create', 'store']);
        $this->middleware('permission:edit_units')->only(['edit', 'update']);
        $this->middleware('permission:delete_units')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        $units = $project->units()
            ->orderByDesc('id')
            ->get();

        return view(
            'dashboard.realestate.units.index',
            compact('project', 'units')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
   public function create(Project $project)
    {
        $amenities = Amenity::active()->get();
        $accommodations = Accommodation::active()->get();
        $phases = $project->phases()->get();

        return view(
            'dashboard.realestate.units.create',
            compact('project', 'amenities', 'accommodations', 'phases')
        );
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
