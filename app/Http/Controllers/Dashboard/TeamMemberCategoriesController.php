<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TeamMemberCategory;
use Illuminate\Http\Request;

class TeamMemberCategoriesController extends Controller
{
    public function index(Request $request)
    {
        $categories = TeamMemberCategory::withCount('teamMembers')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $editCategory = $request->filled('edit')
            ? TeamMemberCategory::find($request->integer('edit'))
            : null;

        return view('dashboard.contentManagement.teamMemberCategories.index', compact('categories', 'editCategory'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255|unique:team_member_categories,name',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        TeamMemberCategory::create([
            'name'       => $request->name,
            'sort_order' => $request->integer('sort_order', 0),
        ]);

        return redirect()->route('teamMemberCategories.index')
            ->with('status', 'success')
            ->with('message', 'Category created.');
    }

    public function update(Request $request, TeamMemberCategory $teamMemberCategory)
    {
        $request->validate([
            'name'       => 'required|string|max:255|unique:team_member_categories,name,' . $teamMemberCategory->id,
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $teamMemberCategory->update([
            'name'       => $request->name,
            'sort_order' => $request->integer('sort_order', 0),
        ]);

        return redirect()->route('teamMemberCategories.index')
            ->with('status', 'success')
            ->with('message', 'Category updated.');
    }

    public function destroy(TeamMemberCategory $teamMemberCategory)
    {
        if ($teamMemberCategory->teamMembers()->exists()) {
            return redirect()->route('teamMemberCategories.index')
                ->with('status', 'error')
                ->with('message', 'Cannot delete a category that still has team members assigned to it.');
        }

        $teamMemberCategory->delete();

        return redirect()->route('teamMemberCategories.index')
            ->with('status', 'success')
            ->with('message', 'Category deleted.');
    }

    public function addNewCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $category = TeamMemberCategory::firstOrCreate(
            ['name' => $request->name],
            ['sort_order' => TeamMemberCategory::max('sort_order') + 1]
        );

        return response()->json([
            'newCategory'    => $category,
            'allCategories'  => TeamMemberCategory::orderBy('sort_order')->orderBy('name')->get(['id', 'name']),
        ]);
    }
}
