<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * List all active locations.
     *
     * Query params:
     *   per_page = int (default 20)
     */
    public function index(Request $request): JsonResponse
    {
        $locations = Location::active()
            ->orderBy('sort_order')
            ->paginate($request->integer('per_page', 20));

        return response()->json([
            'success' => true,
            'data'    => LocationResource::collection($locations->items()),
            'meta'    => [
                'current_page' => $locations->currentPage(),
                'last_page'    => $locations->lastPage(),
                'per_page'     => $locations->perPage(),
                'total'        => $locations->total(),
            ],
        ]);
    }

    /**
     * Return a single active location.
     */
    public function show(string $id): JsonResponse
    {
        $location = Location::active()->where('id', $id)->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => new LocationResource($location),
        ]);
    }
}
