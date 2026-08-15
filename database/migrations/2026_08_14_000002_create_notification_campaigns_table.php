<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_campaigns', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('message');

            // Free-form (validated in FormRequest, not a DB enum) so new
            // categories never need a migration.
            $table->string('type')->default('general');

            $table->enum('target', ['all', 'role'])->default('all');
            $table->json('roles')->nullable();

            // Deep link — where tapping the notification should take the user.
            $table->string('deep_link_type')->nullable(); // none/project/offer/event/url
            $table->string('deep_link_value')->nullable(); // slug/id, or the raw URL

            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('status', ['draft', 'scheduled', 'sending', 'sent', 'failed'])->default('draft');

            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('sent_at')->nullable();

            $table->unsignedInteger('total_recipients')->default(0);
            $table->unsignedInteger('delivered_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'scheduled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_campaigns');
    }
};
