<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_events')->only(['index', 'show']);
        $this->middleware('permission:create_events')->only(['create', 'store']);
        $this->middleware('permission:edit_events')->only(['edit', 'update']);
        $this->middleware('permission:delete_events')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::orderByDesc('id')->get();
        return view('dashboard.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:launch,open_house,site_visit,broker_meet,webinar,handover,other',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'venue' => 'nullable|string|max:255',
            'is_virtual' => 'nullable|boolean',
            'requires_registration' => 'nullable|boolean',
            'capacity' => 'nullable|integer|min:1',
            'registration_deadline' => 'nullable|date',
            'status' => 'required|in:draft,published,cancelled,completed',
            'is_featured' => 'nullable|boolean',
            'is_public' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|max:5120',
            'video' => 'nullable|mimes:mp4,mov,avi,webm|max:262144',
            'thumbnail' => 'nullable|image|max:5120',
        ]);

        $event = Event::create([
            'title' => $request->title,
            'type' => $request->type,
            'description' => $request->description ?: null,
            'event_date' => $request->event_date,
            'start_time' => $request->start_time ?: null,
            'end_time' => $request->end_time ?: null,
            'venue' => $request->venue ?: null,
            'is_virtual' => $request->is_virtual ?? false,
            'requires_registration' => $request->requires_registration ?? true,
            'capacity' => $request->capacity ?: null,
            'registration_deadline' => $request->registration_deadline ?: null,
            'status' => $request->status,
            'is_featured' => $request->is_featured ?? false,
            'is_public' => $request->is_public ?? true,
            'created_by' => auth()->id(),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        if ($request->hasFile('image')) {
            $event->addMediaFromRequest('image')->toMediaCollection('images');
        }
        if ($request->hasFile('video')) {
            $event->addMediaFromRequest('video')->toMediaCollection('videos');
        }
        if ($request->hasFile('thumbnail')) {
            $event->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnail');
        }

        return redirect()->route('events.index')
            ->with('status', 'success')
            ->with('message', 'Event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('events.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $event = Event::findOrFail($id);
        return view('dashboard.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:launch,open_house,site_visit,broker_meet,webinar,handover,other',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'venue' => 'nullable|string|max:255',
            'is_virtual' => 'nullable|boolean',
            'requires_registration' => 'nullable|boolean',
            'capacity' => 'nullable|integer|min:1',
            'registration_deadline' => 'nullable|date',
            'status' => 'required|in:draft,published,cancelled,completed',
            'is_featured' => 'nullable|boolean',
            'is_public' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|max:5120',
            'video' => 'nullable|mimes:mp4,mov,avi,webm|max:262144',
            'thumbnail' => 'nullable|image|max:5120',
        ]);

        $event->update([
            'title' => $request->title,
            'type' => $request->type,
            'description' => $request->description ?: null,
            'event_date' => $request->event_date,
            'start_time' => $request->start_time ?: null,
            'end_time' => $request->end_time ?: null,
            'venue' => $request->venue ?: null,
            'is_virtual' => $request->is_virtual ?? false,
            'requires_registration' => $request->requires_registration ?? true,
            'capacity' => $request->capacity ?: null,
            'registration_deadline' => $request->registration_deadline ?: null,
            'status' => $request->status,
            'is_featured' => $request->is_featured ?? false,
            'is_public' => $request->is_public ?? true,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        if ($request->hasFile('image')) {
            $event->addMediaFromRequest('image')->toMediaCollection('images');
        }
        if ($request->hasFile('video')) {
            $event->addMediaFromRequest('video')->toMediaCollection('videos');
        }
        if ($request->hasFile('thumbnail')) {
            $event->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnail');
        }

        return redirect()->route('events.index')
            ->with('status', 'success')
            ->with('message', 'Event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        $event->clearMediaCollection('images');
        $event->clearMediaCollection('videos');
        $event->clearMediaCollection('thumbnail');
        $event->delete();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Event deleted successfully.');
    }
}
