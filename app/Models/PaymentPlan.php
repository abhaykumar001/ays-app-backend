<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PaymentPlan extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'project_id',
        'title',
        'payment_breakdown',
        'down_payment',
        'total_price',
        'description',
        'installments',
        'is_active',
        'is_offer',
    ];

    protected $casts = [
        'payment_breakdown' => 'array',
        'installments' => 'array',
        'is_active' => 'boolean',
        'is_offer' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
