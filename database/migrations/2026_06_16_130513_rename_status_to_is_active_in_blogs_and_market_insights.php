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
        Schema::table('blogs', function (Blueprint $table) {
            $table->renameColumn('status', 'is_active');
        });

        Schema::table('market_insights', function (Blueprint $table) {
            $table->renameColumn('status', 'is_active');
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->renameColumn('is_active', 'status');
        });

        Schema::table('market_insights', function (Blueprint $table) {
            $table->renameColumn('is_active', 'status');
        });
    }
};
