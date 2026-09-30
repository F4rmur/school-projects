<?php

namespace App\Http\Requests;

use App\Repositories\Contracts\RoleRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends BaseRoleRequest
{
    public function authorize(): bool
    {
        return Gate::allows('manage-roles');
    }

    public function rules(RoleRepository $roles): array
    {
        $role = $this->route('role');

        return array_replace($this->sharedRules($roles->availableAbilityNames()), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($role->getKey()),
                ...($role->name === 'admin' ? [Rule::in(['admin'])] : ['alpha_dash']),
            ],
        ]);
    }
}
