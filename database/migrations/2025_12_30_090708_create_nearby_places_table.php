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
        Schema::create('nearby_places', function (Blueprint $table) {
            $table->id();

            // Link to community
            $table->foreignId('community_id')
                ->constrained()
                ->cascadeOnDelete();
            // Basic info
            $table->string('name');
            $table->enum('type', ['school', 'mall', 'hospital', 'metro', 'park', 'restaurant', 'other'])->default('other');
            // Distance from community / project (optional)
            $table->string('distance_km');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['community_id']);
            $table->index(['type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nearby_places');
    }
};
