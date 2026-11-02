<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Validator;
use LogicException;
use Tests\TestCase;

class StoreUserRequestTest extends TestCase
{
    public function test_rejects_non_string_validated_passwords(): void
    {
        $request = new StoreUserRequest;
        $request->setValidator(Validator::make(
            ['password' => ['invalid']],
            ['password' => ['present']],
        ));

        $this->expectException(LogicException::class);

        $request->validatedPassword();
    }
}
