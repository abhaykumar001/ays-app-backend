<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectOffer extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'category',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function offerUnits()
    {
        return $this->hasMany(ProjectOfferUnit::class);
    }
}
