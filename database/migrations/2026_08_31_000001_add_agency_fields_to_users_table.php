<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * company_name / official_registration_number are shared by both
     * External Agent (broker) and External Agency accounts. The rest
     * (bank details, TRN, owner_document_type) are External Agency-only.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('approved_at');
            $table->string('official_registration_number')->nullable()->after('company_name');
            $table->string('bank_name')->nullable()->after('official_registration_number');
            $table->string('iban_number')->nullable()->after('bank_name');
            $table->string('account_number')->nullable()->after('iban_number');
            $table->string('trn_number')->nullable()->after('account_number');
            // Which document was uploaded to the 'owner_identity_document'
            // media collection: 'passport_eid' or 'poa'.
            $table->string('owner_document_type')->nullable()->after('trn_number');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'official_registration_number',
                'bank_name',
                'iban_number',
                'account_number',
                'trn_number',
                'owner_document_type',
            ]);
        });
    }
};
