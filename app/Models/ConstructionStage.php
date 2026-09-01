<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConstructionStage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'sort_order',
        'weight_percentage',
    ];

    protected $casts = [
        'weight_percentage' => 'float',
    ];

    public function constructionUpdates()
    {
        return $this->hasMany(ConstructionUpdate::class);
    }
}
