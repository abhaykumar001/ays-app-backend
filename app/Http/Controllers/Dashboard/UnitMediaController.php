<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\unit;
use App\Models\VirtualTour;
use Illuminate\Http\Request;

class UnitMediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_unit_media')->only(['index', 'show']);
        $this->middleware('permission:create_unit_media')->only(['create', 'store']);
        $this->middleware('permission:edit_unit_media')->only(['edit', 'update']);
        $this->middleware('permission:delete_unit_media')->only(['destroy']);
    }
    public function index(unit $unit)
    {
        $unitMedia = $unit->unitMedia()->get();
        return view('dashboard.realestate.unitMedia.index', compact('unit', 'unitMedia'));
    }

    public function store(Request $request, unit $unit)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'type' => 'required|in:360,video,iframe,link',
            'url' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'file' => 'nullable|file|mimes:jpg,png,jpeg,mp4,zip',
        ]);

        $virtualTour = $unit->unitMedia()->create([
            'title' => $data['title'] ?? null,
            'type' => $data['type'],
            'url' => $data['url'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (!empty($data['file'])) {
            $virtualTour->addMedia($data['file'])->toMediaCollection('virtual_tours');
        }

        return back()->with('success', 'Virtual Tour saved.');
    }

    public function edit(unit $unit, VirtualTour $virtualTour)
    {
        // Include media URL if exists
        $virtualTour->file_url = $virtualTour->hasMedia('virtual_tours')
            ? $virtualTour->getFirstMediaUrl('virtual_tours') : null;
        return response()->json($virtualTour);
    }

    public function update(Request $request, unit $unit, VirtualTour $virtualTour)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'type' => 'required|in:360,video,iframe,link',
            'url' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'file' => 'nullable|file|mimes:jpg,png,jpeg,mp4,zip',
        ]);

        $virtualTour->update([
            'title' => $data['title'] ?? null,
            'type' => $data['type'],
            'url' => $data['url'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (!empty($data['file'])) {
            // Remove old media if exists
            $virtualTour->clearMediaCollection('virtual_tours');

            $virtualTour->addMedia($data['file'])->toMediaCollection('virtual_tours');
        }

        return back()->with('success', 'Virtual Tour updated.');
    }

    public function destroy(unit $unit, VirtualTour $virtualTour)
    {
        $virtualTour->clearMediaCollection('virtual_tours');
        $virtualTour->delete();

        return back()->with('success', 'Virtual Tour deleted.');
    }
}
