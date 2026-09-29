<?php

namespace App\Repositories\Eloquent;

use App\Models\Motif;
use App\Repositories\Contracts\MotifRepository;
use Illuminate\Database\Eloquent\Collection;

class EloquentMotifRepository implements MotifRepository
{
    public function all(): Collection
    {
        return Motif::all();
    }

    public function allOrderedByLibelle(): Collection
    {
        return Motif::orderBy('libelle')->get();
    }
}
