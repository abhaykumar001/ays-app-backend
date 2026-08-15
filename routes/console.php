<?php

use App\Models\NotificationCampaign;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Picks up scheduled notification campaigns and sends them. Requires a
// cron entry on the server running `php artisan schedule:run` every
// minute — no persistent queue worker needed.
Schedule::call(function () {
    NotificationCampaign::where('status', 'scheduled')
        ->where('scheduled_at', '<=', now())
        ->get()
        ->each(fn (NotificationCampaign $campaign) => $campaign->dispatchSend());
})->everyMinute()->name('send-scheduled-notification-campaigns')->withoutOverlapping();
