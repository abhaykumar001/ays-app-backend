<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// doctrine/dbal is not installed in this project, so enum changes use raw SQL
// (Schema::table()->change() isn't available) — see project_ays_app memory.
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE projects MODIFY project_status ENUM('off_plan','ready','under_construction','handover') NOT NULL DEFAULT 'off_plan'");
        DB::statement("ALTER TABLE projects MODIFY sales_status ENUM('available','sold_out','coming_soon','handover') NOT NULL DEFAULT 'available'");
        DB::statement("ALTER TABLE projects MODIFY price_status ENUM('price','on_request','coming_soon','sold_out','handover') NOT NULL DEFAULT 'price'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE projects MODIFY project_status ENUM('off_plan','ready','under_construction') NOT NULL DEFAULT 'off_plan'");
        DB::statement("ALTER TABLE projects MODIFY sales_status ENUM('available','sold_out','coming_soon') NOT NULL DEFAULT 'available'");
        DB::statement("ALTER TABLE projects MODIFY price_status ENUM('price','on_request','coming_soon','sold_out') NOT NULL DEFAULT 'price'");
    }
};
