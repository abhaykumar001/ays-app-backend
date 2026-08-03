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

class Project extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'project_code',
        'project_status',
        'sales_status',
        'community_id',
        'sub_community',
        'city',
        'address',
        'latitude',
        'longitude',
        'starting_price',
        'price_per_sqft',
        'total_units',
        'available_units',
        'construction_progress',
        'roi',
        'ownership_type',
        'bedrooms',
        'bathrooms',
        'min_size',
        'max_size',
        'launch_date',
        'handover',
        'handover_date',
        'short_description',
        'title_description',
        'quote_description',
        'materiality_title',
        'materiality_description',
        'description',
        'is_featured',
        'is_new_launch',
        'is_hot_selling',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'sort_order',
        'is_active',
        'user_id',
        'virtual_tour_url',
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
    public function community()
    {
        return $this->belongsTo(Community::class);
    }
    public function phases()
    {
        return $this->hasMany(Phase::class);
    }
    public function highlights()
    {
        return $this->hasMany(Highlight::class);
    }
    public function units()
    {
        return $this->hasMany(unit::class);
    }
    public function virtualTours()
    {
        return $this->morphMany(VirtualTour::class, 'tourable');
    }
    public function constructionUpdates()
    {
        return $this->morphMany(ConstructionUpdate::class, 'updatable');
    }
    public function paymentPlans()
    {
        return $this->hasMany(PaymentPlan::class);
    }
    public function offers()
    {
        return $this->hasMany(ProjectOffer::class);
    }

    public function amenities()
    {
        return $this->morphToMany(Amenity::class, 'amenable', 'amenables', 'amenable_id', 'amenity_id');
    }
    public function accommodations()
    {
        return $this->belongsToMany(Accommodation::class, 'project_accommodations', 'project_id', 'accommodation_id');
    }
}
