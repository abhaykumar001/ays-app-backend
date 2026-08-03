<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DesignPhilosophySection extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'philosophy_id',
        'title',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('resize')
            ->format('webp')
            ->performOnCollections('images')
            ->nonQueued();
    }

    public function philosophy()
    {
        return $this->belongsTo(DesignPhilosophy::class, 'philosophy_id');
    }
}
