<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Buyer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'nationality',
        'gender', 'marital_status', 'occupation', 'dob', 'buyer_type',
        'passport_number', 'visa_status', 'residency_permit',
        'country_of_origin', 'budget_min', 'budget_max',
        'preferred_community', 'preferred_unit_type',
        'agent_id', 'source', 'channel', 'interest_level', 'notes', 'user_id',
    ];

    public function getNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
