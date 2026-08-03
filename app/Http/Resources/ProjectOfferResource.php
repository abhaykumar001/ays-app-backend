<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectOfferResource extends JsonResource
{
    /** Include the unit-availability list (used on detail view, not list view). */
    private bool $withUnits;

    public function __construct($resource, bool $withUnits = false)
    {
        parent::__construct($resource);
        $this->withUnits = $withUnits;
    }

    public function toArray(Request $request): array
    {
        return [
            'id'          => (string) $this->id,
            'title'       => $this->title,
            'description' => $this->description ?? '',
            'category'    => $this->category,
            'project'     => new ProjectResource($this->project),
            'units'       => $this->withUnits
                ? OfferUnitResource::collection($this->whenLoaded('offerUnits'))
                : [],
        ];
    }
}
