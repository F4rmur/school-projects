<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LogicException;

abstract class BaseUserRequest extends FormRequest
{
    /** @return array<string, array<int, mixed>> */
    protected function sharedRules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'sexe' => ['required', 'in:homme,femme'],
            'role' => ['required', 'string', Rule::exists('roles', 'name')],
        ];
    }

    public function validatedRoleName(): string
    {
        $roleName = $this->validated('role');

        if (! is_string($roleName)) {
            throw new LogicException('The validated role name must be a string.');
        }

        return $roleName;
    }
}
