<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => (string) $this->id,
            'title'    => $this->title,
            'message'  => $this->message,
            'type'     => $this->type,
            'audience' => $this->audience,
            'priority' => $this->priority,
        ];
    }
}
