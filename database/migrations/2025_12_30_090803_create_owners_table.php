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
        Schema::create('owners', function (Blueprint $table) {
            $table->id();

            // Personal info
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('nationality');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed']);
            $table->string('occupation');
            $table->date('dob');

            // Address info
            $table->string('address');
            $table->string('city');
            $table->string('country');

            // Legal / documents
            $table->enum('owner_type', ['local', 'foreign'])->default('local'); // local or foreign
            $table->string('passport_number')->nullable(); // foreign passport
            $table->string('visa_status')->nullable();    // visa details
            $table->string('residency_permit')->nullable(); // optional UAE residency ID
            $table->string('country_of_origin')->nullable(); // for foreign nationals
            $table->boolean('kyc_completed')->default(false); // KYC verified
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owners');
    }
};
