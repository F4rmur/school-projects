<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LogicException;

class UpdateLanguageRequest extends FormRequest
{
    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        $supportedLocales = config('app.supported_locales');

        if (! is_array($supportedLocales)) {
            throw new LogicException('Supported locales configuration must be an array.');
        }

        return [
            'locale' => ['required', Rule::in(array_keys($supportedLocales))],
        ];
    }
}
