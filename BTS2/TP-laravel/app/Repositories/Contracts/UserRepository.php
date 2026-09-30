<?php

namespace App\Repositories\Contracts;

use App\Models\users as UserRecord;
use Illuminate\Database\Eloquent\Collection;

interface UserRepository
{
    public function allWithAbsences(?int $userId = null): Collection;

    public function forAbsenceForm(bool $manageAll, int $currentUserId): Collection;

    public function find(int|string $id): ?UserRecord;

    public function create(array $attributes): UserRecord;

    public function update(UserRecord $user, array $attributes): void;
}
