<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'avatar', 'nis', 'nip', 'phone', 'kelas_id'])]
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

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function waliMurids(): HasMany
    {
        return $this->hasMany(WaliMurid::class, 'student_id');
    }

    public function teachingAssignments(): HasMany
    {
        return $this->hasMany(TeachingAssignment::class, 'teacher_id');
    }

    public function reportedViolations(): HasMany
    {
        return $this->hasMany(Violation::class, 'reported_by');
    }

    /**
     * Check if user is Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /**
     * Check if user can manage LMS content (Super Admin or Guru).
     */
    public function isTeacher(): bool
    {
        return in_array($this->role, ['superadmin', 'guru'], true);
    }

    /**
     * Check if user is a student (Murid).
     */
    public function isStudent(): bool
    {
        return $this->role === 'murid';
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
            'guru' => 'Guru',
            'murid' => 'Murid',
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
            'guru' => 'badge-primary',
            'murid' => 'badge-success',
            default => 'badge-secondary',
        };
    }
}
