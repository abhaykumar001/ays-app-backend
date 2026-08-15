<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    // The 4 categories the app's Notification Settings screen exposes.
    public const TYPES = [
        'project_updates',
        'new_listings',
        'news_announcements',
        'promotional_offers',
    ];

    protected $fillable = [
        'user_type',
        'user_id',
        'type',
        'in_app',
        'email',
        'sms',
        'push',
        'mute_from',
        'mute_to',
        'is_enabled',
    ];

    protected $casts = [
        'in_app'     => 'boolean',
        'email'      => 'boolean',
        'sms'        => 'boolean',
        'push'       => 'boolean',
        'is_enabled' => 'boolean',
    ];

    public function user()
    {
        return $this->morphTo();
    }
}
