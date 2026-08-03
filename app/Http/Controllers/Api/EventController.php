<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * List upcoming published public events.
     *
     * Query params:
     *   type     = launch | open_house | site_visit | broker_meet | webinar | handover | other
     *   featured = 1 (filter to featured only)
     *   per_page = int (default 20)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Event::published()
            ->upcoming()
            ->where('is_public', true)
            ->orderBy('event_date')
            ->orderBy('sort_order');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->boolean('featured')) {
            $query->featured();
        }

        $events = $query->paginate($request->integer('per_page', 20));

        return response()->json([
            'success' => true,
            'data'    => EventResource::collection($events->items()),
            'meta'    => [
                'current_page' => $events->currentPage(),
                'last_page'    => $events->lastPage(),
                'per_page'     => $events->perPage(),
                'total'        => $events->total(),
            ],
        ]);
    }

    /**
     * Return a single event by slug.
     */
    public function show(string $slug): JsonResponse
    {
        $event = Event::published()
            ->where('is_public', true)
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => new EventResource($event),
        ]);
    }

    /**
     * Register (RSVP) for an event. Guests must provide name/email/phone;
     * authenticated users are registered using their account details.
     */
    public function register(Request $request, string $slug): JsonResponse
    {
        $event = Event::published()
            ->where('is_public', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $user = $request->user('sanctum');

        $rules = [];
        if (!$user) {
            $rules['name']  = 'required|string|max:255';
            $rules['email'] = 'required|email';
            $rules['phone'] = 'required|string|max:30';
        }
        $request->validate($rules);

        // Check capacity
        if ($event->capacity && $event->registrations()->count() >= $event->capacity) {
            return response()->json([
                'success' => false,
                'message' => 'This event is fully booked.',
            ], 422);
        }

        $event->registrations()->create([
            'user_id' => $user?->id,
            'name'    => $user?->name ?? $request->name,
            'email'   => $user?->email ?? $request->email,
            'phone'   => $user ? null : $request->phone,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'You have been registered for this event.',
        ], 201);
    }
}
