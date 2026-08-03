<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitPaymentPlanMilestone extends Model
{
    protected $fillable = [
        'unit_payment_plan_id',
        'month_offset',
        'percent',
        'is_amount_manual',
        'amount',
        'sort_order',
    ];

    protected $casts = [
        'is_amount_manual' => 'boolean',
        'percent'          => 'float',
        'amount'           => 'float',
    ];

    public function paymentPlan()
    {
        return $this->belongsTo(UnitPaymentPlan::class, 'unit_payment_plan_id');
    }

    public function resolvedAmount(): ?float
    {
        if ($this->is_amount_manual) {
            return $this->amount;
        }

        $price = $this->paymentPlan?->unit?->price;

        return $price ? round(($this->percent / 100) * (float) $price, 2) : null;
    }

    public function calendarMonth(): ?string
    {
        $saleDate = $this->paymentPlan?->tentative_sale_date;

        return $saleDate ? $saleDate->copy()->addMonths($this->month_offset)->format('M Y') : null;
    }

    public function withinMonthsText(): string
    {
        return "Within {$this->month_offset} month(s) of Sale Date";
    }
}
