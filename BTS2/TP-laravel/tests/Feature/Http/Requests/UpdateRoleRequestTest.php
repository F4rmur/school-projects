<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\UpdateRoleRequest;
use App\Repositories\Contracts\RoleRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

class UpdateRoleRequestTest extends TestCase
{
    public function test_rejects_a_route_parameter_that_is_not_a_role_model(): void
    {
        $request = new UpdateRoleRequest;
        $request->setRouteResolver(static fn () => null);

        $this->expectException(ModelNotFoundException::class);

        $request->rules($this->createMock(RoleRepository::class));
    }
}
