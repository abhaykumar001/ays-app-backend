<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => (string) $this->id,
            'title'           => $this->title,
            'message'         => $this->message,
            'type'            => $this->type,
            'priority'        => $this->priority,
            'deep_link_type'  => $this->deep_link_type ?? 'none',
            'deep_link_value' => $this->deep_link_value,
            'is_read'         => (bool) $this->is_read,
            'created_at'      => $this->created_at?->toIso8601String(),
        ];
    }
}
