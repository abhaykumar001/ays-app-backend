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
        Schema::create('phases', function (Blueprint $table) {
            $table->id();

            $table->foreignId('project_id')->constrained()->cascadeOnDelete();

            $table->string('name'); // Tower A, Phase 1, Building 2
            $table->string('slug')->unique();
            $table->integer('total_units')->nullable();
            $table->string('bedrooms')->nullable();

            $table->date('launch_date')->nullable();
            $table->date('handover_date')->nullable();
            $table->string('handover')->nullable(); // Q3 2028

            $table->enum('status', ['planned', 'under_construction', 'completed'])
                ->default('planned');

            $table->integer('sort_order')->default(0);

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();

            $table->boolean('is_active')->default(true);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phases');
    }
};
