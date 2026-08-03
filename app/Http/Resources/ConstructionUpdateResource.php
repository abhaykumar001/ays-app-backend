<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConstructionUpdateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => (string) $this->id,
            'title'       => $this->title,
            'description' => $this->description ?? '',
            'stage'       => $this->stage,
            'update_date' => $this->update_date
                ? \Carbon\Carbon::parse($this->update_date)->format('M d, Y')
                : null,
            'update_date_short' => $this->update_date
                ? strtoupper(\Carbon\Carbon::parse($this->update_date)->format('M j'))
                : null,
            'update_year' => $this->update_date
                ? \Carbon\Carbon::parse($this->update_date)->format('Y')
                : null,
            'media_urls'  => $this->getMedia('updates')
                ->map(fn($m) => $m->getUrl())
                ->values()
                ->all(),
            'link'        => $this->link,
        ];
    }
}
