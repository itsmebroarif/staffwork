<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'avatar'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
        ];
    }

    /**
     * Check if user is Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /**
     * Get URL for user avatar or default illustration.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && file_exists(storage_path('app/public/'.$this->avatar))) {
            return asset('storage/'.$this->avatar);
        }

        return asset('template/img/undraw_profile.svg');
    }

    /**
     * Get readable role label.
     */
    public function getRoleNameAttribute(): string
    {
        return match ($this->role) {
            'superadmin' => 'Super Admin',
            'admin' => 'Admin',
            'staff' => 'Staff',
            default => ucfirst((string) $this->role),
        };
    }

    /**
     * Get badge HTML class for role.
     */
    public function getRoleBadgeClassAttribute(): string
    {
        return match ($this->role) {
            'superadmin' => 'badge-danger',
            'admin' => 'badge-warning',
            'staff' => 'badge-info',
            default => 'badge-secondary',
        };
    }
}
