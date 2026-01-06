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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // Identity
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('project_code')->nullable()->unique();

            // Status
            $table->enum('project_status', ['off_plan', 'ready', 'under_construction'])->default('off_plan');
            $table->enum('sales_status', ['available', 'sold_out', 'coming_soon'])->default('available');

            // Location
            $table->unsignedBigInteger('community_id');
            $table->foreign('community_id')->references('id')->on('communities')->cascadeOnDelete();
            $table->string('sub_community')->nullable();
            $table->string('city')->default('Dubai');
            $table->string('address')->nullable();

            // Map
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Cached pricing & size
            $table->decimal('starting_price', 15, 2)->nullable();
            $table->decimal('price_per_sqft', 10, 2)->nullable();
            $table->integer('total_units')->nullable();
            $table->integer('available_units')->nullable();
            $table->tinyInteger('construction_progress')->nullable(); // 0–100
            $table->decimal('roi', 5, 2)->nullable();
            $table->string('ownership_type')->nullable();

            // Sizes
            $table->string('bedrooms')->nullable();
            $table->integer('min_size')->nullable();
            $table->integer('max_size')->nullable();

            // Timeline
            $table->date('launch_date')->nullable();
            $table->string('handover')->nullable();
            $table->date('handover_date')->nullable();

            // Content
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            // Flags
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new_launch')->default(false);
            $table->boolean('is_hot_selling')->default(false);

            // SEO
            $table->string('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            // Sorting & metrics
            $table->integer('sort_order')->default(0);
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('enquiries_count')->default(0);

            // Ownership
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();

            $table->boolean('is_active')->default(true);

            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index(['project_status', 'sales_status', 'is_active']);
            $table->index(['is_featured', 'is_new_launch', 'is_hot_selling']);
            $table->index('community_id');
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
