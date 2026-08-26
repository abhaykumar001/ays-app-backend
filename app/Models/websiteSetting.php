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
        'login_banner',
        'partnership_hero_video',
        'welcome_video',
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
    public function getLoginBannerAttribute()
    {
        return $this->getFirstMediaUrl('login_banners');
    }
    public function getPartnershipHeroVideoAttribute()
    {
        return $this->getFirstMediaUrl('partnership_hero_videos');
    }
    public function getWelcomeVideoAttribute()
    {
        return $this->getFirstMediaUrl('welcome_videos');
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
            $arr['login_banner'] = $logoSetting->login_banner ?? null;
            $arr['partnership_hero_video'] = $logoSetting->partnership_hero_video ?? null;
            $arr['welcome_video'] = $logoSetting->welcome_video ?? null;
        } else {
            $arr['logo'] = null;
            $arr['favicon'] = null;
            $arr['login_banner'] = null;
            $arr['partnership_hero_video'] = null;
            $arr['welcome_video'] = null;
        }
        return $arr;
    }
}
