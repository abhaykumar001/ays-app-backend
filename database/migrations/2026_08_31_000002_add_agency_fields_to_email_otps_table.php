<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Temporary holding fields, mirrored onto the User row in
     * AuthController::verifyOtp() once the OTP is confirmed — same pattern
     * as the existing phone/role/plain_password columns.
     */
    public function up(): void
    {
        Schema::table('email_otps', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('role');
            $table->string('official_registration_number')->nullable()->after('company_name');
            $table->string('bank_name')->nullable()->after('official_registration_number');
            $table->string('iban_number')->nullable()->after('bank_name');
            $table->string('account_number')->nullable()->after('iban_number');
            $table->string('trn_number')->nullable()->after('account_number');
        });
    }

    public function down(): void
    {
        Schema::table('email_otps', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'official_registration_number',
                'bank_name',
                'iban_number',
                'account_number',
                'trn_number',
            ]);
        });
    }
};
