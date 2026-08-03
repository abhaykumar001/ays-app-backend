<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->renameColumn('unit_code', 'unit_number');
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->renameColumn('unit_number', 'unit_code');
        });
    }
};
