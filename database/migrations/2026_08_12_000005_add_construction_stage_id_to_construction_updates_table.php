<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('construction_updates', function (Blueprint $table) {
            $table->foreignId('construction_stage_id')
                ->nullable()
                ->after('stage')
                ->constrained('construction_stages')
                ->nullOnDelete();
        });

        // Backfill construction_stage_id from the old free-text/enum `stage`
        // column by matching names case-insensitively against the stages
        // seeded in the previous migration.
        $stages = DB::table('construction_stages')->pluck('id', 'name');
        foreach ($stages as $name => $id) {
            DB::table('construction_updates')
                ->whereRaw('LOWER(stage) = ?', [strtolower($name)])
                ->update(['construction_stage_id' => $id]);
        }

        Schema::table('construction_updates', function (Blueprint $table) {
            $table->dropColumn('stage');
            $table->unique(
                ['updatable_type', 'updatable_id', 'construction_stage_id'],
                'construction_updates_updatable_stage_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('construction_updates', function (Blueprint $table) {
            $table->dropUnique('construction_updates_updatable_stage_unique');
            $table->enum('stage', [
                'foundation',
                'structure',
                'facade',
                'interior',
                'finishing',
                'handover',
            ])->nullable()->after('update_date');
        });

        $stages = DB::table('construction_stages')->pluck('name', 'id');
        foreach ($stages as $id => $name) {
            DB::table('construction_updates')
                ->where('construction_stage_id', $id)
                ->update(['stage' => strtolower($name)]);
        }

        Schema::table('construction_updates', function (Blueprint $table) {
            $table->dropConstrainedForeignId('construction_stage_id');
        });
    }
};
