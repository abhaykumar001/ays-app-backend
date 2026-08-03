<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectOfferUnit extends Model
{
    protected $fillable = [
        'project_offer_id',
        'unit_id',
        'price',
    ];

    public function offer()
    {
        return $this->belongsTo(ProjectOffer::class, 'project_offer_id');
    }

    public function unit()
    {
        return $this->belongsTo(unit::class, 'unit_id');
    }
}
