<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_approved' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    protected $appends = ['approval_status', 'registered_at'];

    public function roles()
    {
        return $this->morphToMany(Role::class, 'model', 'model_has_roles');
    }

    public function scopePendingApproval($query)
    {
        return $query->where('is_approved', false);
    }

    /**
     * Drives the dashboard Users list Status badge: distinguishes a broker
     * awaiting admin approval from the plain active/deactivated states.
     */
    public function getApprovalStatusAttribute(): string
    {
        if (! $this->is_approved) {
            return 'pending';
        }

        return $this->is_active ? 'active' : 'deactivated';
    }

    public function getRegisteredAtAttribute(): ?string
    {
        return $this->created_at?->format('M d, Y');
    }

    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }

    /**
     * Named distinctly from the Notifiable trait's own notifications()
     * relation (which points at Laravel's built-in DatabaseNotification /
     * uuid-keyed notifications shape — unused in this app) to avoid any
     * ambiguity with this app's custom Notification model/table.
     */
    public function appNotifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->latest();
    }

    /**
     * Passport / Emirates ID scans for broker (External Agent) registration.
     * Stored on the private 'local' disk — served only through the
     * view_user-gated dashboard routes, never a public URL.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('passport')
            ->useDisk('local')
            ->singleFile()
            ->acceptsMimeTypes(['application/pdf', 'image/jpeg', 'image/png']);

        $this->addMediaCollection('emirates_id')
            ->useDisk('local')
            ->singleFile()
            ->acceptsMimeTypes(['application/pdf', 'image/jpeg', 'image/png']);
    }
}
