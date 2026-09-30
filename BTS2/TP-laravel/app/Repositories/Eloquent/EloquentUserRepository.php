<?php

namespace App\Repositories\Eloquent;

use App\Models\users as UserRecord;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Database\Eloquent\Collection;

class EloquentUserRepository implements UserRepository
{
    public function allWithAbsences(?int $userId = null): Collection
    {
        return UserRecord::with('absences.motif')
            ->withCount('absences')
            ->when($userId !== null, fn ($query) => $query->whereKey($userId))
            ->orderBy('nom')
            ->get();
    }

    public function forAbsenceForm(bool $manageAll, int $currentUserId): Collection
    {
        return $manageAll
            ? UserRecord::orderBy('nom')->get()
            : UserRecord::whereKey($currentUserId)->get();
    }

    public function find(int|string $id): ?UserRecord
    {
        return UserRecord::find($id);
    }

    public function create(array $attributes): UserRecord
    {
        return UserRecord::create($attributes);
    }

    public function update(UserRecord $user, array $attributes): void
    {
        $user->update($attributes);
    }
}
