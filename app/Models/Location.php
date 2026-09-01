<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Location extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'phone',
        'email',
        'website_url',
        'opening_hours',
        'address',
        'latitude',
        'longitude',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'opening_hours' => 'array',
        'is_active'     => 'boolean',
        'latitude'      => 'decimal:7',
        'longitude'     => 'decimal:7',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')->useDisk('public');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('resize')
            ->format('webp')
            ->performOnCollections('images')
            ->nonQueued();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** The admin form saves 0/0 as the hidden-field default when no pin has been dropped. */
    public function hasCoordinates(): bool
    {
        return $this->latitude !== null
            && $this->longitude !== null
            && ! ((float) $this->latitude === 0.0 && (float) $this->longitude === 0.0);
    }

    /** Used by the dashboard datatable's 'image' column type. */
    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('images', 'resize') ?: ($this->getFirstMediaUrl('images') ?: null);
    }
}
