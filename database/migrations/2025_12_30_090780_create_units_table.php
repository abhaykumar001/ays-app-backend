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
        Schema::create('units', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_phase_id')->nullable()->constrained('phases')->nullOnDelete();
            $table->foreignId('accommodation_id')->constrained()->cascadeOnDelete();

            // Unit identity
            $table->string('unit_code')->nullable()->unique(); // A-1203
            $table->string('title'); // 2 Bed Apartment
            $table->string('slug')->unique();

            // Configuration
            $table->unsignedTinyInteger('bedrooms'); // 1,2,3,4
            $table->unsignedTinyInteger('bathrooms');
            $table->unsignedTinyInteger('parking')->nullable();

            // Size
            $table->integer('size_sqft');
            $table->integer('plot_size_sqft')->nullable(); // villas / townhouses

            // Pricing
            $table->decimal('price', 15, 2);
            $table->decimal('price_per_sqft', 10, 2)->nullable();

            // Availability
            $table->enum('availability_status', ['available', 'reserved', 'sold'])
                ->default('available');

            // Floor info
            $table->integer('floor')->nullable();
            $table->string('view')->nullable(); // Marina, Sea, Park
             $table->longText('description')->nullable();
            $table->string('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            // Flags
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();

            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['project_id', 'accommodation_id']);
            $table->index(['bedrooms', 'availability_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
