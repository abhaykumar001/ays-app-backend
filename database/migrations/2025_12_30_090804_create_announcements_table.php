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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->longText('message');

            $table->enum('type', [
                'general',
                'project_update',
                'handover',
                'maintenance',
                'offer',
                'construction_update',
                'system'
            ])->default('general');

            $table->enum('audience', [
                'public',
                'owners',
                'buyers',
                'agents',
                'internal'
            ])->default('public');


            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->boolean('is_popup')->default(false);
            $table->boolean('is_active')->default(true);

            $table->dateTime('publish_at')->nullable();
            $table->dateTime('expire_at')->nullable();

            $table->string('cta_text')->nullable();
            $table->string('cta_url')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            /**
             * Indexes
             */
            $table->index(['type', 'audience', 'is_active']);
            $table->index(['publish_at', 'expire_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
