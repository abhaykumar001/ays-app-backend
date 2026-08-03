<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\KioskSlide;
use Illuminate\Http\Request;

class KioskSlidesController extends Controller
{
    public function index()
    {
        $slides = KioskSlide::orderBy('display_order')->orderBy('id')->get();
        return view('dashboard.contentManagement.kioskSlides.index', compact('slides'));
    }

    public function create()
    {
        return view('dashboard.contentManagement.kioskSlides.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'display_order' => 'nullable|integer|min:0',
            'is_active'     => 'nullable|boolean',
            'image'         => 'required|image|max:5120',
        ]);

        $slide = KioskSlide::create([
            'title'         => $request->title,
            'display_order' => $request->integer('display_order', 0),
            'is_active'     => $request->boolean('is_active', true),
        ]);

        $slide->addMediaFromRequest('image')->toMediaCollection('images');

        return redirect()->route('kioskSlides.index')
            ->with('status', 'success')
            ->with('message', 'Kiosk slide created.');
    }

    public function edit(KioskSlide $kioskSlide)
    {
        return view('dashboard.contentManagement.kioskSlides.edit', compact('kioskSlide'));
    }

    public function update(Request $request, KioskSlide $kioskSlide)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'display_order' => 'nullable|integer|min:0',
            'is_active'     => 'nullable|boolean',
            'image'         => 'nullable|image|max:5120',
        ]);

        $kioskSlide->update([
            'title'         => $request->title,
            'display_order' => $request->integer('display_order', 0),
            'is_active'     => $request->boolean('is_active', true),
        ]);

        if ($request->hasFile('image')) {
            $kioskSlide->clearMediaCollection('images');
            $kioskSlide->addMediaFromRequest('image')->toMediaCollection('images');
        }

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Kiosk slide updated.');
    }

    public function destroy(KioskSlide $kioskSlide)
    {
        $kioskSlide->clearMediaCollection('images');
        $kioskSlide->delete();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Kiosk slide deleted.');
    }
}
