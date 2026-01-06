<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class unit extends Model
{
    public function unitMedia()
    {
        return $this->morphMany(VirtualTour::class, 'tourable');
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
