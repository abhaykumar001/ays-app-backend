<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    /**
     * Register (or refresh) an FCM device token. Callable without auth so
     * a token can be captured before login; if a valid Sanctum bearer
     * token is present, the token gets attributed to that user.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'token'     => 'required|string',
            'platform'  => 'required|in:android,ios',
            'device_id' => 'nullable|string',
        ]);

        $user = $request->user('sanctum');

        DeviceToken::updateOrCreate(
            ['token' => $data['token']],
            [
                'user_id'      => $user?->id,
                'platform'     => $data['platform'],
                'device_id'    => $data['device_id'] ?? null,
                'last_used_at' => now(),
            ]
        );

        return response()->json(['success' => true]);
    }
}
