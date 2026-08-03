<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Community extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'city',
        'short_description',
        'description',
        'is_active',
        'starting_price',
        'roi',
        'growth',
        'category',
        'address',
        'latitude',
        'longitude',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'sort_order',
        'is_featured',
        'user_id',
    ];
    /**
     * The dates attributes
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be append with arrays.
     *
     * @var array
     */
    protected $appends = [
        'image',
        'video',
        'formattedCreatedAt'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
    // image accessor
    public function getImageAttribute()
    {
        return $this->getFirstMedia('images');
    }
    
    public function getVideoAttribute()
    {
        return $this->getFirstMediaUrl('videos');
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('resize')
            ->format('webp')
            ->performOnCollections('images')
            ->nonQueued();
    }
    
    // formatted created at accessor
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('F d, Y') : null;
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function scopeInactive($query)
    {
        return $query->where('is_active',  false);
    }
    public function scopeStatus($query, $status)
    {
        return $query->where('is_active', $status);
    }
    
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }

    public function amenities()
    {
        return $this->morphToMany(Amenity::class, 'amenable', 'amenables', 'amenable_id', 'amenity_id');
    }
    public function nearbyPlaces()
    {
        return $this->hasMany(NearbyPlace::class);
    }
   
}