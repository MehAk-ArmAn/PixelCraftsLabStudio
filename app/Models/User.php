<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = 'super_admin';

    public const ROLE_ADMIN = 'admin';

    public const ROLE_EDITOR = 'editor';

    public const ROLES = [self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN, self::ROLE_EDITOR];

    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_active', 'last_login_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(AdminActivityLog::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN], true);
    }

    public function isEditor(): bool
    {
        return $this->role === self::ROLE_EDITOR;
    }

    /** Any of the three roles may reach the panel; the gates narrow it from there. */
    public function canAccessAdminPanel(): bool
    {
        return $this->is_active && in_array($this->role, self::ROLES, true);
    }

    /** Content = projects, services, team, pages, media and marketing. */
    public function canManageContent(): bool
    {
        return $this->canAccessAdminPanel();
    }

    public function canManageAdministration(): bool
    {
        return $this->is_active && $this->isAdmin();
    }

    /** Security = admin users, activity log, destructive system controls. */
    public function canManageSecurity(): bool
    {
        return $this->is_active && $this->isSuperAdmin();
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            self::ROLE_SUPER_ADMIN => 'Super admin',
            self::ROLE_ADMIN => 'Admin',
            default => 'Editor',
        };
    }
}
