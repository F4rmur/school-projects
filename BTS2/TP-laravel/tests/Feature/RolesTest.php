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
        $admin = User::factory()->create();
        $admin->assign('admin');

        $this->actingAs($admin)
            ->post(route('roles.store'), [
                'name' => 'nouveau-role',
                'title' => 'Nouveau rôle',
                'abilities' => ['manage-users'],
            ])
            ->assertRedirect(route('roles.index'));

        $role = Role::query()->where('name', 'gestionnaire')->firstOrFail();
        $this->assertTrue($role->can('manage-users'));
        $this->assertFalse($role->can('manage-all-absences'));

        $this->actingAs($admin)
            ->put(route('roles.update', $role), [
                'name' => 'gestionnaire',
                'title' => 'Gestionnaire modifié',
                'abilities' => ['manage-all-absences'],
            ])
            ->assertRedirect(route('roles.index'));

        $role->refresh();
        $this->assertTrue($role->can('manage-all-absences'));
        $this->assertFalse($role->can('manage-users'));

        $adminRole = Role::query()->where('name', 'admin')->firstOrFail();
        $this->actingAs($admin)
            ->put(route('roles.update', $adminRole), [
                'name' => 'admin',
                'title' => 'Admin',
                'abilities' => ['manage-users'],
            ])
            ->assertRedirect(route('roles.index'));

        $this->assertTrue($admin->fresh()->can('manage-roles'));
    }

    public function test_user_without_manage_roles_cannot_create_or_view_roles(): void
    {
        $user = User::factory()->create();
        $role = Role::query()->create([
            'name' => 'gestionnaire',
            'title' => 'Gestionnaire',
        ]);

        $this->actingAs($user)
            ->get(route('roles.index'))
            ->assertForbidden();

        $this->actingAs($user)
            ->post(route('roles.store'), [
                'name' => 'gestionnaire',
                'title' => 'Gestionnaire',
                'abilities' => ['manage-users'],
            ])
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('roles.edit', $role))
            ->assertForbidden();

        $this->actingAs($user)
            ->put(route('roles.update', $role), [
                'name' => 'gestionnaire',
                'title' => 'Modifié',
                'abilities' => ['manage-users'],
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('roles', [
            'id' => $role->getKey(),
            'title' => 'Gestionnaire',
        ]);
        $this->assertDatabaseMissing('roles', ['name' => 'nouveau-role']);
    }
}
