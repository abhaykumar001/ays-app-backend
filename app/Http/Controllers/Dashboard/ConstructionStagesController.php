<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ConstructionStage;
use Illuminate\Http\Request;

class ConstructionStagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_construction_updates')->only(['index']);
        $this->middleware('permission:edit_construction_updates')->only(['store', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        $stages = ConstructionStage::withCount('constructionUpdates')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $totalWeight = $stages->sum('weight_percentage');

        $editStage = $request->filled('edit')
            ? ConstructionStage::find($request->integer('edit'))
            : null;

        return view('dashboard.realestate.constructionStages.index', compact('stages', 'editStage', 'totalWeight'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255|unique:construction_stages,name',
            'sort_order'        => 'nullable|integer|min:0',
            'weight_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        ConstructionStage::create([
            'name'              => $request->name,
            'sort_order'        => $request->integer('sort_order', 0),
            'weight_percentage' => $request->filled('weight_percentage') ? $request->weight_percentage : 0,
        ]);

        return redirect()->route('constructionStages.index')
            ->with('status', 'success')
            ->with('message', 'Stage created.');
    }

    public function update(Request $request, ConstructionStage $constructionStage)
    {
        $request->validate([
            'name'              => 'required|string|max:255|unique:construction_stages,name,' . $constructionStage->id,
            'sort_order'        => 'nullable|integer|min:0',
            'weight_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $constructionStage->update([
            'name'              => $request->name,
            'sort_order'        => $request->integer('sort_order', 0),
            'weight_percentage' => $request->filled('weight_percentage') ? $request->weight_percentage : 0,
        ]);

        return redirect()->route('constructionStages.index')
            ->with('status', 'success')
            ->with('message', 'Stage updated.');
    }

    public function destroy(ConstructionStage $constructionStage)
    {
        if ($constructionStage->constructionUpdates()->exists()) {
            return redirect()->route('constructionStages.index')
                ->with('status', 'error')
                ->with('message', 'Cannot delete a stage that still has construction updates assigned to it.');
        }

        $constructionStage->delete();

        return redirect()->route('constructionStages.index')
            ->with('status', 'success')
            ->with('message', 'Stage deleted.');
    }
}
