<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectOfferResource;
use App\Models\ProjectOffer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * List all active project offers.
     *
     * Query params:
     *   category = exclusive | payment_plan | investment
     */
    public function index(Request $request): JsonResponse
    {
        $query = ProjectOffer::with(['project.community', 'project.accommodations'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $offers = $query->get();

        return response()->json([
            'success' => true,
            'data'    => ProjectOfferResource::collection($offers),
        ]);
    }

    /**
     * Return a single offer with its unit availability.
     */
    public function show(string $id): JsonResponse
    {
        $offer = ProjectOffer::with([
            'project.community',
            'project.accommodations',
            'offerUnits.unit.amenities',
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => new ProjectOfferResource($offer, withUnits: true),
        ]);
    }
}
