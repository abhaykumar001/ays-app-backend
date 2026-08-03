<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    /**
     * Submit an enquiry for a project or unit.
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user('sanctum');

        $rules = [
            'project_id'     => 'required_without:team_member_id|nullable|exists:projects,id',
            'unit_id'        => 'nullable|exists:units,id',
            'team_member_id' => 'required_without:project_id|nullable|exists:team_members,id',
            'message'        => 'required|string|max:2000',
            'enquiry_type'   => 'nullable|string|in:general,viewing,booking,pricing',
        ];

        if (!$user) {
            $rules['guest_email'] = 'required|email|max:255';
            $rules['guest_phone'] = 'required|string|max:30';
        }

        $request->validate($rules);

        $enquiry = Enquiry::create([
            'user_id'        => $user?->id,
            'guest_email'    => $user ? null : $request->guest_email,
            'guest_phone'    => $user ? null : $request->guest_phone,
            'project_id'     => $request->project_id,
            'unit_id'        => $request->unit_id,
            'team_member_id' => $request->team_member_id,
            'message'        => $request->message,
            'enquiry_type'   => $request->enquiry_type ?? 'general',
            'status'         => 'new',
        ]);

        // Bump the project's enquiry counter
        if ($request->project_id) {
            Project::where('id', $request->project_id)
                ->increment('enquiries_count');
        }

        return response()->json([
            'success' => true,
            'message' => 'Your enquiry has been submitted. Our team will be in touch shortly.',
            'data'    => ['enquiry_id' => $enquiry->id],
        ], 201);
    }
}
