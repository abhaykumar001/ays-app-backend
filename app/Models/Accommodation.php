<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Accommodation extends Model  implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;
    protected $fillable = ['name', 'is_active'];
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
        'icon',
        'formattedCreatedAt'
    ];

    public function getIconAttribute()
    {
        return $this->getFirstMedia('icons');
    }

    // formatted created at accessor
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('F d, Y') : null;
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

}
