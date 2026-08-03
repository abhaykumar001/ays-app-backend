<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Owner extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'nationality',
        'gender', 'marital_status', 'occupation', 'dob',
        'address', 'city', 'country', 'owner_type',
        'passport_number', 'visa_status', 'residency_permit',
        'country_of_origin', 'kyc_completed',
    ];

    public function getNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
