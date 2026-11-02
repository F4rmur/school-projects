<?php

namespace App\Http\Requests;

use App\Models\users as UserRecord;
use Illuminate\Support\Facades\Gate;
use LogicException;

class StoreUserRequest extends BaseUserRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create', UserRecord::class);
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return array_replace($this->sharedRules(), [
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function validatedPassword(): string
    {
        $password = $this->validated('password');

        if (! is_string($password)) {
            throw new LogicException('The validated password must be a string.');
        }

        return $password;
    }
}
