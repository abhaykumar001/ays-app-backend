<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\MarketInsightRequest;
use App\Models\MarketInsight;
use Illuminate\Http\Request;

class MarketInsightsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_market_insights')->only(['index', 'show']);
        $this->middleware('permission:create_market_insights')->only(['create', 'store']);
        $this->middleware('permission:edit_market_insights')->only(['edit', 'update']);
        $this->middleware('permission:delete_market_insights')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marketInsights = MarketInsight::get();
        return view('dashboard.contentManagement.marketInsights.index', compact('marketInsights'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.contentManagement.marketInsights.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MarketInsightRequest $request)
    {
        // Create new Insight instance
        $marketInsight = new MarketInsight;
        $marketInsight->title = $request->title;
        $marketInsight->published_at = $request->published_at;
        $marketInsight->short_description = $request->short_description;
        $marketInsight->author = $request->author;
        $marketInsight->description = $request->description; // Rich text
        $marketInsight->is_active = $request->is_active ?? true;
        $marketInsight->is_featured = $request->is_featured ?? '0';
        $marketInsight->meta_title = $request->meta_title;
        $marketInsight->meta_description = $request->meta_description;
        $marketInsight->meta_keywords = $request->meta_keywords;
        $marketInsight->user_id = auth()->user()->id;
        $marketInsight->save();

        // Handle image upload if needed
        if ($request->hasFile('image')) {
            $marketInsight->addMediaFromRequest('image')->toMediaCollection('images');
        }

        return redirect()->route('marketInsights.index')
            ->with('status', 'success')
            ->with('message', 'MarketInsight created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('marketInsights.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $marketInsight = MarketInsight::findorfail($id);
        return view('dashboard.contentManagement.marketInsights.edit', compact('marketInsight'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MarketInsightRequest $request, string $id)
    {
        $marketInsight = MarketInsight::findorfail($id);
        $marketInsight->title = $request->title;
        $marketInsight->published_at = $request->published_at;
        $marketInsight->short_description = $request->short_description;
        $marketInsight->author = $request->author;
        $marketInsight->description = $request->description; // Rich text
        $marketInsight->is_active = $request->is_active;
        $marketInsight->is_featured = $request->is_featured;
        $marketInsight->meta_title = $request->meta_title;
        $marketInsight->meta_description = $request->meta_description;
        $marketInsight->meta_keywords = $request->meta_keywords;
        $marketInsight->user_id = auth()->user()->id;
        $marketInsight->save();
        // Handle image upload if needed
        if ($request->hasFile('image')) {
            $marketInsight->clearMediaCollection('images');
            $marketInsight->addMediaFromRequest('image')->toMediaCollection('images');
        }
       
        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'MarketInsight Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $marketInsight = MarketInsight::findorfail($id);
        $marketInsight->clearMediaCollection('images');
        $marketInsight->delete();
        return redirect()->back()->with('status', 'success')->with('message', 'MarketInsight deleted successfully.');
    }
}
