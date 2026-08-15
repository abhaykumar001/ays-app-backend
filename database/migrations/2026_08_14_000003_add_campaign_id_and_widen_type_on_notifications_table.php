<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreignId('campaign_id')->nullable()->after('id')
                ->constrained('notification_campaigns')->nullOnDelete();

            // Deep-link target for this specific recipient's copy of the
            // notification. Not stored via the existing `related` morph
            // columns because targets are looked up by slug (projects,
            // events) as well as by id (offers) — a plain string keeps
            // both shapes working without a polymorphic PK mismatch.
            $table->string('deep_link_type')->nullable()->after('type');
            $table->string('deep_link_value')->nullable()->after('deep_link_type');
        });

        // Widen the fixed `type` enum to a free-form string so it can hold the
        // same categories as notification_campaigns.type without ever needing
        // another enum-altering migration. Raw SQL avoids a doctrine/dbal
        // dependency just for this one column change.
        DB::statement('ALTER TABLE notifications MODIFY type VARCHAR(50) NOT NULL DEFAULT \'system\'');
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('campaign_id');
            $table->dropColumn(['deep_link_type', 'deep_link_value']);
        });

        DB::statement("ALTER TABLE notifications MODIFY type ENUM('system','payment','maintenance','booking','handover','document','alert') NOT NULL DEFAULT 'system'");
    }
};
