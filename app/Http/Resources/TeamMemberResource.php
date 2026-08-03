<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamMemberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $imageUrl = $this->getFirstMediaUrl('images', 'resize')
            ?: $this->getFirstMediaUrl('images');

        return [
            'id'                   => (string) $this->id,
            'name'                 => $this->name,
            'slug'                 => $this->slug,
            'designation'          => $this->category?->name,
            'category_id'          => $this->team_member_category_id ? (string) $this->team_member_category_id : null,
            'category_sort_order'  => $this->category?->sort_order ?? 0,
            'email'                => $this->email,
            'phone'                => $this->phone,
            'languages'            => $this->languages,
            'description'          => $this->description,
            'image_url'            => $imageUrl,
            'display_order'        => $this->display_order,
        ];
    }
}
