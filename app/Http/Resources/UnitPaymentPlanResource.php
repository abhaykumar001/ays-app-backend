<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitPaymentPlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                   => (string) $this->id,
            'name'                 => $this->name,
            'tentative_sale_date'  => optional($this->tentative_sale_date)->format('Y-m-d'),
            'milestones'           => $this->whenLoaded('milestones', function () {
                return $this->milestones->map(function ($m) {
                    $m->setRelation('paymentPlan', $this->resource);

                    return [
                        'month_offset'       => $m->month_offset,
                        'calendar_month'     => $m->calendarMonth(),
                        'within_months_text' => $m->withinMonthsText(),
                        'percent'            => (float) $m->percent,
                        'amount'             => $m->resolvedAmount(),
                    ];
                })->values();
            }, []),
        ];
    }
}
