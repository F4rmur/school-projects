<?php

namespace App\Http\Requests;

use App\Models\users as UserRecord;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use LogicException;

class UpdateUserRequest extends BaseUserRequest
{
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('idUser'));
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        $user = $this->route('idUser');
        $userId = $user instanceof UserRecord ? $user->getKey() : $user;

        return array_replace($this->sharedRules(), [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function validatedPassword(): ?string
    {
        $password = $this->validated('password');

        if ($password !== null && ! is_string($password)) {
            throw new LogicException('The validated password must be a string or null.');
        }

        return $password;
    }
}
