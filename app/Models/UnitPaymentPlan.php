<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitPaymentPlan extends Model
{
    protected $fillable = [
        'unit_id',
        'name',
        'tentative_sale_date',
        'sort_order',
    ];

    protected $casts = [
        'tentative_sale_date' => 'date',
    ];

    public function unit()
    {
        return $this->belongsTo(unit::class);
    }

    public function milestones()
    {
        return $this->hasMany(UnitPaymentPlanMilestone::class)->orderBy('sort_order');
    }
}
