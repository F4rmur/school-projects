<?php

namespace App\Repositories\Eloquent;

use App\Models\absence as AbsenceRecord;
use App\Repositories\Contracts\AbsenceRepository;
use Illuminate\Database\Eloquent\Collection;

class EloquentAbsenceRepository implements AbsenceRepository
{
    public function allWithRelations(?int $userId = null): Collection
    {
        return AbsenceRecord::with(['user', 'motif'])
            ->when($userId !== null, fn ($query) => $query->where('user_id', $userId))
            ->orderByDesc('date_debut')
            ->get();
    }

    public function paidForUser(int|string $userId): Collection
    {
        return AbsenceRecord::query()
            ->where('user_id', $userId)
            ->where(function ($query): void {
                $query->where('conges_payes', true)
                    ->orWhere('type_conge', 'conges_payes');
            })
            ->get(['date_debut', 'date_fin']);
    }

    public function hasOverlap(int|string $userId, string $dateStart, string $dateEnd): bool
    {
        return AbsenceRecord::query()
            ->where('user_id', $userId)
            ->whereDate('date_debut', '<=', $dateEnd)
            ->whereDate('date_fin', '>=', $dateStart)
            ->exists();
    }

    public function create(array $attributes): AbsenceRecord
    {
        return AbsenceRecord::create($attributes);
    }

    public function update(AbsenceRecord $absence, array $attributes): void
    {
        $absence->update($attributes);
    }

    public function delete(AbsenceRecord $absence): void
    {
        $absence->delete();
    }

    public function loadRelations(AbsenceRecord $absence): AbsenceRecord
    {
        return $absence->load(['user', 'motif', 'approvedBy']);
    }
}
