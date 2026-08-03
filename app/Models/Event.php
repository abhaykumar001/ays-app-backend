<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Event extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, HasSlug;

    protected $fillable = [
        'eventable_type',
        'eventable_id',
        'title',
        'slug',
        'type',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'venue',
        'latitude',
        'longitude',
        'is_virtual',
        'requires_registration',
        'capacity',
        'registration_deadline',
        'status',
        'is_featured',
        'is_public',
        'created_by',
        'sort_order',
    ];

    protected $casts = [
        'event_date'            => 'date',
        'registration_deadline' => 'datetime',
        'is_virtual'            => 'boolean',
        'requires_registration' => 'boolean',
        'is_featured'           => 'boolean',
        'is_public'             => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function eventable()
    {
        return $this->morphTo();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function requests()
    {
        return $this->hasMany(EventRequest::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->toDateString());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')->singleFile();
        $this->addMediaCollection('videos')->singleFile();
        $this->addMediaCollection('thumbnail')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('resize')
            ->format('webp')
            ->performOnCollections('images', 'thumbnail')
            ->nonQueued();
    }
}
