<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'campaign_id',
        'notifiable_type',
        'notifiable_id',
        'title',
        'message',
        'type',
        'deep_link_type',
        'deep_link_value',
        'send_email',
        'send_sms',
        'send_push',
        'is_read',
        'read_at',
        'priority',
    ];

    protected $casts = [
        'send_email' => 'boolean',
        'send_sms'   => 'boolean',
        'send_push'  => 'boolean',
        'is_read'    => 'boolean',
        'read_at'    => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(NotificationCampaign::class, 'campaign_id');
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function markRead(): void
    {
        if (! $this->is_read) {
            $this->update(['is_read' => true, 'read_at' => now()]);
        }
    }
}
