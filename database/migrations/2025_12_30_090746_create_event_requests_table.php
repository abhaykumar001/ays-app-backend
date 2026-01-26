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
        Schema::create('event_requests', function (Blueprint $table) {
            $table->id();

            /**
             * Who is requesting the event
             */
            $table->foreignId('requested_by')
                ->constrained('users')
                ->cascadeOnDelete();

            /**
             * Requested entity
             * Project / Phase / Unit
             */
            $table->morphs('eventable');
            // eventable_type, eventable_id

            /**
             * Event details (proposal)
             */
            $table->string('title');
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
             * Proposed schedule
             */
            $table->date('proposed_date');
            $table->time('proposed_start_time')->nullable();
            $table->time('proposed_end_time')->nullable();

            /**
             * Location
             */
            $table->string('venue')->nullable();
            $table->boolean('is_virtual')->default(false);
            $table->string('virtual_link')->nullable();

            /**
             * Expected audience
             */
            $table->integer('expected_attendees')->nullable();
            $table->boolean('requires_registration')->default(true);

            /**
             * Approval flow
             */
            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending');

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('admin_notes')->nullable();

            /**
             * Conversion
             */
            $table->foreignId('event_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete(); // set after approval

            $table->timestamps();

            /**
             * Indexes
             */
            $table->index(['status', 'proposed_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_requests');
    }
};
