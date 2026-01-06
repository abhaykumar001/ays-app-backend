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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();

            $table->string('title'); // e.g. "4% DLD Fee Waiver"
            $table->text('description')->nullable();

            $table->enum('type', [
                'discount',
                'dld_waiver',
                'service_charge_waiver',
                'post_handover',
                'furniture',
                'cashback',
                'custom'
            ]);

            $table->decimal('value', 15, 2)->nullable(); // AED amount
            $table->decimal('percentage', 5, 2)->nullable(); // % discount
            $table->string('unit')->nullable(); // AED / % / Months / Years


            $table->json('conditions')->nullable(); 

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'is_active']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
