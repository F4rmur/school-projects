<?php

namespace App\Repositories\Contracts;

use App\Models\Motif;
use Illuminate\Database\Eloquent\Collection;

interface MotifRepository
{
    /** @return Collection<int, Motif> */
    public function all(): Collection;

    /** @return Collection<int, Motif> */
    public function allOrderedByLibelle(): Collection;
}
