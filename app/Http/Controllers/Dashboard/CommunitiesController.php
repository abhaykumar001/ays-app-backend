<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Community;
use Illuminate\Http\Request;

class CommunitiesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_communities')->only(['index', 'show']);
        $this->middleware('permission:create_communities')->only(['create', 'store']);
        $this->middleware('permission:edit_communities')->only(['edit', 'update']);
        $this->middleware('permission:delete_communities')->only(['destroy']);
    }

    public function index()
    {
        $communities = Community::orderby('id', 'desc')->get();
        return view('dashboard.realestate.communities.index', compact('communities'));
    }

    public function create()
    {
        $amenities = Amenity::active()->get();
        return view('dashboard.realestate.communities.create', compact('amenities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'city'              => 'required|string|max:255',
            'short_description' => 'required|string',
            'description'       => 'nullable|string',
            'image'             => 'required|file|image|max:5120',
            'amenities'         => 'nullable|array',
            'amenities.*'       => 'exists:amenities,id',
        ]);

        $community = Community::create([
            'name'              => $request->name,
            'city'              => $request->city,
            'short_description' => $request->short_description,
            'description'       => $request->description ?: null,
            'starting_price'    => $request->starting_price,
            'growth'            => $request->growth,
            'roi'               => $request->roi,
            'category'          => $request->category,
            'is_featured'       => $request->is_featured ?? 0,
            'sort_order'        => $request->sort_order ?? 0,
            'is_active'         => $request->status === 'active',
            'address'           => $request->address,
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude,
            'meta_title'        => $request->meta_title,
            'meta_keywords'     => $request->meta_keywords,
            'meta_description'  => $request->meta_description,
            'user_id'           => auth()->id(),
        ]);

        if ($request->filled('amenities')) {
            $community->amenities()->sync($request->amenities);
        }

        if ($request->hasFile('image')) {
            $community->addMediaFromRequest('image')->toMediaCollection('images');
        }
        if ($request->hasFile('video')) {
            $community->addMediaFromRequest('video')->toMediaCollection('videos');
        }

        return redirect()->route('communities.index')
            ->with('status', 'success')
            ->with('message', 'Community created successfully.');
    }

    public function show(string $id)
    {
        $community = Community::with(['nearbyPlaces'])->findOrFail($id);
        return view('dashboard.realestate.communities.show', compact('community'));
    }

    public function edit(string $id)
    {
        $community = Community::with('amenities')->findOrFail($id);
        $amenities = Amenity::active()->get();
        return view('dashboard.realestate.communities.edit', compact('community', 'amenities'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'city'              => 'required|string|max:255',
            'short_description' => 'required|string',
            'description'       => 'nullable|string',
            'amenities'         => 'nullable|array',
            'amenities.*'       => 'exists:amenities,id',
        ]);

        $community = Community::findOrFail($id);

        $community->update([
            'name'              => $request->name,
            'city'              => $request->city,
            'short_description' => $request->short_description,
            'description'       => $request->description ?: null,
            'starting_price'    => $request->starting_price,
            'growth'            => $request->growth,
            'roi'               => $request->roi,
            'category'          => $request->category,
            'is_featured'       => $request->is_featured ?? 0,
            'sort_order'        => $request->sort_order ?? 0,
            'is_active'         => $request->status === 'active',
            'address'           => $request->address,
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude,
            'meta_title'        => $request->meta_title,
            'meta_keywords'     => $request->meta_keywords,
            'meta_description'  => $request->meta_description,
            'user_id'           => auth()->id(),
        ]);

        $community->amenities()->sync($request->amenities ?? []);

        if ($request->hasFile('image')) {
            $community->clearMediaCollection('images');
            $community->addMediaFromRequest('image')->toMediaCollection('images');
        }
        if ($request->hasFile('video')) {
            $community->clearMediaCollection('videos');
            $community->addMediaFromRequest('video')->toMediaCollection('videos');
        }

        return redirect()->route('communities.index')
            ->with('status', 'success')
            ->with('message', 'Community updated successfully.');
    }

    public function destroy(string $id)
    {
        $community = Community::findOrFail($id);
        $community->clearMediaCollection('images');
        $community->clearMediaCollection('videos');
        $community->delete();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Community deleted successfully.');
    }
}
