<?php

namespace App\Models;

use Database\Factories\MapelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mapel extends Model
{
    /** @use HasFactory<MapelFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    public function teachingAssignments(): HasMany
    {
        return $this->hasMany(TeachingAssignment::class);
    }
}
