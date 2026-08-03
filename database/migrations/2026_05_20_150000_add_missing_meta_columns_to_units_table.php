<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            if (!Schema::hasColumn('units', 'project_phase_id')) {
                $table->foreignId('project_phase_id')->nullable()->constrained('phases')->nullOnDelete()->after('project_id');
            }
            if (!Schema::hasColumn('units', 'parking')) {
                $table->unsignedTinyInteger('parking')->nullable()->after('bathrooms');
            }
            if (!Schema::hasColumn('units', 'plot_size_sqft')) {
                $table->integer('plot_size_sqft')->nullable()->after('size_sqft');
            }
            if (!Schema::hasColumn('units', 'price_per_sqft')) {
                $table->decimal('price_per_sqft', 10, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('units', 'floor')) {
                $table->integer('floor')->nullable()->after('availability_status');
            }
            if (!Schema::hasColumn('units', 'view')) {
                $table->string('view')->nullable()->after('floor');
            }
            if (!Schema::hasColumn('units', 'description')) {
                $table->longText('description')->nullable()->after('view');
            }
            if (!Schema::hasColumn('units', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('description');
            }
            if (!Schema::hasColumn('units', 'meta_keywords')) {
                $table->string('meta_keywords')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('units', 'meta_description')) {
                $table->longText('meta_description')->nullable()->after('meta_keywords');
            }
            if (!Schema::hasColumn('units', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('meta_description');
            }
            if (!Schema::hasColumn('units', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('is_featured');
            }
            if (!Schema::hasColumn('units', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('is_active');
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('units', 'deleted_at')) {
                $table->softDeletes()->after('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $columns = [
                'project_phase_id', 'parking', 'plot_size_sqft', 'price_per_sqft',
                'floor', 'view', 'description', 'meta_title', 'meta_keywords',
                'meta_description', 'is_featured', 'is_active', 'user_id', 'deleted_at',
            ];
            $existing = array_values(array_filter($columns, fn($col) => Schema::hasColumn('units', $col)));
            if ($existing) {
                $table->dropColumn($existing);
            }
        });
    }
};
