<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Tonysm\RichTextLaravel\Models\Traits\HasRichText;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Blog extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, HasRichText, HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'is_active',
        'is_featured',
        'author',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published_at',
        'user_id',
    ];
    /**
     * The dates attributes
     *
     * @var array
     */
    protected $dates = [
        'published_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    /**
     * The richtext attributes
     *
     * @var array
     */
    protected $richTextAttributes = [
        'description'
    ];

    /**
     * The attributes that should be append with arrays.
     *
     * @var array
     */
    protected $appends = [
        'image',
        'formattedPublishAt',
        'formattedCreatedAt'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
    // image accessor
    public function getImageAttribute()
    {
        return $this->getFirstMedia('images');
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('resize')
            ->format('webp')
            ->performOnCollections('images')
            ->nonQueued();
    }
    public function getFormattedPublishAtAttribute()
    {
        return $this->published_at ? $this->published_at->format('F d, Y') : null;
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
        return $query->where('is_active', false);
    }
    public function scopeStatus($query, $status)
    {
        return $query->where('is_active', $status);
    }
    
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_tags', 'blog_id', 'tag_id');
    }
}