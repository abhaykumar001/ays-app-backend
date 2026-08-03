<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectPaymentPlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => (string) $this->id,
            'title'             => $this->title,
            'description'       => $this->description ?? '',
            'down_payment'      => $this->down_payment,
            'total_price'       => $this->total_price,
            'payment_breakdown' => $this->payment_breakdown ?? [],
            'installments'      => $this->installments ?? [],
            'is_offer'          => (bool) $this->is_offer,
            'file_url'          => $this->getFirstMediaUrl('payment_plans') ?: null,
        ];
    }
}
