<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('design_philosophies', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title')->default('Our Design');
            $table->string('hero_title_accent')->default('Philosophy');
            $table->string('hero_subtitle')->nullable();
            $table->text('quote')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('design_philosophy_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('philosophy_id')->constrained('design_philosophies')->cascadeOnDelete();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('design_philosophy_sections');
        Schema::dropIfExists('design_philosophies');
    }
};
