<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // bedrooms is a free-text string (e.g. "Studio", "1", "Duplex 1-3")
        $bedroomsRaw = (string) ($this->bedrooms ?? '');
        $label = $bedroomsRaw !== '' ? $bedroomsRaw : 'N/A';

        // Format price as "AED X,XXX,XXX"
        $price = $this->price
            ? 'AED ' . number_format((float) $this->price, 0, '.', ',')
            : 'Price on request';

        // size_sqft is now a free-text string (e.g. "1,200", "901-1,867")
        $sqft = ($this->size_sqft !== null && $this->size_sqft !== '')
            ? $this->size_sqft . ' SQFT'
            : 'N/A';

        // floor is now a free-text string (e.g. "5", "2-4-6-8", "G-10")
        $floors = ($this->floor !== null && $this->floor !== '')
            ? $this->floor . ' Floor'
            : 'N/A';

        // Amenities: prefer unit-level; fall back to project-level if empty
        $amenities = $this->whenLoaded('amenities', function () {
            return $this->amenities->isNotEmpty()
                ? AmenityResource::collection($this->amenities)
                : AmenityResource::collection($this->project?->amenities ?? collect());
        }, []);

        // Gallery: use media images; fall back to project images
        $gallery = $this->getMedia('images')->map(
            fn($m) => $m->getUrl('resize') ?: $m->getUrl()
        );
        if ($gallery->isEmpty() && $this->project) {
            $gallery = $this->project->getMedia('images')->map(
                fn($m) => $m->getUrl('resize') ?: $m->getUrl()
            );
        }

        return [
            'id'                  => (string) $this->id,
            'title'               => $this->title ?? '',
            'label'               => $label,
            'price'               => $price,
            'bathrooms'           => (string) ($this->bathrooms ?? ''),
            'sqft_range'          => $sqft,
            'floors'              => $floors,
            'description'         => $this->description
                               ?? $this->project?->short_description
                               ?? '',
            'amenities'           => $amenities,
            'gallery_images'      => $gallery->values()->all(),
            'unit_number'         => $this->unit_number ?? '',
            'unit_type'           => $this->unit_type ?? '',
            'view'                => $this->view ?? '',
            'price_per_sqft'      => $this->price_per_sqft ?? '',
            'availability_status' => $this->availability_status ?? 'available',
            'payment_plans'       => $this->whenLoaded('paymentPlans', function () {
                return UnitPaymentPlanResource::collection($this->paymentPlans);
            }, []),
        ];
    }
}
