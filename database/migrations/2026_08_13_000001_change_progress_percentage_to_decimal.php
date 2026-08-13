<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Raw SQL (not Schema::table()->change()) — doctrine/dbal isn't installed
     * in this project, so the fluent column-change API isn't available.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE construction_updates MODIFY progress_percentage DECIMAL(5,2) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE construction_updates MODIFY progress_percentage TINYINT NULL');
    }
};
