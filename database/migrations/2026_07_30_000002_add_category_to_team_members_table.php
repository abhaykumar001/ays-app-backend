<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->foreignId('team_member_category_id')
                ->nullable()
                ->after('slug')
                ->constrained('team_member_categories')
                ->nullOnDelete();
        });

        $designations = DB::table('team_members')
            ->whereNotNull('designation')
            ->where('designation', '!=', '')
            ->distinct()
            ->pluck('designation');

        $sortOrder = 0;
        foreach ($designations as $designation) {
            $categoryId = DB::table('team_member_categories')->insertGetId([
                'name'       => $designation,
                'sort_order' => $sortOrder++,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('team_members')
                ->where('designation', $designation)
                ->update(['team_member_category_id' => $categoryId]);
        }

        Schema::table('team_members', function (Blueprint $table) {
            $table->dropColumn('designation');
        });
    }

    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->string('designation')->nullable()->after('slug');
        });

        DB::table('team_members')
            ->join('team_member_categories', 'team_members.team_member_category_id', '=', 'team_member_categories.id')
            ->update(['team_members.designation' => DB::raw('team_member_categories.name')]);

        Schema::table('team_members', function (Blueprint $table) {
            $table->dropConstrainedForeignId('team_member_category_id');
        });
    }
};
