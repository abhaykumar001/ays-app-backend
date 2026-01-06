<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\NearbyPlace;
use Illuminate\Http\Request;

class NearbyPlacesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_nearby_places')->only(['index', 'show']);
        $this->middleware('permission:create_nearby_places')->only(['create', 'store']);
        $this->middleware('permission:edit_nearby_places')->only(['edit', 'update']);
        $this->middleware('permission:delete_nearby_places')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Community $community)
    {
        $nearbyPlaces = $community->nearbyPLaces()->orderby('id', 'desc')->get();
        return view('dashboard.realestate.nearbyPlaces.index', compact('nearbyPlaces', 'community'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Community $community)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'distance_km' => 'required|string|max:255',
        ]);

        $community->nearbyPlaces()->create($data);

        return redirect()->back()->with('success', 'Nearby place added successfully');
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
    public function edit(Community $community, NearbyPlace $nearbyPlace)
    {
        return response()->json($nearbyPlace);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        Community $community,
        NearbyPlace $nearbyPlace
    ) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'distance_km' => 'required|string|max:255',
        ]);

        $nearbyPlace->update($data);

        return redirect()->back()->with('success', 'Nearby place updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Community $community, NearbyPlace $nearbyPlace)
    {
        $nearbyPlace->delete();

        return redirect()->back()->with('success', 'Nearby place deleted successfully');
    }
}
