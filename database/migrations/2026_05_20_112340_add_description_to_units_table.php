<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
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
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn(array_filter([
                Schema::hasColumn('units', 'description')     ? 'description'     : null,
                Schema::hasColumn('units', 'meta_title')      ? 'meta_title'      : null,
                Schema::hasColumn('units', 'meta_keywords')   ? 'meta_keywords'   : null,
                Schema::hasColumn('units', 'meta_description') ? 'meta_description' : null,
            ]));
        });
    }
};
