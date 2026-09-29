<?php

namespace App\Models;

use Database\Factories\AbsenceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class absence extends Model
{
    /** @use HasFactory<AbsenceFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'motif_id', 'conges_payes', 'type_conge', 'date_debut', 'date_fin'];

    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_fin' => 'date',
            'conges_payes' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(users::class, 'user_id');
    }

    public function motif(): BelongsTo
    {
        return $this->belongsTo(Motif::class, 'motif_id');
    }
}
