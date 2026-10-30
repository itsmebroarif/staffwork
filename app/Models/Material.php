<?php

namespace App\Models;

use Database\Factories\MaterialFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
    /** @use HasFactory<MaterialFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'type',
        'kelas_id',
        'body',
        'file_path',
        'file_name',
        'is_published',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Check whether a student can access this material (same grade level or general material).
     */
    public function isAccessibleBy(User $user): bool
    {
        if ($this->kelas_id === null) {
            return true;
        }

        if ($user->kelas_id === null || $this->kelas === null || $user->kelas === null) {
            return false;
        }

        return $this->kelas->tingkat === $user->kelas->tingkat;
    }

    public function getExcerptAttribute(): string
    {
        return \Str::limit(strip_tags((string) $this->body), 120);
    }
}
