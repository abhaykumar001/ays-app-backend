<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommunityResource;
use App\Models\Community;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    /**
     * List all active communities.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Community::with(['amenities'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('is_featured');

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('city', 'like', "%{$term}%");
            });
        }

        $communities = $query->paginate($request->integer('per_page', 20));

        return response()->json([
            'success' => true,
            'data'    => CommunityResource::collection($communities->items()),
            'meta'    => [
                'current_page' => $communities->currentPage(),
                'last_page'    => $communities->lastPage(),
                'per_page'     => $communities->perPage(),
                'total'        => $communities->total(),
            ],
        ]);
    }

    /**
     * Return a single community by slug.
     */
    public function show(string $slug): JsonResponse
    {
        $community = Community::with(['amenities'])
            ->where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => new CommunityResource($community),
        ]);
    }
}
