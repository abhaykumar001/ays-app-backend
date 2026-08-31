<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtp extends Model
{
    protected $fillable = [
        'email', 'name', 'phone', 'plain_password', 'role', 'otp', 'expires_at',
        'company_name', 'official_registration_number',
        'bank_name', 'iban_number', 'account_number', 'trn_number',
    ];

    protected $casts = ['expires_at' => 'datetime'];

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
