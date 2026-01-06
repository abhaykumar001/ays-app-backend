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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            /**
             * Associations
             */
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();

            $table->foreignId('buyer_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('agent_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            /**
             * Booking reference
             */
            $table->string('booking_number')->unique(); // e.g. BK-2025-000123

            /**
             * Status lifecycle
             */
            $table->enum('status', [
                'reserved',        // unit blocked
                'confirmed',       // initial payment done
                'under_contract',  // SPA signed
                'completed',       // ownership transferred
                'cancelled',
                'expired'
            ])->default('reserved');

            /**
             * Dates
             */
            $table->date('booking_date')->default(now());
            $table->date('expiry_date')->nullable(); // auto release if unpaid
            $table->date('contract_signed_at')->nullable();
            $table->date('handover_date')->nullable();

            /**
             * Financials
             */
            $table->decimal('unit_price', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('final_price', 15, 2);
            $table->decimal('booking_amount', 15, 2)->nullable(); // token amount

            $table->foreignId('payment_plan_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            /**
             * Payment status
             */
            $table->enum('payment_status', [
                'pending',
                'partial',
                'paid',
                'refunded'
            ])->default('pending');

            /**
             * Sales metadata
             */
            
            $table->text('notes')->nullable();

            /**
             * Conversion
             */
            $table->foreignId('owner_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete(); // set once ownership is created

            /**
             * Audit
             */
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            /**
             * Indexes
             */
            $table->index(['status', 'payment_status']);
            $table->index(['unit_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
