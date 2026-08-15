<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // notifications.priority was ['low','medium','high','urgent'] but
        // notification_campaigns.priority (and the rest of the app's
        // conventions, e.g. the old announcements table) use 'normal'
        // instead of 'medium'. Widen to a plain string so the two stay
        // trivially compatible instead of silently truncating inserts.
        DB::statement('ALTER TABLE notifications MODIFY priority VARCHAR(20) NOT NULL DEFAULT \'normal\'');
        DB::statement("UPDATE notifications SET priority = 'normal' WHERE priority = 'medium'");
    }

    public function down(): void
    {
        DB::statement("UPDATE notifications SET priority = 'medium' WHERE priority = 'normal'");
        DB::statement("ALTER TABLE notifications MODIFY priority ENUM('low','medium','high','urgent') NOT NULL DEFAULT 'medium'");
    }
};
