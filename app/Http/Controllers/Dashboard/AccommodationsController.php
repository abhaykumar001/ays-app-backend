<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use Illuminate\Http\Request;

class AccommodationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_accommodations')->only(['index', 'show']);
        $this->middleware('permission:create_accommodations')->only(['create', 'store']);
        $this->middleware('permission:edit_accommodations')->only(['edit', 'update']);
        $this->middleware('permission:delete_accommodations')->only(['destroy']);
    }

    public function index()
    {
        $accommodations = Accommodation::orderBy('id', 'desc')->get();
        return view('dashboard.realestate.accommodations.index', compact('accommodations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'nullable|string',
            'logo'   => 'nullable|image|max:5120',
        ]);

        $accommodation = Accommodation::create([
            'name'      => $request->name,
            'is_active' => $request->status === 'true',
        ]);

        if ($request->hasFile('logo')) {
            $accommodation->addMediaFromRequest('logo')->toMediaCollection('icons');
        }

        return redirect()->route('accommodations.index')
            ->with('status', 'success')
            ->with('message', 'Accommodation created successfully.');
    }

    public function edit(string $id)
    {
        $accommodation = Accommodation::findOrFail($id);
        return response()->json([
            'name'      => $accommodation->name,
            'is_active' => $accommodation->is_active,
            'logo'      => $accommodation->getFirstMediaUrl('icons') ?: null,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'nullable|string',
            'logo'   => 'nullable|image|max:5120',
        ]);

        $accommodation = Accommodation::findOrFail($id);
        $accommodation->update([
            'name'      => $request->name,
            'is_active' => $request->status === 'true',
        ]);

        if ($request->hasFile('logo')) {
            $accommodation->clearMediaCollection('icons');
            $accommodation->addMediaFromRequest('logo')->toMediaCollection('icons');
        }

        return redirect()->route('accommodations.index')
            ->with('status', 'success')
            ->with('message', 'Accommodation updated successfully.');
    }

    public function destroy(string $id)
    {
        $accommodation = Accommodation::findOrFail($id);
        $accommodation->clearMediaCollection('icons');
        $accommodation->delete();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Accommodation deleted successfully.');
    }
}
