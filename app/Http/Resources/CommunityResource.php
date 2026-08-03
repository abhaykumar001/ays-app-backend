<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommunityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $thumbnail = $this->getFirstMediaUrl('images', 'resize')
            ?: $this->getFirstMediaUrl('images');

        $gallery = $this->getMedia('images')
            ->map(fn($m) => $m->getUrl('resize') ?: $m->getUrl())
            ->values()
            ->all();

        return [
            'id'                => (string) $this->id,
            'name'              => $this->name,
            'slug'              => $this->slug,
            'city'              => $this->city ?? 'Dubai',
            'short_description' => $this->short_description ?? '',
            'long_description'  => $this->description ?? '',
            'thumbnail_url'     => $thumbnail,
            'gallery_images'    => $gallery,
            'starting_price'    => $this->starting_price,
            'growth'            => $this->growth,
            'roi'               => $this->roi,
            'category'          => $this->category,
            'is_featured'       => (bool) $this->is_featured,
            'address'           => $this->address,
            'latitude'          => $this->latitude,
            'longitude'         => $this->longitude,
            'amenities'         => AmenityResource::collection(
                $this->whenLoaded('amenities', fn() => $this->amenities, collect())
            ),
        ];
    }
}
