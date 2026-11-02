<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Validator;
use LogicException;
use Tests\TestCase;

class UpdateUserRequestTest extends TestCase
{
    public function test_rejects_non_string_non_null_validated_passwords(): void
    {
        $request = new UpdateUserRequest;
        $request->setValidator(Validator::make(
            ['password' => ['invalid']],
            ['password' => ['present']],
        ));

        $this->expectException(LogicException::class);

        $request->validatedPassword();
    }
}
