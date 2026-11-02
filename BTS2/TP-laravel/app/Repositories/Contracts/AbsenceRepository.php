<?php

namespace App\Repositories\Contracts;

use App\Models\absence as AbsenceRecord;
use Illuminate\Database\Eloquent\Collection;

interface AbsenceRepository
{
    /** @return Collection<int, AbsenceRecord> */
    public function allWithRelations(?int $userId = null): Collection;

    /**
     * @return Collection<int, AbsenceRecord>
     */
    public function paidForUser(int|string $userId): Collection;

    public function hasOverlap(int|string $userId, string $dateStart, string $dateEnd): bool;

    /** @param array<string, mixed> $attributes */
    public function create(array $attributes): AbsenceRecord;

    /** @param array<string, mixed> $attributes */
    public function update(AbsenceRecord $absence, array $attributes): void;

    public function delete(AbsenceRecord $absence): void;

    public function loadRelations(AbsenceRecord $absence): AbsenceRecord;
}
