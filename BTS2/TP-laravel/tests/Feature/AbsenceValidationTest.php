<?php

namespace Tests\Feature;

use App\Models\absence as AbsenceRecord;
use App\Models\Motif;
use App\Models\User;
use App\Models\users as UserRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class AbsenceValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_invalid_request_stops_before_business_rule_checks(): void
    {
        $admin = User::factory()->create();
        $admin->assign('admin');
        $motif = Motif::factory()->create();

        $this->actingAs($admin)
            ->post(route('absence.store'), [
                'motif_id' => $motif->getKey(),
                'date_debut' => '2026-10-05',
                'date_fin' => '2026-10-05',
            ])
            ->assertSessionHasErrors('user_id');
    }

    public function test_paternity_leave_is_rejected_for_a_woman(): void
    {
        $user = UserRecord::factory()->create(['sexe' => 'femme']);

        $this->submitAbsence($user, 'paternite', '2026-10-05', '2026-10-05')
            ->assertSessionHasErrors('type_conge');
    }

    public function test_maternity_leave_is_rejected_for_a_man(): void
    {
        $user = UserRecord::factory()->create(['sexe' => 'homme']);

        $this->submitAbsence($user, 'maternite', '2026-10-05', '2026-10-05')
            ->assertSessionHasErrors('type_conge');
    }

    public function test_parental_leave_is_accepted_for_the_matching_gender(): void
    {
        Mail::fake();
        $woman = UserRecord::factory()->create(['sexe' => 'femme']);
        $man = UserRecord::factory()->create(['sexe' => 'homme']);

        $this->submitAbsence($woman, 'maternite', '2026-10-05', '2026-10-05')
            ->assertRedirect(route('user.show', $woman));

        $this->submitAbsence($man, 'paternite', '2026-10-06', '2026-10-06')
            ->assertRedirect(route('user.show', $man));

        $this->assertDatabaseCount('absences', 2);
    }

    public function test_absence_overlapping_an_existing_request_is_rejected(): void
    {
        $user = UserRecord::factory()->create();
        $motif = Motif::factory()->create();
        AbsenceRecord::factory()->create([
            'user_id' => $user->getKey(),
            'motif_id' => $motif->getKey(),
            'date_debut' => '2026-10-05',
            'date_fin' => '2026-10-07',
        ]);

        $this->submitAbsence($user, null, '2026-10-07', '2026-10-08', $motif)
            ->assertSessionHasErrors('date_fin');
    }

    public function test_paid_leave_limit_includes_existing_legacy_paid_leave(): void
    {
        $user = UserRecord::factory()->create();
        $motif = Motif::factory()->create();
        AbsenceRecord::factory()->create([
            'user_id' => $user->getKey(),
            'motif_id' => $motif->getKey(),
            'conges_payes' => false,
            'type_conge' => 'conges_payes',
            'date_debut' => '2026-01-01',
            'date_fin' => '2026-01-16',
        ]);

        $this->submitAbsence($user, 'conges_payes', '2026-01-20', '2026-01-29', $motif)
            ->assertSessionHasErrors('date_fin');
    }

    public function test_paid_leave_can_span_two_years_when_each_year_is_within_its_limit(): void
    {
        Mail::fake();
        $user = UserRecord::factory()->create();
        $motif = Motif::factory()->create();

        $this->submitAbsence($user, 'conges_payes', '2026-12-20', '2027-01-05', $motif)
            ->assertRedirect(route('user.show', $user));

        $absence = AbsenceRecord::query()->where('user_id', $user->getKey())->firstOrFail();
        $this->assertTrue($absence->conges_payes);
        $this->assertSame('2026-12-20', $absence->date_debut->toDateString());
        $this->assertSame('2027-01-05', $absence->date_fin->toDateString());
    }

    private function submitAbsence(
        UserRecord $user,
        ?string $leaveType,
        string $dateStart,
        string $dateEnd,
        ?Motif $motif = null,
    ): TestResponse {
        $admin = User::factory()->create();
        $admin->assign('admin');
        $motif ??= Motif::factory()->create();

        return $this->actingAs($admin)->post(route('absence.store'), [
            'user_id' => $user->getKey(),
            'motif_id' => $motif->getKey(),
            'type_conge' => $leaveType,
            'date_debut' => $dateStart,
            'date_fin' => $dateEnd,
        ]);
    }
}
