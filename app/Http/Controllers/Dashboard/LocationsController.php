<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_locations')->only(['index', 'show']);
        $this->middleware('permission:create_locations')->only(['create', 'store']);
        $this->middleware('permission:edit_locations')->only(['edit', 'update', 'toggleStatus', 'destroyMedia']);
        $this->middleware('permission:delete_locations')->only(['destroy']);
    }

    private function openingHoursRules(): array
    {
        return [
            'opening_hours'          => 'nullable|array',
            'opening_hours.monday'   => 'nullable|string|max:255',
            'opening_hours.tuesday'  => 'nullable|string|max:255',
            'opening_hours.wednesday' => 'nullable|string|max:255',
            'opening_hours.thursday' => 'nullable|string|max:255',
            'opening_hours.friday'   => 'nullable|string|max:255',
            'opening_hours.saturday' => 'nullable|string|max:255',
            'opening_hours.sunday'   => 'nullable|string|max:255',
        ];
    }

    public function index()
    {
        $locations = Location::orderBy('sort_order')->orderBy('id')->get();
        return view('dashboard.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('dashboard.locations.create');
    }

    public function store(Request $request)
    {
        $request->validate(array_merge([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'phone'       => 'nullable|string|max:255',
            'email'       => 'nullable|email|max:255',
            'website_url' => 'nullable|url|max:2048',
            'address'     => 'nullable|string|max:255',
            'latitude'    => 'nullable|numeric|between:-90,90',
            'longitude'   => 'nullable|numeric|between:-180,180',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
            'image'       => 'nullable|image|max:5120',
            'gallery'     => 'nullable|array|max:10',
            'gallery.*'   => 'nullable|image|max:5120',
        ], $this->openingHoursRules()));

        $location = Location::create([
            'title'         => $request->title,
            'description'   => $request->description,
            'phone'         => $request->phone ?: null,
            'email'         => $request->email ?: null,
            'website_url'   => $request->website_url ?: null,
            'opening_hours' => $request->input('opening_hours', []),
            'address'       => $request->address ?: null,
            'latitude'      => $request->latitude,
            'longitude'     => $request->longitude,
            'sort_order'    => $request->integer('sort_order', 0),
            'is_active'     => $request->boolean('is_active', true),
        ]);

        if ($request->hasFile('image')) {
            $location->addMediaFromRequest('image')->toMediaCollection('images');
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $location->addMedia($file)->toMediaCollection('images');
            }
        }

        return redirect()->route('locations.index')
            ->with('status', 'success')
            ->with('message', 'Location created.');
    }

    public function edit(Location $location)
    {
        return view('dashboard.locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $request->validate(array_merge([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'phone'       => 'nullable|string|max:255',
            'email'       => 'nullable|email|max:255',
            'website_url' => 'nullable|url|max:2048',
            'address'     => 'nullable|string|max:255',
            'latitude'    => 'nullable|numeric|between:-90,90',
            'longitude'   => 'nullable|numeric|between:-180,180',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
            'image'       => 'nullable|image|max:5120',
            'gallery'     => 'nullable|array|max:10',
            'gallery.*'   => 'nullable|image|max:5120',
        ], $this->openingHoursRules()));

        $location->update([
            'title'         => $request->title,
            'description'   => $request->description,
            'phone'         => $request->phone ?: null,
            'email'         => $request->email ?: null,
            'website_url'   => $request->website_url ?: null,
            'opening_hours' => $request->input('opening_hours', []),
            'address'       => $request->address ?: null,
            'latitude'      => $request->latitude,
            'longitude'     => $request->longitude,
            'sort_order'    => $request->integer('sort_order', 0),
            'is_active'     => $request->boolean('is_active', true),
        ]);

        if ($request->hasFile('image')) {
            // Only remove the current main image (first item in the shared
            // 'images' collection), not the whole collection — clearing it
            // here would wipe the gallery too.
            $location->getFirstMedia('images')?->delete();
            $newMainImage = $location->addMediaFromRequest('image')->toMediaCollection('images');
            $newMainImage->order_column = 0;
            $newMainImage->save();
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $location->addMedia($file)->toMediaCollection('images');
            }
        }

        return redirect()->route('locations.index')
            ->with('status', 'success')
            ->with('message', 'Location updated.');
    }

    public function destroy(Location $location)
    {
        $location->clearMediaCollection('images');
        $location->delete();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Location deleted.');
    }

    public function destroyMedia(Location $location, int $media)
    {
        $mediaItem = $location->media()->where('id', $media)->firstOrFail();
        $mediaItem->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('status', 'success')->with('message', 'File removed successfully.');
    }

    public function toggleStatus(string $id)
    {
        $location = Location::findOrFail($id);

        $location->is_active = ! $location->is_active;
        $location->save();

        $status = $location->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', "Location {$status} successfully.");
    }
}
