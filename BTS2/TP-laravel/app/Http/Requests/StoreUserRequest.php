<?php

namespace App\Http\Requests;

use App\Models\users as UserRecord;
use Illuminate\Support\Facades\Gate;

class StoreUserRequest extends BaseUserRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create', UserRecord::class);
    }

    public function rules(): array
    {
        return array_replace($this->sharedRules(), [
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
}
