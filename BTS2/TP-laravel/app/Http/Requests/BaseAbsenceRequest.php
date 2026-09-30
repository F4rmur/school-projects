<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

abstract class BaseAbsenceRequest extends FormRequest
{
    protected function sharedRules(): array
    {
        return [
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::when(! Gate::allows('manage-all-absences'), Rule::in([Auth::id()])),
            ],
            'motif_id' => ['required', 'exists:motifs,id'],
            'type_conge' => ['nullable', 'in:conges_payes,paternite,maternite'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['required', 'date', 'after_or_equal:date_debut'],
        ];
    }
}
