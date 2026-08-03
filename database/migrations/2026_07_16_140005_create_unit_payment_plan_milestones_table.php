<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_payment_plan_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_payment_plan_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('month_offset'); // "within N months of sale date"
            $table->decimal('percent', 5, 2);
            $table->boolean('is_amount_manual')->default(false);
            $table->decimal('amount', 15, 2)->nullable(); // used only when is_amount_manual
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['unit_payment_plan_id', 'sort_order'], 'upp_milestones_plan_sort_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_payment_plan_milestones');
    }
};
