<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Phase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id', 'name', 'slug', 'total_units', 'bedrooms',
        'launch_date', 'handover_date', 'handover', 'status',
        'sort_order', 'user_id', 'is_active',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function amenities()
    {
        return $this->morphToMany(Amenity::class, 'amenable', 'amenables', 'amenable_id', 'amenity_id');
    }

    public function accommodations()
    {
        return $this->belongsToMany(Accommodation::class, 'phase_accommodations', 'phase_id', 'accommodation_id');
    }
}
