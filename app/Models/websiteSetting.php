<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class websiteSetting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;
    protected $fillable = ['key', 'value', 'user_id'];

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

    protected $appends = [
        'logo',
        'favicon',
        'formattedCreatedAt'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getLogoAttribute()
    {
        return $this->getFirstMediaUrl('logos');
    }
    public function getFaviconAttribute()
    {
        return $this->getFirstMediaUrl('favicons');
    }
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('F d, Y') : null;
    }

    public static function setSetting($key, $value, $userId = null)
    {
        $old = self::where('key', $key)->first();

        if ($old) {
            $old->value = $value;
            $old->save();
            return;
        }

        $set = new WebsiteSetting();
        $set->key = $key;
        $set->value = $value;
        if ($userId) {
            $set->user_id = $userId;
        } else {
            $set->user_id = Auth()->user()->id;
        }

        $set->save();
    }

    public static function getSetting($key)
    {
        $setting = static::where('key', $key)->first();

        if ($setting) {
            return $setting->value;
        } else {
            return null;
        }
    }
    public static function toArrayWithMedia(): array
    {
        $settings = self::get();
        $arr = [];
        foreach ($settings as $setting) {
            $arr[$setting->key] = $setting->value;
        }
        // Only check media once, from the record that actually has it
        $logoSetting = $settings->first();

        if ($logoSetting) {
            $arr['logo'] = $logoSetting->logo ?? null;
            $arr['favicon'] = $logoSetting->favicon ?? null;
        } else {
            $arr['logo'] = null;
            $arr['favicon'] = null;
        }
        return $arr;
    }
}
