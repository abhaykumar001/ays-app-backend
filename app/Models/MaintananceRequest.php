<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MaintananceRequest extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'unit_id', 'owner_id', 'service_id', 'description', 'status',
        'assigned_to', 'scheduled_at', 'completed_at',
        'estimated_cost', 'materials_used', 'special_instructions',
        'is_urgent', 'priority_level',
    ];

    protected $casts = [
        'scheduled_at'  => 'datetime',
        'completed_at'  => 'datetime',
        'is_urgent'     => 'boolean',
    ];

    public function service()
    {
        return $this->belongsTo(Maintanance::class, 'service_id');
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function unit()
    {
        return $this->belongsTo(unit::class);
    }
}
