<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LogicException;

abstract class BaseRoleRequest extends FormRequest
{
    /**
     * @param  list<string>  $abilityNames
     * @return array<string, array<int, mixed>>
     */
    protected function sharedRules(array $abilityNames): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'abilities' => ['sometimes', 'array'],
            'abilities.*' => ['string', Rule::in($abilityNames)],
        ];
    }

    /** @return list<string> */
    public function validatedAbilityNames(): array
    {
        $validatedAbilities = $this->validated('abilities', []);

        if (! is_array($validatedAbilities)) {
            throw new LogicException('Validated role abilities must be an array.');
        }

        $abilityNames = [];

        foreach ($validatedAbilities as $abilityName) {
            if (! is_string($abilityName)) {
                throw new LogicException('Validated role abilities must contain only strings.');
            }

            $abilityNames[] = $abilityName;
        }

        return $abilityNames;
    }
}
