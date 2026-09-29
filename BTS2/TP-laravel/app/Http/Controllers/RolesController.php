<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\RoleRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Silber\Bouncer\Database\Role;

class RolesController extends Controller
{
    public function __construct(private RoleRepository $roles) {}

    public function index(): View
    {
        Gate::authorize('manage-roles');

        $roles = $this->roles->allWithAbilities();

        return view('roles.index', compact('roles'));
    }

    public function create(): View
    {
        Gate::authorize('manage-roles');

        return view('roles.form', [
            'role' => null,
            'abilities' => $this->roles->availableAbilities(),
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
            'abilities.*' => ['string', Rule::in($this->roles->availableAbilityNames())],
        ]);

        $abilityNames = $validated['abilities'] ?? [];
        unset($validated['abilities']);

        $this->roles->create($validated, $abilityNames);

        return redirect()->route('roles.index')->with('success', __('ui.flash.role_created'));
    }

    public function edit(Role $role): View
    {
        Gate::authorize('manage-roles');

        return view('roles.form', [
            'role' => $role,
            'abilities' => $this->roles->availableAbilities(),
            'roleAbilities' => $this->roles->abilityNames($role),
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
            'abilities.*' => ['string', Rule::in($this->roles->availableAbilityNames())],
        ]);

        $abilityNames = $validated['abilities'] ?? [];
        unset($validated['abilities']);

        if ($role->name === 'admin') {
            $abilityNames[] = 'manage-roles';
        }

        $this->roles->update($role, $validated, array_values(array_unique($abilityNames)));

        return redirect()->route('roles.index')->with('success', __('ui.flash.role_updated'));
    }
}
