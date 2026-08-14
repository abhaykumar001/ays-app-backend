<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /** Include the full units list (used on detail view, not list view). */
    private bool $withUnits;

    public function __construct($resource, bool $withUnits = false)
    {
        parent::__construct($resource);
        $this->withUnits = $withUnits;
    }

    public function toArray(Request $request): array
    {
        // Thumbnail — first image in the 'images' media collection
        $thumbnail = $this->getFirstMediaUrl('images', 'resize')
            ?: $this->getFirstMediaUrl('images');

        // Gallery — all images
        $gallery = $this->getMedia('images')
            ->map(fn($m) => $m->getUrl('resize') ?: $m->getUrl())
            ->values()
            ->all();

        // Materiality slider images
        $materialityImages = $this->getMedia('materiality')
            ->map(fn($m) => $m->getUrl())
            ->values()
            ->all();

        // Map DB enum → Flutter enum string
        $statusMap = [
            'off_plan'          => 'offPlan',
            'ready'             => 'ready',
            'under_construction' => 'construction',
        ];

        return [
            'id'                => (string) $this->id,
            'slug'              => $this->slug,
            'name'              => $this->name,
            'tagline'           => $this->tagline ?: null,
            'location'          => $this->community?->name ?? $this->city ?? 'Dubai',
            'latitude'          => $this->hasCoordinates() ? (float) $this->latitude : null,
            'longitude'         => $this->hasCoordinates() ? (float) $this->longitude : null,
            'short_description' => $this->short_description ?? '',
            'title_description' => $this->title_description ?? '',
            'quote_description' => $this->quote_description ?? '',
            'full_description'  => $this->description ?? $this->short_description ?? '',
            'shared_description' => $this->shared_description ?: null,
            'thumbnail_url'     => $thumbnail,
            'gallery_images'    => $gallery,
            'materiality_title'       => $this->materiality_title ?? '',
            'materiality_description' => $this->materiality_description ?? '',
            'materiality_images'      => $materialityImages,
            'starting_price'    => $this->formattedStartingPrice(),
            'status'            => $statusMap[$this->project_status] ?? 'ready',
            // Sourced from the most recent Construction Update entry (dashboard: Projects > Construction Updates),
            // so this always matches what the construction-update detail screen shows.
            'construction_progress' => $this->computedConstructionProgress(),
            'is_featured'       => (bool) $this->is_featured,
            'is_hot_selling'    => (bool) $this->is_hot_selling,
            'bedrooms_range'    => $this->bedrooms,
            'bathrooms_range'   => $this->bathrooms,
            'min_size'          => $this->min_size,
            'max_size'          => $this->max_size,
            'property_type'     => $this->whenLoaded('accommodations', fn() =>
                $this->accommodations->first()?->name, null),
            'virtual_tour_url'  => $this->virtual_tour_url ?: null,
            'brochure_url'      => $this->getFirstMediaUrl('brochures') ?: null,
            'floorplan_url'     => $this->getFirstMediaUrl('floorplans') ?: null,
            'units'             => $this->withUnits
                ? UnitResource::collection($this->whenLoaded('units'))
                : [],
            'handover'          => $this->handover ?? null,
            'on_handover_payment'   => $this->on_handover_payment ?: null,
            'post_handover_payment' => $this->post_handover_payment ?: null,
            'cash_buyer_payment_plan' => $this->cash_buyer_payment_plan ?: null,
            'amenities'         => AmenityResource::collection(
                $this->whenLoaded('amenities', fn() => $this->amenities, collect())
            ),
            'payment_plans'     => ProjectPaymentPlanResource::collection(
                $this->whenLoaded('paymentPlans', fn() => $this->paymentPlans, collect())
            ),
            'community'         => $this->whenLoaded('community', fn() =>
                $this->community ? new CommunityResource($this->community) : null
            ),
        ];
    }

    /** The admin form saves 0/0 as the hidden-field default when no pin has been dropped. */
    private function hasCoordinates(): bool
    {
        return $this->latitude !== null
            && $this->longitude !== null
            && ! ((float) $this->latitude === 0.0 && (float) $this->longitude === 0.0);
    }

    private function formattedStartingPrice(): string
    {
        $labels = [
            'on_request'  => 'Price on Request',
            'coming_soon' => 'Coming Soon',
            'sold_out'    => 'Sold Out',
        ];

        $status = $this->price_status ?? 'price';

        if ($status !== 'price') {
            return $labels[$status] ?? 'Price on Request';
        }

        return $this->starting_price
            ? 'AED ' . number_format((float) $this->starting_price, 0, '.', ',')
            : 'Price on Request';
    }
}
