<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Silber\Bouncer\Database\Ability;
use Silber\Bouncer\Database\Role;

class RolesController extends Controller
{
    public function index(): View
    {
        Gate::authorize('manage-roles');

        $roles = Role::query()->with('abilities')->orderBy('name')->get();

        return view('roles.index', compact('roles'));
    }

    public function create(): View
    {
        Gate::authorize('manage-roles');

        return view('roles.form', [
            'role' => null,
            'abilities' => $this->availableAbilities(),
            'roleAbilities' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-roles');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('roles', 'name')],
            'title' => ['required', 'string', 'max:255'],
            'abilities' => ['sometimes', 'array'],
            'abilities.*' => ['string', Rule::in($this->availableAbilityNames())],
        ]);

        $abilityNames = $validated['abilities'] ?? [];
        unset($validated['abilities']);

        $role = Role::query()->create($validated);
        Bouncer::sync($role)->abilities($abilityNames);
        Bouncer::refresh($role);

        return redirect()->route('roles.index')->with('success', 'Rôle créé avec succès.');
    }

    public function edit(Role $role): View
    {
        Gate::authorize('manage-roles');

        return view('roles.form', [
            'role' => $role,
            'abilities' => $this->availableAbilities(),
            'roleAbilities' => $role->abilities()->pluck('name')->all(),
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        Gate::authorize('manage-roles');

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($role->getKey()),
                ...($role->name === 'admin' ? [Rule::in(['admin'])] : ['alpha_dash']),
            ],
            'title' => ['required', 'string', 'max:255'],
            'abilities' => ['sometimes', 'array'],
            'abilities.*' => ['string', Rule::in($this->availableAbilityNames())],
        ]);

        $abilityNames = $validated['abilities'] ?? [];
        unset($validated['abilities']);

        $role->update($validated);

        if ($role->name === 'admin') {
            $abilityNames[] = 'manage-roles';
        }

        Bouncer::sync($role)->abilities(array_values(array_unique($abilityNames)));
        Bouncer::refresh($role);

        return redirect()->route('roles.index')->with('success', 'Rôle modifié avec succès.');
    }

    private function availableAbilities(): \Illuminate\Database\Eloquent\Collection
    {
        return Ability::query()->simpleAbility()->orderBy('name')->get();
    }

    /** @return list<string> */
    private function availableAbilityNames(): array
    {
        return $this->availableAbilities()->pluck('name')->all();
    }
}
