<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AmenityResource extends JsonResource
{
    /**
     * Map amenity names to icon keys used by the Flutter app.
     * Add new entries here as amenities are added in the admin panel.
     */
    private static array $iconMap = [
        'pool'          => 'pool',
        'swimming'      => 'pool',
        'gym'           => 'gym',
        'fitness'       => 'gym',
        'bbq'           => 'bbq',
        'barbecue'      => 'bbq',
        'grill'         => 'bbq',
        'lounge'        => 'lounge',
        'family'        => 'lounge',
        'game'          => 'game_room',
        'cinema'        => 'game_room',
        'garden'        => 'garden',
        'park'          => 'garden',
        'kids'          => 'kids',
        'children'      => 'kids',
        'playground'    => 'kids',
        'spa'           => 'spa',
        'sauna'         => 'spa',
        'parking'       => 'parking',
        'security'      => 'security',
        'concierge'     => 'security',
    ];

    public function toArray(Request $request): array
    {
        return [
            'name'        => $this->name,
            'description' => $this->description,
            'icon_key'    => $this->resolveIconKey($this->name),
        ];
    }

    private function resolveIconKey(string $name): string
    {
        $lower = strtolower($name);
        foreach (self::$iconMap as $keyword => $key) {
            if (str_contains($lower, $keyword)) {
                return $key;
            }
        }
        return 'star'; // fallback
    }
}
