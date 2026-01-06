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
        Schema::create('viewings', function (Blueprint $table) {
            $table->id();

            // Link to user (if registered)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Link to project/unit
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();

            // Viewing details
            $table->enum('viewing_type', ['in_person', 'virtual', 'video_call'])->default('in_person');
            $table->dateTime('scheduled_at')->nullable();
            $table->text('notes')->nullable(); // optional notes

            // Status tracking
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'project_id', 'viewing_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viewings');
    }
};
