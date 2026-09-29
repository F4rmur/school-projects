<?php

namespace Tests\Feature;

use App\Models\absence;
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
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('users', ['email' => 'alice@example.test']);
    }

    public function test_non_admin_can_update_only_their_own_absence(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $motif = Motif::factory()->create();
        $absence = absence::factory()->create([
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

        $ownedAbsence = absence::factory()->create([
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
        $absence = absence::factory()->create([
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
}
