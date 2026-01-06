<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_tags')->only(['index', 'show']);
        $this->middleware('permission:create_tags')->only(['create', 'store']);
        $this->middleware('permission:edit_tags')->only(['edit', 'update']);
        $this->middleware('permission:delete_tags')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = tag::latest()->paginate(10);
        return view('dashboard.contentManagement.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.contentManagement.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate basic input
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,NULL,id,deleted_at,NULL',
        ]);

        // Normalize name input
        $name = $request->name;

        // Check if a tag (even soft-deleted) exists with the same name
        $existingTag = Tag::withTrashed()
            ->where('name', $name)
            ->first();

        if ($existingTag) {
            if ($existingTag->trashed()) {
                // Restore if soft-deleted
                $existingTag->restore();
                return redirect()->back()->with('status', 'success')->with('message', 'Tag restored successfully!');
            } else {
                // If already exists and not deleted
                return redirect()->back()->with('status', 'error')->with('message', 'Tag Already Exist!');
            }
        }
        // Create new tag if not exists
        Tag::create([
            'name' => $name,
        ]);

        return redirect()->back()->with('status', 'success')->with('message', 'Tag created successfully!');
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
    public function edit(string $id)
    {
        $editTag = Tag::findOrFail($id);
        $tags = Tag::latest()->paginate(10);
        return view('dashboard.contentManagement.tags.index', compact('tags', 'editTag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tag = Tag::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id . ',id,deleted_at,NULL',
        ]);


        $tag->update([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('status', 'success')->with('message', 'Tag updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return redirect()->back()->with('status', 'success')->with('message', 'Tag deleted successfully!');
    }
    public function addNewTag(Request $request)
    {
        $tag = Tag::firstOrCreate(['name' => $request->name]);
        $tags = Tag::all(['id', 'name']); // get all tags

        return response()->json([
            'newTag' => $tag,
            'allTags' => $tags
        ]);
    }
}

