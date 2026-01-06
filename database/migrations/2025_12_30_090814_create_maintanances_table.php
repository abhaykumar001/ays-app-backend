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
        Schema::create('maintanances', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of service, e.g., "Plumbing", "Electrical Repair"
            $table->string('slug')->unique(); // Name of service, e.g., "Plumbing", "Electrical Repair"
            $table->longText('description')->nullable(); // Description of the service
            $table->decimal('default_cost', 15, 2)->nullable(); // Optional default cost
            $table->string('estimated_duration')->nullable(); // e.g., "2 hours", "1 day"
            $table->text('required_materials')->nullable(); // e.g., "Pipe, Valve, Sealant"
            $table->longText('special_instructions')->nullable();
            $table->boolean('is_active')->default(true); // Active or inactive service
            $table->integer('sort_order')->default(0); // For ordering in frontend
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintanances');
    }
};
