<?php

namespace Tests\Feature;

use App\Mail\AbsenceRequestApproved;
use App\Mail\AbsenceRequestCreated;
use App\Models\absence as AbsenceRecord;
use App\Models\Motif;
use App\Models\users as UserRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AbsenceMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_absence_request_mail_renders_escaped_user_and_motif_content(): void
    {
        $maliciousFirstName = '<script>alert('.fake()->randomNumber(3).')</script>';
        $maliciousLastName = '<img src=x onerror=alert('.fake()->randomNumber(3).')>';
        $maliciousMotif = '<script>alert('.fake()->randomNumber(3).')</script>';
        $user = UserRecord::factory()->create([
            'nom' => $maliciousLastName,
            'prenom' => $maliciousFirstName,
        ]);
        $motif = Motif::factory()->create(['libelle' => $maliciousMotif]);
        $absence = $this->createAbsence($user, $motif);
        $mail = new AbsenceRequestCreated($absence);

        $rendered = $mail->render();

        $this->assertSame(__('ui.mail.absence_request_subject'), $mail->envelope()->subject);
        $this->assertStringContainsString('&lt;script&gt;', $rendered);
        $this->assertStringNotContainsString($maliciousFirstName, $rendered);
        $this->assertStringNotContainsString($maliciousLastName, $rendered);
        $this->assertStringNotContainsString('<img src=x', $rendered);
        $this->assertStringNotContainsString($maliciousMotif, $rendered);
    }

    public function test_approval_mail_renders_approver_and_translated_subject(): void
    {
        $user = UserRecord::factory()->create();
        $approver = UserRecord::factory()->create([
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
        ]);
        $motif = Motif::factory()->create();
        $absence = $this->createAbsence($user, $motif, [
            'approved_by' => $approver->getKey(),
            'date_debut' => '2026-10-05',
            'date_fin' => '2026-10-06',
        ]);
        $mail = new AbsenceRequestApproved($absence);

        $rendered = $mail->render();

        $this->assertSame(__('ui.mail.absence_approved_subject'), $mail->envelope()->subject);
        $this->assertStringContainsString($approver->prenom.' '.$approver->nom, $rendered);
        $this->assertStringContainsString('05/10/2026', $rendered);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createAbsence(UserRecord $user, Motif $motif, array $attributes = []): AbsenceRecord
    {
        return AbsenceRecord::factory()->create([
            'user_id' => $user->getKey(),
            'motif_id' => $motif->getKey(),
            ...$attributes,
        ]);
    }
}
