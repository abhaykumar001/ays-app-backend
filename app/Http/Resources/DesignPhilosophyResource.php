<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DesignPhilosophyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $heroUrl = $this->getFirstMediaUrl('hero', 'resize')
            ?: $this->getFirstMediaUrl('hero');

        $sections = $this->sections->map(function ($section) {
            $images = $section->getMedia('images')->map(
                fn($m) => $m->getUrl('resize') ?: $m->getUrl()
            )->values()->all();

            return [
                'id'          => (string) $section->id,
                'title'       => $section->title,
                'description' => $section->description ?? '',
                'sort_order'  => $section->sort_order,
                'images'      => $images,
            ];
        })->values()->all();

        return [
            'hero_image_url'    => $heroUrl ?: null,
            'hero_title'        => $this->hero_title,
            'hero_title_accent' => $this->hero_title_accent,
            'hero_subtitle'     => $this->hero_subtitle ?? '',
            'quote'             => $this->quote ?? '',
            'sections'          => $sections,
        ];
    }
}
