<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DesignPhilosophy extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'hero_title',
        'hero_title_accent',
        'hero_subtitle',
        'quote',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('resize')
            ->format('webp')
            ->performOnCollections('hero')
            ->nonQueued();
    }

    public function sections()
    {
        return $this->hasMany(DesignPhilosophySection::class, 'philosophy_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    public function allSections()
    {
        return $this->hasMany(DesignPhilosophySection::class, 'philosophy_id')
            ->orderBy('sort_order');
    }

    /** Returns the single active instance, creating it if absent. */
    public static function singleton(): self
    {
        return self::firstOrCreate(['id' => 1], [
            'hero_title'        => 'Our Design',
            'hero_title_accent' => 'Philosophy',
            'hero_subtitle'     => 'Where timeless elegance meets modern innovation in the heart of Dubai.',
            'quote'             => 'Crafting icons in the Dubai skyline. We merge timeless elegance with modern innovation to create living spaces that transcend the ordinary.',
            'is_active'         => true,
        ]);
    }
}
