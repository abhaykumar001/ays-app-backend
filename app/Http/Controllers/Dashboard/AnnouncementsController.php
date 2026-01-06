<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementsController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->get();
        return view('dashboard.contentManagement.announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:general,project_update,handover,maintenance',
            'audience' => 'required|in:public,owners,buyers,agents,internal',
            'priority' => 'required|in:low,normal,high,urgent',
            'is_popup' => 'boolean',
            'is_active' => 'boolean',
            'publish_at' => 'nullable|date',
            'expire_at' => 'nullable|date|after_or_equal:publish_at',
            'cta_text' => 'nullable|string|max:255',
            'cta_url' => 'nullable|string|max:255',
        ]);

        $data['is_popup'] = $request->boolean('is_popup');
        $data['is_active'] = $request->boolean('is_active');
        $data['created_by'] = auth()->id();

        Announcement::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Announcement created successfully!'
        ]);
    }

    public function edit(Announcement $announcement)
    {
        return response()->json($announcement);
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:general,project_update,handover,maintenance',
            'audience' => 'required|in:public,owners,buyers,agents,internal',
            'priority' => 'required|in:low,normal,high,urgent',
            'is_popup' => 'boolean',
            'is_active' => 'boolean',
            'publish_at' => 'nullable|date',
            'expire_at' => 'nullable|date|after_or_equal:publish_at',
            'cta_text' => 'nullable|string|max:255',
            'cta_url' => 'nullable|string|max:255',
        ]);

        $data['is_popup'] = $request->boolean('is_popup');
        $data['is_active'] = $request->boolean('is_active');

        $announcement->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Announcement updated successfully!'
        ]);
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Announcement deleted successfully!'
        ]);
    }
}