<?php

namespace App\Models;

use Database\Factories\UsersFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class users extends Model
{
    /** @use HasFactory<UsersFactory> */
    use HasFactory;

    protected $fillable = ['nom', 'prenom', 'sexe', 'email', 'password', 'is_admin'];

    protected function casts(): array
    {
        return [
            'is_admin' => 'boolean',
        ];
    }

    public function absences(): HasMany
    {
        return $this->hasMany(absence::class, 'user_id');
    }
}
