<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface MotifRepository
{
    public function all(): Collection;

    public function allOrderedByLibelle(): Collection;
}
