<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferUnitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $unit = $this->unit;

        $data = (new UnitResource($unit))->toArray($request);

        if ($this->price !== null) {
            $data['price'] = 'AED ' . number_format((float) $this->price, 0, '.', ',');
        }

        $data['floor_raw']  = $unit->floor;
        $data['total_area'] = $unit->size_sqft
            ? number_format((float) $unit->size_sqft, 2) . ' sqft'
            : 'N/A';
        $data['status'] = $unit->availability_status;

        return $data;
    }
}
