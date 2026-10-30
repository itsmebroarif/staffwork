<?php

namespace App\Models;

use Database\Factories\WaliMuridFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaliMurid extends Model
{
    /** @use HasFactory<WaliMuridFactory> */
    use HasFactory;

    protected $fillable = [
        'student_id',
        'name',
        'hubungan',
        'phone',
        'email',
        'alamat',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
