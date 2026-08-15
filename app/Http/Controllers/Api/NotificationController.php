<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $notifications = $request->user()->appNotifications()
            ->paginate($request->integer('per_page', 20));

        return response()->json([
            'success' => true,
            'data'    => NotificationResource::collection($notifications->items()),
            'meta'    => [
                'current_page' => $notifications->currentPage(),
                'last_page'    => $notifications->lastPage(),
                'total'        => $notifications->total(),
            ],
        ]);
    }

    public function unreadCount(Request $request): JsonResponse
    {
        $count = $request->user()->appNotifications()->where('is_read', false)->count();

        return response()->json(['success' => true, 'count' => $count]);
    }

    public function markRead(Request $request, Notification $notification): JsonResponse
    {
        if ($notification->notifiable_type !== User::class || $notification->notifiable_id !== $request->user()->id) {
            abort(404);
        }

        $notification->markRead();

        return response()->json(['success' => true]);
    }

    public function markAllRead(Request $request): JsonResponse
    {
        $request->user()->appNotifications()->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function settings(Request $request): JsonResponse
    {
        $existing = NotificationSetting::query()
            ->where('user_type', User::class)
            ->where('user_id', $request->user()->id)
            ->get()
            ->keyBy('type');

        $data = collect(NotificationSetting::TYPES)->map(function ($type) use ($existing) {
            $row = $existing->get($type);

            return [
                'type' => $type,
                'push' => $row ? (bool) $row->push : true,
            ];
        })->values();

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function updateSettings(Request $request): JsonResponse
    {
        $data = $request->validate([
            'settings'          => 'required|array',
            'settings.*.type'   => ['required', 'in:' . implode(',', NotificationSetting::TYPES)],
            'settings.*.push'   => 'required|boolean',
        ]);

        foreach ($data['settings'] as $setting) {
            NotificationSetting::updateOrCreate(
                [
                    'user_type' => User::class,
                    'user_id'   => $request->user()->id,
                    'type'      => $setting['type'],
                ],
                [
                    'push'       => $setting['push'],
                    'is_enabled' => $setting['push'],
                ]
            );
        }

        return response()->json(['success' => true]);
    }
}
