<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class unit extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'project_id',
        'project_phase_id',
        'accommodation_id',
        'unit_number',
        'unit_type',
        'title',
        'slug',
        'bedrooms',
        'bathrooms',
        'parking',
        'size_sqft',
        'plot_size_sqft',
        'price',
        'price_per_sqft',
        'availability_status',
        'floor',
        'view',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_featured',
        'user_id',
        'is_active',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('resize')
            ->format('webp')
            ->performOnCollections('images')
            ->nonQueued();
    }

    public function unitMedia()
    {
        return $this->morphMany(VirtualTour::class, 'tourable');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function amenities()
    {
        return $this->morphToMany(Amenity::class, 'amenable', 'amenables', 'amenable_id', 'amenity_id');
    }

    public function paymentPlans()
    {
        return $this->hasMany(UnitPaymentPlan::class)->orderBy('sort_order');
    }
}
