<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\RoleRepository;
use Illuminate\Database\Eloquent\Collection;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Silber\Bouncer\Database\Ability;
use Silber\Bouncer\Database\Role;
use UnexpectedValueException;

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
        return $this->stringNames($this->availableAbilities()->pluck('name')->all());
    }

    public function abilityNames(Role $role): array
    {
        return $this->stringNames($role->abilities()->pluck('name')->all());
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
        $roleName = User::query()->findOrFail($userId)->getRoles()->first();

        if ($roleName !== null && ! is_string($roleName)) {
            throw new UnexpectedValueException('Role names must be strings.');
        }

        return $roleName;
    }

    /**
     * @param  array<array-key, mixed>  $values
     * @return list<string>
     */
    private function stringNames(array $values): array
    {
        $names = [];

        foreach ($values as $value) {
            if (! is_string($value)) {
                throw new UnexpectedValueException('Role and ability names must be strings.');
            }

            $names[] = $value;
        }

        return $names;
    }
}
