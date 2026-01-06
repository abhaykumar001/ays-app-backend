<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // who performed the action
            $table->string('action'); // e.g., 'created', 'updated', 'deleted'

            // Polymorphic target: which model was affected
            $table->morphs('auditable'); // auditable_type + auditable_id

            $table->json('old_values')->nullable(); // previous data
            $table->json('new_values')->nullable(); // new data

            $table->text('notes')->nullable(); // optional description

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
