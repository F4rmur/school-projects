<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;

class UpdateAbsenceRequest extends BaseAbsenceRequest
{
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('numeroAbsence'));
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return $this->sharedRules();
    }
}
