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
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();

            // Link to user (if registered)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Interest
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();
            $table->text('message')->nullable();
            $table->string('enquiry_type')->nullable();

            // Status tracking
            $table->enum('status', ['new', 'contacted', 'converted'])->default('new');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'project_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
