<?php

namespace App\Http\Requests;

use App\Repositories\Contracts\RoleRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Silber\Bouncer\Database\Role;

class UpdateRoleRequest extends BaseRoleRequest
{
    public function authorize(): bool
    {
        return Gate::allows('manage-roles');
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(RoleRepository $roles): array
    {
        $role = $this->route('role');

        if (! $role instanceof Role) {
            throw (new ModelNotFoundException)->setModel(Role::class);
        }

        return array_replace($this->sharedRules($roles->availableAbilityNames()), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($role->getKey()),
                ...($role->getAttribute('name') === 'admin' ? [Rule::in(['admin'])] : ['alpha_dash']),
            ],
        ]);
    }
}
