<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLanguageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'locale' => ['required', Rule::in(array_keys(config('app.supported_locales')))],
        ];
    }
}
