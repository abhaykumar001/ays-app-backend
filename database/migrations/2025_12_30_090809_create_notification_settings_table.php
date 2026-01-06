<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();

            // Who this setting belongs to
            $table->morphs('user');

            // Notification type (matching UI)
            $table->enum('type', [
                'project_updates',       // Project Updates
                'new_listings',          // New Listings
                'news_announcements',    // News & Announcements
                'promotional_offers',    // Promotional Offers
            ]);

            // Channel toggles
            $table->boolean('in_app')->default(true);
            $table->boolean('email')->default(true);
            $table->boolean('sms')->default(false);
            $table->boolean('push')->default(false);

            // Optional quiet hours
            $table->time('mute_from')->nullable();
            $table->time('mute_to')->nullable();

            // Master status
            $table->boolean('is_enabled')->default(true);

            $table->timestamps();

            // Ensure one setting per type per user
            $table->unique(['user_type', 'user_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
