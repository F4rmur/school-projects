<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Validator;
use LogicException;
use Tests\TestCase;

class BaseUserRequestTest extends TestCase
{
    public function test_rejects_non_string_validated_role_names(): void
    {
        $request = new StoreUserRequest;
        $request->setValidator(Validator::make(
            ['role' => 123],
            ['role' => ['present']],
        ));

        $this->expectException(LogicException::class);

        $request->validatedRoleName();
    }
}
