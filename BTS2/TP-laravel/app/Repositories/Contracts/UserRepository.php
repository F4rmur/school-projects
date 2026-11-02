<?php

namespace App\Repositories\Contracts;

use App\Models\users as UserRecord;
use Illuminate\Database\Eloquent\Collection;

interface UserRepository
{
    /** @return Collection<int, UserRecord> */
    public function allWithAbsences(?int $userId = null): Collection;

    /** @return Collection<int, UserRecord> */
    public function forAbsenceForm(bool $manageAll, int $currentUserId): Collection;

    public function find(int|string $id): ?UserRecord;

    /** @param array<string, mixed> $attributes */
    public function create(array $attributes): UserRecord;

    /** @param array<string, mixed> $attributes */
    public function update(UserRecord $user, array $attributes): void;
}
