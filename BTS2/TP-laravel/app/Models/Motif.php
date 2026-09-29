<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Motif extends Model
{
    use HasFactory;

    public function absences(): HasMany
    {
        return $this->hasMany(absence::class, 'motif_id');
    }
}
