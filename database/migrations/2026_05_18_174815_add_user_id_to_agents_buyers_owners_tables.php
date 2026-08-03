<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('is_active')->constrained('users')->nullOnDelete();
        });

        Schema::table('buyers', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('agent_id')->constrained('users')->nullOnDelete();
        });

        Schema::table('owners', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('kyc_completed')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('buyers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('owners', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
