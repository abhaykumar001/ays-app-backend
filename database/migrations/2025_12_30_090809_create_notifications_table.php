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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            /**
             * Who receives the notification
             * agent / admin / buyer / owner
             */
            $table->morphs('notifiable'); 
            // notifiable_type, notifiable_id

            /**
             * What triggered the notification
             * project / unit / payment / maintenance_request / buyer / owner etc
             */
            $table->nullableMorphs('related');
            // related_type, related_id

            // Notification content
            $table->string('title');
            $table->text('message');

            /**
             * Notification category
             */
            $table->enum('type', [
                'system',
                'payment',
                'maintenance',
                'booking',
                'handover',
                'document',
                'alert'
            ])->default('system');

            /**
             * Channels
             */
            $table->boolean('send_email')->default(false);
            $table->boolean('send_sms')->default(false);
            $table->boolean('send_push')->default(false);

            /**
             * Status
             */
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            /**
             * Priority level
             */
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');

            /**
             * Optional action link
             * Example: /dashboard/payments/12
             */
            $table->string('action_url')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
