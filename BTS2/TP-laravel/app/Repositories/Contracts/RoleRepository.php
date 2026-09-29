<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Silber\Bouncer\Database\Role;

interface RoleRepository
{
    public function all(): Collection;

    public function allWithAbilities(): Collection;

    public function availableAbilities(): Collection;

    /** @return list<string> */
    public function availableAbilityNames(): array;

    /** @return list<string> */
    public function abilityNames(Role $role): array;

    public function create(array $attributes, array $abilityNames): Role;

    public function update(Role $role, array $attributes, array $abilityNames): void;

    public function syncForUser(int|string $userId, string $roleName): void;

    public function userRoleName(int|string $userId): ?string;
}
