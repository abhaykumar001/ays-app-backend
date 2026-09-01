<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $image = $this->getFirstMediaUrl('images', 'resize')
            ?: $this->getFirstMediaUrl('images');

        return [
            'id'           => (string) $this->id,
            'title'        => $this->title,
            'description'  => $this->description,
            'image_url'    => $image ?: null,
            'phone'        => $this->phone ?: null,
            'email'        => $this->email ?: null,
            'website_url'  => $this->website_url ?: null,
            'opening_hours' => $this->opening_hours ?? [],
            'address'      => $this->address ?: null,
            'latitude'     => $this->hasCoordinates() ? (float) $this->latitude : null,
            'longitude'    => $this->hasCoordinates() ? (float) $this->longitude : null,
        ];
    }
}
