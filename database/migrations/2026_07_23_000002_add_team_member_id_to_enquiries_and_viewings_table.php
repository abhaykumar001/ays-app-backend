<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->foreignId('team_member_id')->nullable()->after('unit_id')
                ->constrained('team_members')->nullOnDelete();
        });

        Schema::table('viewings', function (Blueprint $table) {
            $table->foreignId('team_member_id')->nullable()->after('unit_id')
                ->constrained('team_members')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('team_member_id');
        });

        Schema::table('viewings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('team_member_id');
        });
    }
};
