<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\RoleRepository;
use Illuminate\Database\Eloquent\Collection;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Silber\Bouncer\Database\Ability;
use Silber\Bouncer\Database\Role;

class EloquentRoleRepository implements RoleRepository
{
    public function all(): Collection
    {
        return Role::query()->orderBy('name')->get();
    }

    public function allWithAbilities(): Collection
    {
        return Role::query()->with('abilities')->orderBy('name')->get();
    }

    public function availableAbilities(): Collection
    {
        return Ability::query()->simpleAbility()->orderBy('name')->get();
    }

    public function availableAbilityNames(): array
    {
        return $this->availableAbilities()->pluck('name')->all();
    }

    public function abilityNames(Role $role): array
    {
        return $role->abilities()->pluck('name')->all();
    }

    public function create(array $attributes, array $abilityNames): Role
    {
        $role = Role::query()->create($attributes);
        Bouncer::sync($role)->abilities($abilityNames);
        Bouncer::refresh($role);

        return $role;
    }

    public function update(Role $role, array $attributes, array $abilityNames): void
    {
        $role->update($attributes);
        Bouncer::sync($role)->abilities($abilityNames);
        Bouncer::refresh($role);
    }

    public function syncForUser(int|string $userId, string $roleName): void
    {
        $user = User::query()->findOrFail($userId);
        Bouncer::sync($user)->roles([$roleName]);
        Bouncer::refresh($user);
    }

    public function userRoleName(int|string $userId): ?string
    {
        return User::query()->findOrFail($userId)->getRoles()->first()?->name;
    }
}
