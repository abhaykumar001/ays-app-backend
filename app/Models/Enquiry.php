<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enquiry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'guest_email',
        'guest_phone',
        'project_id',
        'unit_id',
        'team_member_id',
        'message',
        'enquiry_type',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function unit()
    {
        return $this->belongsTo(unit::class);
    }

    public function teamMember()
    {
        return $this->belongsTo(TeamMember::class);
    }

    public function getContactNameAttribute(): string
    {
        return $this->user?->name ?? 'Guest';
    }

    public function getContactEmailAttribute(): ?string
    {
        return $this->user?->email ?? $this->guest_email;
    }

    public function getContactPhoneAttribute(): ?string
    {
        return $this->guest_phone;
    }
}
