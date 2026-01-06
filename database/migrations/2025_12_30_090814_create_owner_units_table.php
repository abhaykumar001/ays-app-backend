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
        Schema::create('owner_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();

            // Purchase info
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->decimal('down_payment', 15, 2)->nullable();
            $table->decimal('remaining_amount', 15, 2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending');
            $table->enum('buyer_type', ['cash', 'installment', 'mortgage'])->default('installment'); // cash or mortgage
            $table->enum('payment_method', ['cash', 'check', 'bank_transfer'])->default('bank_transfer');
            $table->boolean('kyc_completed')->default(false); // KYC verified
            $table->decimal('ownership_percentage', 5, 2)->default(100); // if multiple owners per unit
            $table->string('title_deed_number')->nullable(); // Oqood / Title Deed number
            $table->date('title_deed_date')->nullable(); // Registration date of deed
            $table->string('notary_office')->nullable(); // Notary or Land Department office
            $table->text('special_conditions')->nullable(); // Any special legal conditions
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['owner_id', 'unit_id']); // prevents duplicate assignments
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_units');
    }
};
