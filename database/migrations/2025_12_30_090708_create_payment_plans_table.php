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
        Schema::create('payment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('title'); // e.g., "3-Year Payment Plan"
            $table->json('payment_breakdown')->nullable();
            $table->decimal('down_payment', 15, 2)->nullable();
            $table->decimal('total_price', 15, 2)->nullable();
            $table->text('description')->nullable();
            $table->json('installments')->nullable(); // e.g., [{"month":1,"amount":50000}]
            $table->boolean('is_active')->default(true);
            $table->boolean('is_offer')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['project_id', 'is_active', 'is_offer']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_plans');
    }
};
