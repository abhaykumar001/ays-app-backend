<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = News::where('is_active', true)
            ->orderByDesc('published_at');

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->boolean('show_on_ays')) {
            $query->where('show_on_ays_screen', true);
        }

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('short_description', 'like', "%{$term}%");
            });
        }

        $news = $query->paginate($request->integer('per_page', 20));

        return response()->json([
            'success' => true,
            'data'    => NewsResource::collection($news->items()),
            'meta'    => [
                'current_page' => $news->currentPage(),
                'last_page'    => $news->lastPage(),
                'per_page'     => $news->perPage(),
                'total'        => $news->total(),
            ],
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $article = News::where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => new NewsResource($article),
        ]);
    }
}
