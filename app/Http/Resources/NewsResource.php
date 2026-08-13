<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $thumbnail = $this->getFirstMediaUrl('images', 'resize')
            ?: $this->getFirstMediaUrl('images');

        return [
            'id'                => (string) $this->id,
            'title'             => $this->title,
            'slug'              => $this->slug,
            'short_description' => $this->short_description ?? '',
            'full_description'  => $this->description ? (string) $this->description : '',
            'author'            => $this->author,
            'thumbnail_url'     => $thumbnail,
            'is_featured'       => (bool) $this->is_featured,
            'show_on_ays_screen' => (bool) $this->show_on_ays_screen,
            'published_at'      => $this->published_at?->format('M d, Y'),
        ];
    }
}
