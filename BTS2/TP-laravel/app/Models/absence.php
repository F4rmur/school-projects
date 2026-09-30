<?php

namespace App\Models;

use Database\Factories\AbsenceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class absence extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    /** @use HasFactory<AbsenceFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'motif_id', 'conges_payes', 'type_conge', 'date_debut', 'date_fin', 'status', 'approved_by', 'approved_at'];

    protected $attributes = [
        'status' => self::STATUS_APPROVED,
    ];

    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_fin' => 'date',
            'conges_payes' => 'boolean',
            'approved_at' => 'datetime',
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

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
