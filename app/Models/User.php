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
        'company_name',
        'official_registration_number',
        'bank_name',
        'iban_number',
        'account_number',
        'trn_number',
        'owner_document_type',
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

    protected $appends = ['approval_status', 'registered_at', 'is_protected'];

    /**
     * The account id of the founding Super Admin. Fixed at seed time
     * (UserSeeder) and never reassigned, so id comparison is safe here.
     */
    public const ROOT_ADMIN_ID = 1;

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

    /**
     * Drives the dashboard Users list: hides the Edit/Approve/Toggle/Delete
     * actions for the founding Super Admin account so it can't be
     * edited, deactivated, or deleted from the list by accident.
     */
    public function getIsProtectedAttribute(): bool
    {
        return $this->id === self::ROOT_ADMIN_ID;
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

        // External Agency registration documents. 'owner_identity_document'
        // holds whichever of Passport/EID or Power of Attorney was
        // submitted — see the owner_document_type column for which one.
        $this->addMediaCollection('trade_license')
            ->useDisk('local')
            ->singleFile()
            ->acceptsMimeTypes(['application/pdf', 'image/jpeg', 'image/png']);

        $this->addMediaCollection('owner_identity_document')
            ->useDisk('local')
            ->singleFile()
            ->acceptsMimeTypes(['application/pdf', 'image/jpeg', 'image/png']);
    }
}
