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
        Schema::create('maintanance_requests', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('unit_id')->nullable()->constrained()->cascadeOnDelete(); // Optional: specific unit
            $table->foreignId('owner_id')->constrained()->cascadeOnDelete(); // Owner who requested
            $table->foreignId('service_id')->constrained('maintanances')->cascadeOnDelete(); // Service requested

            // Request details
            $table->text('description')->nullable(); // Owner’s notes or problem description
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->string('assigned_to')->nullable(); // Technician or team assigned
            $table->dateTime('scheduled_at')->nullable(); // Scheduled date/time for the service
            $table->dateTime('completed_at')->nullable(); // When service is completed

            // Optional fields
            $table->decimal('estimated_cost', 15, 2)->nullable(); // Estimated cost of service
            $table->text('materials_used')->nullable(); // Materials actually used
            $table->text('special_instructions')->nullable(); // Any notes from technician

            $table->boolean('is_urgent')->default(false); // Flag for urgent requests
            $table->enum('priority_level', ['low', 'medium', 'high'])->default('medium'); // Priority level

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['status', 'scheduled_at', 'is_urgent']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintanance_requests');
    }
};
