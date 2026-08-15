<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class NotificationCampaign extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    public const TARGET_TYPES = ['all', 'role'];
    public const DEEP_LINK_TYPES = ['none', 'project', 'offer', 'event', 'url'];
    public const TYPES = [
        'general', 'project_update', 'offer', 'event',
        'handover', 'construction_update', 'maintenance', 'new_listing', 'system',
    ];

    // Maps a campaign's free-form `type` to the 4 categories the app's
    // Notification Settings screen exposes, so an opted-out user is skipped.
    public const SETTINGS_TYPE_MAP = [
        'project_update'       => 'project_updates',
        'handover'              => 'project_updates',
        'construction_update'   => 'project_updates',
        'maintenance'            => 'project_updates',
        'new_listing'            => 'new_listings',
        'offer'                  => 'promotional_offers',
        'general'                => 'news_announcements',
        'system'                 => 'news_announcements',
        'event'                  => 'news_announcements',
    ];

    protected $fillable = [
        'title',
        'message',
        'type',
        'target',
        'roles',
        'deep_link_type',
        'deep_link_value',
        'priority',
        'status',
        'scheduled_at',
        'sent_at',
        'total_recipients',
        'delivered_count',
        'failed_count',
        'created_by',
    ];

    protected $casts = [
        'roles'        => 'array',
        'scheduled_at' => 'datetime',
        'sent_at'      => 'datetime',
    ];

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'campaign_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->useDisk('public')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('resize')
            ->format('webp')
            ->performOnCollections('image')
            ->nonQueued();
    }

    public function settingsType(): string
    {
        return self::SETTINGS_TYPE_MAP[$this->type] ?? 'news_announcements';
    }

    // Accessors consumed by the <x-datatable> component, which reads
    // columns via object property access ($item->key) — not usable with a
    // plain mapped array, so these live on the model instead.
    public function getTypeLabelAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', $this->type));
    }

    public function getTargetLabelAttribute(): string
    {
        return $this->target === 'all' ? 'All Users' : implode(', ', $this->roles ?? []);
    }

    public function getWhenLabelAttribute(): string
    {
        return $this->sent_at?->format('d M Y, h:i A')
            ?? $this->scheduled_at?->format('d M Y, h:i A')
            ?? '—';
    }

    /**
     * Resolve the recipient users for this campaign (all users with at
     * least one device token, or users holding one of the picked roles).
     */
    public function resolveRecipients()
    {
        $query = User::query()->whereHas('deviceTokens');

        if ($this->target === 'role' && ! empty($this->roles)) {
            $query->role($this->roles);
        }

        return $query->get();
    }

    /**
     * Drop users who explicitly opted out of push for this campaign's
     * mapped settings category. No row for a user+type = still enabled.
     */
    protected function filterOptedIn($users)
    {
        $type = $this->settingsType();

        $optedOut = NotificationSetting::query()
            ->where('user_type', User::class)
            ->whereIn('user_id', $users->pluck('id'))
            ->where('type', $type)
            ->where(function ($q) {
                $q->where('is_enabled', false)->orWhere('push', false);
            })
            ->pluck('user_id')
            ->all();

        return $users->reject(fn (User $user) => in_array($user->id, $optedOut, true));
    }

    /**
     * Fan out to recipients, create per-user notification rows, and send
     * the actual push via FCM. Single code path used by both "Send Now"
     * and the scheduler tick.
     */
    public function dispatchSend(): void
    {
        $this->update(['status' => 'sending']);

        try {
            $recipients = $this->filterOptedIn($this->resolveRecipients());
        } catch (\Throwable $e) {
            // Only a failure to even resolve who should receive this
            // campaign is fatal to the whole send — nothing was created.
            report($e);
            $this->update(['status' => 'failed']);
            return;
        }

        $totalDelivered = 0;
        $totalFailed = 0;

        foreach ($recipients as $user) {
            $notification = $this->notifications()->create([
                'notifiable_type' => User::class,
                'notifiable_id'   => $user->id,
                'title'           => $this->title,
                'message'         => $this->message,
                'type'            => $this->type,
                'deep_link_type'  => $this->deep_link_type,
                'deep_link_value' => $this->deep_link_value,
                'send_push'       => true,
                'priority'        => $this->priority,
            ]);

            $tokens = $user->deviceTokens()->pluck('token')->all();
            if (empty($tokens)) {
                continue;
            }

            // One recipient's send failing (bad credentials, network blip,
            // stale token) must never abort the rest of the batch.
            try {
                [$delivered, $failed] = $this->sendToTokens($tokens, $notification);
            } catch (\Throwable $e) {
                report($e);
                [$delivered, $failed] = [0, count($tokens)];
            }

            $totalDelivered += $delivered;
            $totalFailed += $failed;
        }

        $this->update([
            'status'           => 'sent',
            'sent_at'          => now(),
            'total_recipients' => $recipients->count(),
            'delivered_count'  => $totalDelivered,
            'failed_count'     => $totalFailed,
        ]);
    }

    /**
     * @return array{0:int,1:int} [deliveredCount, failedCount]
     */
    protected function sendToTokens(array $tokens, Notification $notification): array
    {
        $delivered = 0;
        $failed = 0;

        foreach (array_chunk($tokens, 500) as $chunk) {
            $message = CloudMessage::new()
                ->withNotification([
                    'title' => $this->title,
                    'body'  => $this->message,
                ])
                ->withData([
                    'notification_id' => (string) $notification->id,
                    'deep_link_type'  => (string) ($this->deep_link_type ?? 'none'),
                    'deep_link_value' => (string) ($this->deep_link_value ?? ''),
                ]);

            $report = Firebase::messaging()->sendMulticast($message, $chunk);

            $delivered += $report->successes()->count();
            $failed += $report->failures()->count();

            $staleTokens = array_merge($report->unknownTokens(), $report->invalidTokens());
            if (! empty($staleTokens)) {
                DeviceToken::whereIn('token', $staleTokens)->delete();
            }
        }

        return [$delivered, $failed];
    }
}
