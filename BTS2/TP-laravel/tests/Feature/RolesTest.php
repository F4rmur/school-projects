<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Silber\Bouncer\Database\Role;
use Tests\TestCase;

class RolesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_and_update_roles_and_their_abilities(): void
    {
        $admin = $this->createAdmin();
        $roleName = $this->randomRoleName();
        $roleTitle = fake()->sentence(3);
        $updatedRoleName = $this->randomRoleName();
        $updatedRoleTitle = fake()->sentence(3);

        $this->actingAs($admin)
            ->post(route('roles.store'), $this->rolePayload($roleName, $roleTitle, ['manage-users']))
            ->assertRedirect(route('roles.index'));

        $role = Role::query()->where('name', $roleName)->firstOrFail();
        $this->assertTrue($role->can('manage-users'));
        $this->assertFalse($role->can('manage-all-absences'));

        $this->actingAs($admin)
            ->put(route('roles.update', $role), $this->rolePayload(
                $updatedRoleName,
                $updatedRoleTitle,
                ['manage-all-absences'],
            ))
            ->assertRedirect(route('roles.index'));

        $role->refresh();
        $this->assertSame($updatedRoleName, $role->name);
        $this->assertSame($updatedRoleTitle, $role->title);
        $this->assertTrue($role->can('manage-all-absences'));
        $this->assertFalse($role->can('manage-users'));

        $adminRole = Role::query()->where('name', 'admin')->firstOrFail();
        $this->actingAs($admin)
            ->put(route('roles.update', $adminRole), $this->rolePayload(
                'admin',
                fake()->sentence(3),
                ['manage-users'],
            ))
            ->assertRedirect(route('roles.index'));

        $this->assertTrue($admin->fresh()->can('manage-roles'));
    }

    public function test_user_without_manage_roles_cannot_create_or_view_roles(): void
    {
        $user = User::factory()->create();
        $roleName = $this->randomRoleName();
        $roleTitle = fake()->sentence(3);
        $roleToCreateName = $this->randomRoleName();
        $role = Role::query()->create([
            'name' => $roleName,
            'title' => $roleTitle,
        ]);

        $this->actingAs($user)
            ->get(route('roles.index'))
            ->assertForbidden();

        $this->actingAs($user)
            ->post(route('roles.store'), $this->rolePayload(
                $roleToCreateName,
                fake()->sentence(3),
                ['manage-users'],
            ))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('roles.edit', $role))
            ->assertForbidden();

        $this->actingAs($user)
            ->put(route('roles.update', $role), $this->rolePayload(
                $roleName,
                fake()->sentence(3),
                ['manage-users'],
            ))
            ->assertForbidden();

        $this->assertDatabaseHas('roles', [
            'id' => $role->getKey(),
            'title' => $roleTitle,
        ]);
        $this->assertDatabaseMissing('roles', ['name' => $roleToCreateName]);
    }

    public function test_admin_can_view_role_management_pages(): void
    {
        $admin = $this->createAdmin();
        $role = Role::query()->where('name', 'utilisateur')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('roles.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('roles.create'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('roles.edit', $role))
            ->assertOk();
    }

    private function createAdmin(): User
    {
        $admin = User::factory()->create();
        $admin->assign('admin');

        return $admin;
    }

    /**
     * @param  array<int, string>  $abilities
     * @return array{name: string, title: string, abilities: array<int, string>}
     */
    private function rolePayload(string $name, string $title, array $abilities): array
    {
        return [
            'name' => $name,
            'title' => $title,
            'abilities' => $abilities,
        ];
    }

    private function randomRoleName(): string
    {
        return fake()->unique()->slug(2);
    }
}
