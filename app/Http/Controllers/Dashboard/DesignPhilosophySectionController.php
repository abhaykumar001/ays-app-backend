<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DesignPhilosophy;
use App\Models\DesignPhilosophySection;
use Illuminate\Http\Request;

class DesignPhilosophySectionController extends Controller
{
    public function create()
    {
        $philosophy = DesignPhilosophy::singleton();
        return view('dashboard.designPhilosophy.sections.create', compact('philosophy'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order'  => 'nullable|integer|min:0',
            'images.*'    => 'nullable|image|max:5120',
        ]);

        $philosophy = DesignPhilosophy::singleton();

        $section = $philosophy->allSections()->create([
            'title'       => $request->title,
            'description' => $request->description ?: null,
            'sort_order'  => $request->sort_order ?? 0,
            'is_active'   => true,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $section->addMedia($file)->toMediaCollection('images');
            }
        }

        return redirect()->route('design-philosophy.edit')
            ->with('status', 'success')
            ->with('message', 'Section added successfully.');
    }

    public function edit(DesignPhilosophySection $section)
    {
        return view('dashboard.designPhilosophy.sections.edit', compact('section'));
    }

    public function update(Request $request, DesignPhilosophySection $section)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order'  => 'nullable|integer|min:0',
            'images.*'    => 'nullable|image|max:5120',
        ]);

        $section->update([
            'title'       => $request->title,
            'description' => $request->description ?: null,
            'sort_order'  => $request->sort_order ?? $section->sort_order,
            'is_active'   => $request->has('is_active'),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $section->addMedia($file)->toMediaCollection('images');
            }
        }

        return redirect()->route('design-philosophy.sections.edit', $section)
            ->with('status', 'success')
            ->with('message', 'Section saved successfully.');
    }

    public function destroy(DesignPhilosophySection $section)
    {
        $section->clearMediaCollection('images');
        $section->delete();

        return redirect()->route('design-philosophy.edit')
            ->with('status', 'success')
            ->with('message', 'Section deleted.');
    }

    public function destroyMedia(DesignPhilosophySection $section, int $mediaId)
    {
        $media = $section->getMedia('images')->firstWhere('id', $mediaId);
        $media?->delete();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Image removed.');
    }
}
