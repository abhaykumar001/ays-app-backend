<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Highlight;
use App\Models\Project;
use Illuminate\Http\Request;

class HighlightsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_highlights')->only(['index', 'show']);
        $this->middleware('permission:create_highlights')->only(['create', 'store']);
        $this->middleware('permission:edit_highlights')->only(['edit', 'update']);
        $this->middleware('permission:delete_highlights')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        $highlights = $project->highlights()->orderby('id', 'desc')->get();
        return view('dashboard.realestate.highlights.index', compact('highlights', 'project'));
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
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
            'image'       => 'nullable|image|max:5120',
        ]);

        $highlight = $project->highlights()->create([
            'title'       => $request->title,
            'description' => $request->description,
            'is_featured' => $request->boolean('is_featured'),
            'sort_order'  => (int) ($request->sort_order ?? 0),
            'is_active'   => $request->boolean('is_active', true),
        ]);

        if ($request->hasFile('image')) {
            $highlight->addMediaFromRequest('image')->toMediaCollection('images');
        }

        return redirect()->back()->with('success', 'Project Highlight added successfully');
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
    public function edit(Project $project, Highlight $highlight)
    {
        return response()->json([
            'title' => $highlight->title,
            'description' => $highlight->description,
            'is_featured' => $highlight->is_featured,
            'sort_order' => $highlight->sort_order,
            'is_active' => $highlight->is_active,
            'image' => $highlight->hasMedia('images')
            ? $highlight->getFirstMediaUrl('images', 'webp')
            : null, // full URL if exists
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        Project $project,
        Highlight $highlight
    ) {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
            'image'       => 'nullable|image|max:5120',
        ]);

        $highlight->update([
            'title'       => $request->title,
            'description' => $request->description,
            'is_featured' => $request->boolean('is_featured'),
            'sort_order'  => (int) ($request->sort_order ?? 0),
            'is_active'   => $request->boolean('is_active', true),
        ]);

        if ($request->hasFile('image')) {
            $highlight->clearMediaCollection('images');
            $highlight->addMediaFromRequest('image')->toMediaCollection('images');
        }

        return redirect()->back()->with('success', 'Project Highlight updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, Highlight $highlight)
    {
        $highlight->delete();

        return redirect()->back()->with('success', 'Project Highlight deleted successfully');
    }
}
