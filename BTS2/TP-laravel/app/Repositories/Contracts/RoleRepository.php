<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Silber\Bouncer\Database\Ability;
use Silber\Bouncer\Database\Role;

interface RoleRepository
{
    /** @return Collection<int, Role> */
    public function all(): Collection;

    /** @return Collection<int, Role> */
    public function allWithAbilities(): Collection;

    /** @return Collection<int, Ability> */
    public function availableAbilities(): Collection;

    /** @return list<string> */
    public function availableAbilityNames(): array;

    /** @return list<string> */
    public function abilityNames(Role $role): array;

    /**
     * @param  array<string, mixed>  $attributes
     * @param  list<string>  $abilityNames
     */
    public function create(array $attributes, array $abilityNames): Role;

    /**
     * @param  array<string, mixed>  $attributes
     * @param  list<string>  $abilityNames
     */
    public function update(Role $role, array $attributes, array $abilityNames): void;

    public function syncForUser(int|string $userId, string $roleName): void;

    public function userRoleName(int|string $userId): ?string;
}
