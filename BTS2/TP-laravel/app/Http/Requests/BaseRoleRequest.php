<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class BaseRoleRequest extends FormRequest
{
    protected function sharedRules(array $abilityNames): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'abilities' => ['sometimes', 'array'],
            'abilities.*' => ['string', Rule::in($abilityNames)],
        ];
    }
}
