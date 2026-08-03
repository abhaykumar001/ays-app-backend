<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamMemberResource;
use App\Models\TeamMember;
use Illuminate\Http\JsonResponse;

class TeamMemberController extends Controller
{
    public function index(): JsonResponse
    {
        $members = TeamMember::active()
            ->with('category')
            ->orderBy('display_order')
            ->orderBy('id')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => TeamMemberResource::collection($members),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $member = TeamMember::active()
            ->with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => new TeamMemberResource($member),
        ]);
    }
}
