<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KioskSlideResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $imageUrl = $this->getFirstMediaUrl('images', 'resize')
            ?: $this->getFirstMediaUrl('images');

        return [
            'id'            => (string) $this->id,
            'title'         => $this->title,
            'image_url'     => $imageUrl,
            'display_order' => $this->display_order,
        ];
    }
}
