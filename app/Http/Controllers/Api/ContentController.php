<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnnouncementResource;
use App\Http\Resources\BlogResource;
use App\Http\Resources\MarketInsightResource;
use App\Models\Announcement;
use App\Models\Blog;
use App\Models\MarketInsight;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    /**
     * List published blogs.
     *
     * Query params:
     *   featured = 1
     *   per_page = int (default 20)
     */
    public function blogs(Request $request): JsonResponse
    {
        $query = Blog::where('is_active', true)
            ->orderByDesc('published_at');

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $blogs = $query->paginate($request->integer('per_page', 20));

        return response()->json([
            'success' => true,
            'data'    => BlogResource::collection($blogs->items()),
            'meta'    => [
                'current_page' => $blogs->currentPage(),
                'last_page'    => $blogs->lastPage(),
                'per_page'     => $blogs->perPage(),
                'total'        => $blogs->total(),
            ],
        ]);
    }

    /**
     * Return a single blog by slug.
     */
    public function showBlog(string $slug): JsonResponse
    {
        $blog = Blog::where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => new BlogResource($blog),
        ]);
    }

    /**
     * List published market insights.
     */
    public function marketInsights(Request $request): JsonResponse
    {
        $query = MarketInsight::where('is_active', true)
            ->orderByDesc('published_at');

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $insights = $query->paginate($request->integer('per_page', 20));

        return response()->json([
            'success' => true,
            'data'    => MarketInsightResource::collection($insights->items()),
            'meta'    => [
                'current_page' => $insights->currentPage(),
                'last_page'    => $insights->lastPage(),
                'per_page'     => $insights->perPage(),
                'total'        => $insights->total(),
            ],
        ]);
    }

    /**
     * Return a single market insight by slug.
     */
    public function showMarketInsight(string $slug): JsonResponse
    {
        $insight = MarketInsight::where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => new MarketInsightResource($insight),
        ]);
    }

    /**
     * List active announcements (newest first).
     * Auth-protected: each user sees announcements matching their audience.
     */
    public function announcements(Request $request): JsonResponse
    {
        $query = Announcement::where('is_active', true)
            ->orderByDesc('created_at');

        // Filter by audience — 'all' is always included
        $user = $request->user();
        if ($user) {
            $query->where(function ($q) {
                $q->where('audience', 'all')
                  ->orWhere('audience', 'users');
            });
        } else {
            $query->where('audience', 'all');
        }

        $announcements = $query->paginate($request->integer('per_page', 20));

        return response()->json([
            'success' => true,
            'data'    => AnnouncementResource::collection($announcements->items()),
            'meta'    => [
                'current_page' => $announcements->currentPage(),
                'last_page'    => $announcements->lastPage(),
                'per_page'     => $announcements->perPage(),
                'total'        => $announcements->total(),
            ],
        ]);
    }
}
