<?php

namespace App\Models;

use Database\Factories\MotifFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Motif extends Model
{
    /** @use HasFactory<MotifFactory> */
    use HasFactory;

    /** @return HasMany<absence, $this> */
    public function absences(): HasMany
    {
        return $this->hasMany(absence::class, 'motif_id');
    }
}
