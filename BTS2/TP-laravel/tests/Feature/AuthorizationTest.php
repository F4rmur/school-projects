<?php

namespace Tests\Feature;

use App\Models\absence as AbsenceRecord;
use App\Models\Motif;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_without_manage_users_ability_cannot_create_a_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('user.create'))
            ->assertForbidden();
    }

    public function test_admin_can_create_a_user(): void
    {
        $admin = User::factory()->create();
        $admin->assign('admin');

        $this->actingAs($admin)
            ->post(route('user.store'), [
                'nom' => 'Administre',
                'prenom' => 'Alice',
                'sexe' => 'femme',
                'email' => 'alice@example.test',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => 'utilisateur',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('users', ['email' => 'alice@example.test']);
        $this->assertTrue(User::query()->where('email', 'alice@example.test')->firstOrFail()->isA('utilisateur'));
    }

    public function test_admin_can_change_a_users_details_and_role(): void
    {
        $admin = User::factory()->create();
        $admin->assign('admin');
        $user = User::factory()->create();
        $user->assign('utilisateur');

        $this->actingAs($admin)
            ->get(route('user.edit', $user->getKey()))
            ->assertOk();

        $this->actingAs($admin)
            ->put(route('user.update', $user->getKey()), [
                'nom' => 'Modifie',
                'prenom' => 'Alice',
                'sexe' => 'femme',
                'email' => $user->email,
                'password' => '',
                'password_confirmation' => '',
                'role' => 'admin',
            ])
            ->assertRedirect(route('user.show', $user->getKey()));

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'nom' => 'Modifie',
        ]);
        $this->assertTrue($user->fresh()->isA('admin'));
    }

    public function test_non_admin_cannot_edit_a_user(): void
    {
        $user = User::factory()->create();
        $target = User::factory()->create();

        $this->actingAs($user)
            ->get(route('user.edit', $target->getKey()))
            ->assertForbidden();

        $this->actingAs($user)
            ->put(route('user.update', $target->getKey()), [])
            ->assertForbidden();
    }

    public function test_utilisateur_can_only_view_their_own_user_record(): void
    {
        $user = User::factory()->create(['email' => 'own@example.test']);
        $user->assign('utilisateur');
        $otherUser = User::factory()->create(['email' => 'other@example.test']);

        $this->actingAs($user)
            ->get(route('user.index'))
            ->assertSee('own@example.test')
            ->assertDontSee('other@example.test');

        $this->actingAs($user)
            ->get(route('user.show', $user->getKey()))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('user.show', $otherUser->getKey()))
            ->assertForbidden();
    }

    public function test_utilisateur_can_only_view_their_own_absences(): void
    {
        $user = User::factory()->create();
        $user->assign('utilisateur');
        $otherUser = User::factory()->create();
        $motif = Motif::factory()->create();
        $ownAbsence = AbsenceRecord::factory()->create([
            'user_id' => $user->id,
            'motif_id' => $motif->id,
            'date_debut' => '2026-09-10',
            'date_fin' => '2026-09-10',
        ]);
        $otherAbsence = AbsenceRecord::factory()->create([
            'user_id' => $otherUser->id,
            'motif_id' => $motif->id,
            'date_debut' => '2026-09-20',
            'date_fin' => '2026-09-20',
        ]);

        $this->actingAs($user)
            ->get(route('absence.index'))
            ->assertSee('10/09/2026')
            ->assertDontSee('20/09/2026');

        $this->actingAs($user)
            ->get(route('absence.show', $ownAbsence))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('absence.show', $otherAbsence))
            ->assertForbidden();
    }

    public function test_non_admin_can_update_only_their_own_absence(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $motif = Motif::factory()->create();
        $absence = AbsenceRecord::factory()->create([
            'user_id' => $otherUser->id,
            'motif_id' => $motif->id,
        ]);

        $this->actingAs($owner)
            ->put(route('absence.update', $absence), [
                'user_id' => $otherUser->id,
                'motif_id' => $motif->id,
                'date_debut' => '2026-09-10',
                'date_fin' => '2026-09-11',
            ])
            ->assertForbidden();

        $ownedAbsence = AbsenceRecord::factory()->create([
            'user_id' => $owner->id,
            'motif_id' => $motif->id,
        ]);

        $this->actingAs($owner)
            ->put(route('absence.update', $ownedAbsence), [
                'user_id' => $owner->id,
                'motif_id' => $motif->id,
                'date_debut' => '2026-09-12',
                'date_fin' => '2026-09-13',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('absences', [
            'id' => $ownedAbsence->id,
            'date_debut' => '2026-09-12',
        ]);
    }

    public function test_admin_can_update_another_users_absence(): void
    {
        $admin = User::factory()->create();
        $admin->assign('admin');
        $otherUser = User::factory()->create();
        $motif = Motif::factory()->create();
        $absence = AbsenceRecord::factory()->create([
            'user_id' => $otherUser->id,
            'motif_id' => $motif->id,
        ]);

        $this->actingAs($admin)
            ->put(route('absence.update', $absence), [
                'user_id' => $otherUser->id,
                'motif_id' => $motif->id,
                'date_debut' => '2026-09-14',
                'date_fin' => '2026-09-15',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('absences', [
            'id' => $absence->id,
            'date_debut' => '2026-09-14',
        ]);
    }

    public function test_admin_can_edit_and_delete_an_absence(): void
    {
        $admin = User::factory()->create();
        $admin->assign('admin');
        $owner = User::factory()->create();
        $motif = Motif::factory()->create();
        $absence = AbsenceRecord::factory()->create([
            'user_id' => $owner->id,
            'motif_id' => $motif->id,
        ]);

        $this->actingAs($admin)
            ->get(route('absence.edit', $absence->getKey()))
            ->assertOk();

        $this->actingAs($admin)
            ->delete(route('absence.destroy', $absence->getKey()))
            ->assertRedirect(route('user.show', $owner->getKey()));

        $this->assertDatabaseMissing('absences', ['id' => $absence->getKey()]);
    }
}
