<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\BlogRequest;
use App\Models\Blog;
use App\Models\Tag;
use Illuminate\Http\Request;

class BlogsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_blogs')->only(['index', 'show']);
        $this->middleware('permission:create_blogs')->only(['create', 'store']);
        $this->middleware('permission:edit_blogs')->only(['edit', 'update']);
        $this->middleware('permission:delete_blogs')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::get();
        return view('dashboard.contentManagement.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::get();
        return view('dashboard.contentManagement.blogs.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogRequest $request)
    {
        // Create new Insight instance
        $blog = new Blog;
        $blog->title = $request->title;
        $blog->published_at = $request->published_at;
        $blog->short_description = $request->short_description;
        $blog->author = $request->author;
        $blog->description = $request->description; // Rich text
        $blog->is_active = $request->is_active ?? true;
        $blog->is_featured = $request->is_featured ?? '0';
        $blog->meta_title = $request->meta_title;
        $blog->meta_description = $request->meta_description;
        $blog->meta_keywords = $request->meta_keywords;
        $blog->user_id = auth()->user()->id;
        $blog->save();

        // Handle image upload if needed
        if ($request->hasFile('image')) {
            $blog->addMediaFromRequest('image')->toMediaCollection('images', 'blogFiles');
        }
        // 🔹 Attach tags (existing or new)
        $tagIds = [];
        if ($request->tags) {
            foreach ($request->tags as $tag) {
                $tagModel = Tag::firstOrCreate(['id' => $tag], ['name' => $tag]);
                $tagIds[] = $tagModel->id;
            }
        }
        $blog->tags()->sync($tagIds);

        return redirect()->route('blogs.index')
            ->with('status', 'success')
            ->with('message', 'Blog created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $blog = Blog::findorfail($id);
        return redirect()->route('singleBlog', $blog->slug);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $blog = Blog::findorfail($id);
        $tags = Tag::get();
        return view('dashboard.contentManagement.blogs.edit', compact('blog', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogRequest $request, string $id)
    {
        $blog = Blog::findorfail($id);
        $blog->title = $request->title;
        $blog->published_at = $request->published_at;
        $blog->short_description = $request->short_description;
        $blog->author = $request->author;
        $blog->description = $request->description; // Rich text
        $blog->is_active = $request->is_active;
        $blog->is_featured = $request->is_featured;
        $blog->meta_title = $request->meta_title;
        $blog->meta_description = $request->meta_description;
        $blog->meta_keywords = $request->meta_keywords;
        $blog->user_id = auth()->user()->id;
        $blog->save();
        // Handle image upload if needed
        if ($request->hasFile('image')) {
            $blog->clearMediaCollection('images');
            $blog->addMediaFromRequest('image')->toMediaCollection('images', 'blogFiles');
        }
        $tagIds = [];
        if ($request->tags) {
            foreach ($request->tags as $tag) {
                $tagModel = Tag::firstOrCreate(['id' => $tag], ['name' => $tag]);
                $tagIds[] = $tagModel->id;
            }
        }
        $blog->tags()->sync($tagIds);
        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Blog Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blog = Blog::findorfail($id);
        $blog->clearMediaCollection('images');
        $blog->delete();
        return redirect()->back()->with('status', 'success')->with('message', 'Blog deleted successfully.');
    }
}
