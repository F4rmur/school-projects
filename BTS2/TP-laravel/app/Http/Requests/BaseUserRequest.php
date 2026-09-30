<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class BaseUserRequest extends FormRequest
{
    protected function sharedRules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'sexe' => ['required', 'in:homme,femme'],
            'role' => ['required', 'string', Rule::exists('roles', 'name')],
        ];
    }
}
