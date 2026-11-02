<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\StoreRoleRequest;
use Illuminate\Support\Facades\Validator;
use LogicException;
use Tests\TestCase;

class BaseRoleRequestTest extends TestCase
{
    public function test_rejects_non_array_validated_abilities(): void
    {
        $request = new StoreRoleRequest;
        $request->setValidator(Validator::make(
            ['abilities' => 'manage-users'],
            ['abilities' => ['present']],
        ));

        $this->expectException(LogicException::class);

        $request->validatedAbilityNames();
    }

    public function test_rejects_non_string_validated_abilities(): void
    {
        $request = new StoreRoleRequest;
        $request->setValidator(Validator::make(
            ['abilities' => [123]],
            ['abilities' => ['present']],
        ));

        $this->expectException(LogicException::class);

        $request->validatedAbilityNames();
    }
}
