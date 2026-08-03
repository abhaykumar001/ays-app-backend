<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KioskSlideResource;
use App\Models\KioskSlide;
use Illuminate\Http\JsonResponse;

class KioskController extends Controller
{
    public function index(): JsonResponse
    {
        $slides = KioskSlide::active()
            ->orderBy('display_order')
            ->orderBy('id')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => KioskSlideResource::collection($slides),
        ]);
    }
}
