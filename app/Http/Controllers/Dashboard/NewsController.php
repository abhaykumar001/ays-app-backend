<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::latest()->get();
        return view('dashboard.contentManagement.news.index', compact('news'));
    }

    public function create()
    {
        return view('dashboard.contentManagement.news.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'             => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'author'            => 'nullable|string|max:255',
            'published_at'      => 'nullable|date',
            'is_featured'       => 'nullable|boolean',
            'is_active'         => 'nullable|boolean',
            'image'             => 'nullable|image|max:5120',
        ]);

        $article = new News();
        $article->title             = $request->title;
        $article->short_description = $request->short_description;
        $article->description       = $request->description;
        $article->author            = $request->author;
        $article->published_at      = $request->published_at;
        $article->is_featured       = $request->boolean('is_featured');
        $article->is_active         = $request->boolean('is_active', true);
        $article->user_id           = auth()->id();
        $article->save();

        if ($request->hasFile('image')) {
            $article->addMediaFromRequest('image')->toMediaCollection('images');
        }

        return redirect()->route('news.index')
            ->with('status', 'success')
            ->with('message', 'News article created successfully.');
    }

    public function edit(News $news)
    {
        return view('dashboard.contentManagement.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'title'             => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'author'            => 'nullable|string|max:255',
            'published_at'      => 'nullable|date',
            'is_featured'       => 'nullable|boolean',
            'is_active'         => 'nullable|boolean',
            'image'             => 'nullable|image|max:5120',
        ]);

        $news->title             = $request->title;
        $news->short_description = $request->short_description;
        $news->description       = $request->description;
        $news->author            = $request->author;
        $news->published_at      = $request->published_at;
        $news->is_featured       = $request->boolean('is_featured');
        $news->is_active         = $request->boolean('is_active', true);
        $news->save();

        if ($request->hasFile('image')) {
            $news->clearMediaCollection('images');
            $news->addMediaFromRequest('image')->toMediaCollection('images');
        }

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'News article updated successfully.');
    }

    public function destroy(News $news)
    {
        $news->clearMediaCollection('images');
        $news->delete();
        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'News article deleted.');
    }
}
