<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->text('title_description')->nullable()->after('short_description');
            $table->text('quote_description')->nullable()->after('title_description');
            $table->string('materiality_title')->nullable()->after('quote_description');
            $table->text('materiality_description')->nullable()->after('materiality_title');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'title_description',
                'quote_description',
                'materiality_title',
                'materiality_description',
            ]);
        });
    }
};
