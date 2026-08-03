<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Viewing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ViewingController extends Controller
{
    /**
     * Submit a viewing request for a project or unit.
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user('sanctum');

        $rules = [
            'project_id'     => 'required_without:team_member_id|nullable|exists:projects,id',
            'unit_id'        => 'nullable|exists:units,id',
            'team_member_id' => 'required_without:project_id|nullable|exists:team_members,id',
            'viewing_type'   => 'nullable|in:in_person,virtual,video_call',
            'scheduled_at'   => 'required|date|after:now',
            'notes'          => 'nullable|string|max:1000',
        ];

        if (!$user) {
            $rules['guest_email'] = 'required|email|max:255';
            $rules['guest_phone'] = 'required|string|max:30';
        }

        $request->validate($rules);

        $viewing = Viewing::create([
            'user_id'        => $user?->id,
            'guest_email'    => $user ? null : $request->guest_email,
            'guest_phone'    => $user ? null : $request->guest_phone,
            'project_id'     => $request->project_id,
            'unit_id'        => $request->unit_id,
            'team_member_id' => $request->team_member_id,
            'viewing_type'   => $request->viewing_type ?? 'in_person',
            'scheduled_at'   => $request->scheduled_at,
            'notes'          => $request->notes,
            'status'         => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Viewing request submitted. We will confirm shortly.',
            'data'    => ['id' => (string) $viewing->id],
        ], 201);
    }
}
