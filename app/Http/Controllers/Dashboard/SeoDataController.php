<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SeoData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Helpers\RouteHelper;

class SeoDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_seo_data')->only(['index', 'show']);
        $this->middleware('permission:create_seo_data')->only(['create', 'store']);
        $this->middleware('permission:edit_seo_data')->only(['edit', 'update']);
        $this->middleware('permission:delete_seo_data')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $seoData = SeoData::get();
        if ($seoData->isEmpty()) {
            // Optional: show empty array or flash message
            $seoData = collect(); // ensures it's always a collection
        }
        return view('dashboard.seo.index', compact('seoData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $frontendRoutes = RouteHelper::frontendRoutes();
        return view('dashboard.seo.create', compact('frontendRoutes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'page_name' => 'required|string|max:255|unique:seo_data,page_name',
            'meta_title' => 'required|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|max:2048', // 2MB max
        ]);
        // Create new SEO record
        $seoData = SeoData::create($validated);
        $seoData->user_id = auth()->user()->id;
        // Handle image upload if exists
        if ($request->hasFile('image')) {
            $seoData->addMedia($request->file('image'))
                ->toMediaCollection('images', 'seoImageFiles');
        }
        // Redirect back with success message
        return redirect()->route('seoData.index')->with('status', 'success')->with('message', 'SEO data created successfully!');;
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
        $seoData = SeoData::findorfail($id);
        $frontendRoutes = RouteHelper::frontendRoutes();
        return view('dashboard.seo.edit', compact('seoData', 'frontendRoutes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'page_name' => 'required|string|max:255|unique:seo_data,page_name,' . $id,
            'meta_title' => 'required|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|max:2048',
        ]);

        // Find existing record
        $seoData = SeoData::findOrFail($id);

        // Update record
        $seoData->update($validated);

        // Assign user ID (if you track who updates)
        $seoData->user_id = auth()->id();
        $seoData->save();

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $seoData->clearMediaCollection('images');
            $seoData->addMedia($request->file('image'))
                ->toMediaCollection('images', 'seoImageFiles');
        }
        // Redirect back with success message
        return redirect()->route('seoData.edit', $seoData->id)->with('status', 'success')->with('message', 'SEO data updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $seoData = SeoData::findorfail($id);
        $seoData->clearMediaCollection('images');
        $seoData->delete();
        return redirect()->route('seoData.index')
                 ->with('status', 'success')
                 ->with('message', 'SEO data deleted successfully!');
    }
}
