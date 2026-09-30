<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Repositories\Contracts\RoleRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
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

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $validated = $request->validated();

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

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $validated = $request->validated();

        $abilityNames = $validated['abilities'] ?? [];
        unset($validated['abilities']);

        if ($role->name === 'admin') {
            $abilityNames[] = 'manage-roles';
        }

        $this->roles->update($role, $validated, array_values(array_unique($abilityNames)));

        return redirect()->route('roles.index')->with('success', __('ui.flash.role_updated'));
    }
}
