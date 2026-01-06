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
        Schema::create('buyers', function (Blueprint $table) {
            $table->id();

            // Personal info
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('nationality')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->string('occupation')->nullable();
            $table->date('dob')->nullable();

            // KYC / legal (optional)
            $table->enum('buyer_type', ['local', 'foreign'])->default('local');
            $table->string('passport_number')->nullable();
            $table->string('visa_status')->nullable();
            $table->string('residency_permit')->nullable();
            $table->string('country_of_origin')->nullable();

            // Preferences
            $table->decimal('budget_min', 15, 2)->nullable();
            $table->decimal('budget_max', 15, 2)->nullable();
            $table->string('preferred_community')->nullable();
            $table->string('preferred_unit_type')->nullable(); // e.g., 1BR, 2BR, Townhouse

            // Agent info
            $table->foreignId('agent_id')->nullable()->constrained('agents')->nullOnDelete();
            $table->string('source')->nullable(); // website, agent, event, referral
            $table->string('channel')->nullable(); // online, offline
            // Status / interest level
            $table->enum('interest_level', ['hot', 'warm', 'cold'])->default('warm');

            // Notes
            $table->text('notes')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyers');
    }
};
