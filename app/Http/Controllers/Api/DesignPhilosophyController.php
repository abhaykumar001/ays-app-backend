<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DesignPhilosophyResource;
use App\Models\DesignPhilosophy;
use Illuminate\Http\JsonResponse;

class DesignPhilosophyController extends Controller
{
    public function index(): JsonResponse
    {
        $philosophy = DesignPhilosophy::singleton();
        $philosophy->load('sections');

        return response()->json([
            'success' => true,
            'data'    => new DesignPhilosophyResource($philosophy),
        ]);
    }
}
