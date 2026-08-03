<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Viewing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'guest_email',
        'guest_phone',
        'project_id',
        'unit_id',
        'team_member_id',
        'viewing_type',
        'scheduled_at',
        'notes',
        'status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
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

    public function getScheduledAtFormattedAttribute(): ?string
    {
        return $this->scheduled_at?->format('M d, Y h:i A');
    }
}
