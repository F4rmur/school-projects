<?php

namespace App\Repositories\Contracts;

use App\Models\absence as AbsenceRecord;
use Illuminate\Database\Eloquent\Collection;

interface AbsenceRepository
{
    public function allWithRelations(): Collection;

    public function paidForUser(int|string $userId): Collection;

    public function hasOverlap(int|string $userId, string $dateStart, string $dateEnd): bool;

    public function create(array $attributes): AbsenceRecord;

    public function update(AbsenceRecord $absence, array $attributes): void;

    public function delete(AbsenceRecord $absence): void;

    public function loadRelations(AbsenceRecord $absence): AbsenceRecord;
}
