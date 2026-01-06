<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Amenity;
use App\Models\Community;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_projects')->only(['index', 'show']);
        $this->middleware('permission:create_projects')->only(['create', 'store']);
        $this->middleware('permission:edit_projects')->only(['edit', 'update']);
        $this->middleware('permission:delete_projects')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::orderby('id', 'desc')->get();
        return view('dashboard.realestate.projects.index', compact('projects'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $amenities = Amenity::active()->get();
        $accommodations = Accommodation::active()->get();
        $communities = Community::active()->get();
        return view('dashboard.realestate.projects.create', compact('amenities', 'accommodations', 'communities'));
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
