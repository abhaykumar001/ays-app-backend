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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            /**
             * Polymorphic relation
             * Event can be linked to Project / Phase / Unit
             */
            $table->morphs('eventable'); 
            // eventable_type, eventable_id

            /**
             * Core details
             */
            $table->string('title'); // e.g. "DAMAC Lagoons Launch Event"
            $table->string('slug')->unique();
            $table->enum('type', [
                'launch',
                'open_house',
                'site_visit',
                'broker_meet',
                'webinar',
                'handover',
                'other'
            ])->default('other');

            $table->longText('description')->nullable();

            /**
             * Schedule
             */
            $table->date('event_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            /**
             * Location
             */
            $table->string('venue')->nullable(); // Address / Zoom / Google Meet
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_virtual')->default(false);

            /**
             * Registration & capacity
             */
            $table->boolean('requires_registration')->default(true);
            $table->integer('capacity')->nullable();
            $table->dateTime('registration_deadline')->nullable();

            /**
             * Visibility & status
             */
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])
                ->default('draft');

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_public')->default(true);

            /**
             * Organizer
             */
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /**
             * Sorting
             */
            $table->integer('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            /**
             * Indexes
             */
            $table->index(['event_date', 'status']);
            $table->index(['type', 'is_public']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
