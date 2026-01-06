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
        Schema::create('highlights', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            // Content
            $table->string('title');                // e.g. "Luxury Waterfront Living"
            $table->text('description')->nullable();

            // Display
            $table->boolean('is_featured')->default(false); 
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Indexes
            $table->index(['project_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('highlights');
    }
};
