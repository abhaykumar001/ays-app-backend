<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ConstructionUpdate extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'updatable_type',
        'updatable_id',
        'title',
        'description',
        'progress_percentage',
        'update_date',
        'stage',
        'is_public',
        'is_active',
        'created_by',
        'sort_order',
        'link',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('updates')->useDisk('public');
    }

    public function updatable()
    {
        return $this->morphTo();
    }

    // Access first media URL
    public function media_url()
    {
        return $this->getFirstMediaUrl('updates');
    }
}
