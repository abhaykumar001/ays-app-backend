<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\AmenityRequest;
use App\Models\Amenity;
use Illuminate\Http\Request;

class AmenitiesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_amenities')->only(['index', 'show']);
        $this->middleware('permission:create_amenities')->only(['create', 'store']);
        $this->middleware('permission:edit_amenities')->only(['edit', 'update']);
        $this->middleware('permission:delete_amenities')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $amenities = Amenity::orderby('id', 'desc')->get();
        return view('dashboard.realestate.amenities.index', compact('amenities'));
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
    public function store(AmenityRequest $request)
    {
        $amenity = new Amenity;
        $amenity->name = $request->name;
        $amenity->description = $request->description;
        $amenity->status = $request->status;
        $amenity->user_id = auth()->user()->id;
        $amenity->save();

        if ($request->hasFile('image')) {
            $amenity->addMediaFromRequest('image')->toMediaCollection('images', 'amenityFiles');
        }
        if ($request->hasFile('logo')) {
            $amenity->addMediaFromRequest('logo')->toMediaCollection('logos', 'amenityFiles');
        }

        return redirect()->route('amenities.index')
            ->with('status', 'success')
            ->with('message', 'Amenity created successfully.');
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
        $amenity = Amenity::findorfail($id);
        return response()->json([
            'name' => $amenity->name,
            'description' => $amenity->description,
            'is_active' => $amenity->is_active,
            'image' => $amenity->getFirstMediaUrl('images', 'webp'), // full URL if exists
            'logo' => $amenity->getFirstMediaUrl('logos'), // full URL if exists
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $amenity = Amenity::findorfail($id);
        $amenity->name = $request->name;
        $amenity->description = $request->description;
        $amenity->is_active = $request->is_active;
        $amenity->user_id = auth()->user()->id;
        $amenity->save();

        if ($request->hasFile('image')) {
            $amenity->clearMediaCollection('images');
            $amenity->addMediaFromRequest('image')->toMediaCollection('images', 'amenityFiles');
        }
        if ($request->hasFile('logo')) {
            $amenity->clearMediaCollection('logos');
            $amenity->addMediaFromRequest('logo')->toMediaCollection('logos', 'amenityFiles');
        }

        return redirect()->route('amenities.index')
            ->with('status', 'success')
            ->with('message', 'Amenity detail updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $amenity = Amenity::findorfail($id);
        $amenity->clearMediaCollection('images');
        $amenity->clearMediaCollection('logos');
        $amenity->delete();
        return redirect()->back()->with('status', 'success')->with('message', 'Amenity deleted successfully.');
    }
}
