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
        Schema::create('construction_stages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // Seed the same 6 stages the old hardcoded DB enum allowed, so
        // existing construction_updates rows can be backfilled 1:1 in the
        // next migration without losing data.
        $now = now();
        DB::table('construction_stages')->insert([
            ['name' => 'Foundation', 'sort_order' => 0, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Structure', 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Facade', 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Interior', 'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Finishing', 'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Handover', 'sort_order' => 5, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('construction_stages');
    }
};
