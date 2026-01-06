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
        Schema::create('language_preferences', function (Blueprint $table) {
            $table->id();

            /**
             * Who this preference belongs to
             * Admin / Agent / Buyer / Owner
             */
            $table->morphs('user'); // creates user_type and user_id

            /**
             * Selected language
             * Must match supported languages in the app
             */
            $table->string('language')->default('en');

            /**
             * Optional: if this is the preferred language shown first
             */
            $table->boolean('is_preferred')->default(true);

            $table->timestamps();

            // Ensure one preference per user
            $table->unique(['user_type', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('language_preferences');
    }
};
