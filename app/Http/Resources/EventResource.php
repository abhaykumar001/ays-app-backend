<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $thumbnail = $this->getFirstMediaUrl('thumbnail', 'resize')
            ?: $this->getFirstMediaUrl('thumbnail')
            ?: $this->getFirstMediaUrl('images', 'resize')
            ?: $this->getFirstMediaUrl('images');

        $image = $this->getFirstMediaUrl('images', 'resize')
            ?: $this->getFirstMediaUrl('images');

        $video = $this->getFirstMediaUrl('videos');

        return [
            'id'                     => (string) $this->id,
            'title'                  => $this->title,
            'slug'                   => $this->slug,
            'type'                   => $this->type,
            'description'            => $this->description ?? '',
            'event_date'             => $this->event_date?->toDateString(),
            'start_time'             => $this->start_time,
            'end_time'               => $this->end_time,
            'venue'                  => $this->venue,
            'latitude'               => $this->latitude,
            'longitude'              => $this->longitude,
            'is_virtual'             => (bool) $this->is_virtual,
            'requires_registration'  => (bool) $this->requires_registration,
            'capacity'               => $this->capacity,
            'registration_deadline'  => $this->registration_deadline?->toIso8601String(),
            'is_featured'            => (bool) $this->is_featured,
            'thumbnail_url'          => $thumbnail,
            'image_url'              => $image ?: null,
            'video_url'              => $video ?: null,
        ];
    }
}
