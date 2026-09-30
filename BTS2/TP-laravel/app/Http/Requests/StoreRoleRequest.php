<?php

namespace App\Http\Requests;

use App\Repositories\Contracts\RoleRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends BaseRoleRequest
{
    public function authorize(): bool
    {
        return Gate::allows('manage-roles');
    }

    public function rules(RoleRepository $roles): array
    {
        return array_replace($this->sharedRules($roles->availableAbilityNames()), [
            'name' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('roles', 'name')],
        ]);
    }
}
