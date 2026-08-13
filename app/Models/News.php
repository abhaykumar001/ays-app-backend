<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Tonysm\RichTextLaravel\Models\Traits\HasRichText;

class News extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasSlug, HasRichText;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'author',
        'is_featured',
        'show_on_ays_screen',
        'is_active',
        'published_at',
        'user_id',
    ];

    protected $richTextAttributes = ['description'];

    protected $casts = [
        'published_at' => 'date',
        'is_featured'  => 'boolean',
        'show_on_ays_screen' => 'boolean',
        'is_active'    => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
