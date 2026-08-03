<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->string('bathrooms', 100)->change();
            $table->string('size_sqft', 100)->change();
            $table->string('floor', 100)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->unsignedTinyInteger('bathrooms')->change();
            $table->integer('size_sqft')->change();
            $table->integer('floor')->nullable()->change();
        });
    }
};
