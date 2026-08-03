<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Maintanance;
use Illuminate\Http\Request;

class MaintananceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_maintanance')->only(['index', 'show']);
        $this->middleware('permission:create_maintanance')->only(['create', 'store']);
        $this->middleware('permission:edit_maintanance')->only(['edit', 'update']);
        $this->middleware('permission:delete_maintanance')->only(['destroy']);
    }

    public function index()
    {
        $maintanances = Maintanance::orderBy('id', 'desc')->get();
        return view('dashboard.propertyManagement.maintanance.index', compact('maintanances'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                 => 'required|string|max:255',
            'description'          => 'nullable|string',
            'default_cost'         => 'nullable|numeric|min:0',
            'estimated_duration'   => 'nullable|string|max:255',
            'required_materials'   => 'nullable|string',
            'special_instructions' => 'nullable|string',
            'is_active'            => 'nullable',
            'image'                => 'nullable|image|max:5120',
        ]);

        $maintanance = Maintanance::create([
            'name'                 => $request->name,
            'description'          => $request->description,
            'default_cost'         => $request->default_cost ?: null,
            'estimated_duration'   => $request->estimated_duration,
            'required_materials'   => $request->required_materials,
            'special_instructions' => $request->special_instructions,
            'is_active'            => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN),
            'sort_order'           => (int) ($request->sort_order ?? 0),
        ]);

        if ($request->hasFile('image')) {
            $maintanance->addMediaFromRequest('image')->toMediaCollection('images');
        }

        return redirect()->route('maintanance.index')
            ->with('status', 'success')
            ->with('message', 'Maintenance service created successfully.');
    }

    public function edit(string $id)
    {
        $maintanance = Maintanance::findOrFail($id);
        return response()->json([
            'name'                 => $maintanance->name,
            'description'          => $maintanance->description,
            'default_cost'         => $maintanance->default_cost,
            'estimated_duration'   => $maintanance->estimated_duration,
            'required_materials'   => $maintanance->required_materials,
            'special_instructions' => $maintanance->special_instructions,
            'is_active'            => $maintanance->is_active,
            'image'                => $maintanance->getFirstMediaUrl('images') ?: null,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'                 => 'required|string|max:255',
            'description'          => 'nullable|string',
            'default_cost'         => 'nullable|numeric|min:0',
            'estimated_duration'   => 'nullable|string|max:255',
            'required_materials'   => 'nullable|string',
            'special_instructions' => 'nullable|string',
            'is_active'            => 'nullable',
            'image'                => 'nullable|image|max:5120',
        ]);

        $maintanance = Maintanance::findOrFail($id);
        $maintanance->update([
            'name'                 => $request->name,
            'description'          => $request->description,
            'default_cost'         => $request->default_cost ?: null,
            'estimated_duration'   => $request->estimated_duration,
            'required_materials'   => $request->required_materials,
            'special_instructions' => $request->special_instructions,
            'is_active'            => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN),
        ]);

        if ($request->hasFile('image')) {
            $maintanance->clearMediaCollection('images');
            $maintanance->addMediaFromRequest('image')->toMediaCollection('images');
        }

        return redirect()->route('maintanance.index')
            ->with('status', 'success')
            ->with('message', 'Maintenance service updated successfully.');
    }

    public function destroy(string $id)
    {
        $maintanance = Maintanance::findOrFail($id);
        $maintanance->clearMediaCollection('images');
        $maintanance->delete();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Maintenance service deleted successfully.');
    }
}
