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
        Schema::create('construction_updates', function (Blueprint $table) {
            $table->id();

            $table->morphs('updatable'); 
            $table->string('title'); 
            $table->longText('description')->nullable();
            $table->tinyInteger('progress_percentage')->nullable(); // 0–100
            $table->date('update_date')->nullable();

            $table->enum('stage', [
                'foundation',
                'structure',
                'facade',
                'interior',
                'finishing',
                'handover'
            ])->nullable();
            $table->text('link')->nullable(); // website visibility
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active']);
            $table->index(['progress_percentage', 'update_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('construction_updates');
    }
};
