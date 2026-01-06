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
        Schema::create('amenables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('amenity_id')->constrained()->cascadeOnDelete();
            $table->morphs('amenable'); // amenable_type + amenable_id
            $table->boolean('is_featured')->default(false); // featured per entity
            $table->timestamps();

            $table->unique(['amenity_id', 'amenable_type', 'amenable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenables');
    }
};
