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
        $user = UserRecord::factory()->create([
            'nom' => '<img src=x onerror=alert(1)>',
            'prenom' => '<script>alert(1)</script>',
        ]);
        $motif = Motif::factory()->create(['libelle' => '<script>alert(2)</script>']);
        $absence = AbsenceRecord::factory()->create([
            'user_id' => $user->getKey(),
            'motif_id' => $motif->getKey(),
            'date_debut' => '2026-10-05',
            'date_fin' => '2026-10-06',
        ]);
        $mail = new AbsenceRequestCreated($absence);

        $rendered = $mail->render();

        $this->assertSame(__('ui.mail.absence_request_subject'), $mail->envelope()->subject);
        $this->assertStringContainsString('&lt;script&gt;', $rendered);
        $this->assertStringNotContainsString('<script>alert(1)</script>', $rendered);
        $this->assertStringNotContainsString('<img src=x', $rendered);
        $this->assertStringNotContainsString('<script>alert(2)</script>', $rendered);
    }

    public function test_approval_mail_renders_approver_and_translated_subject(): void
    {
        $user = UserRecord::factory()->create();
        $approver = UserRecord::factory()->create([
            'nom' => 'Approbateur',
            'prenom' => 'Alex',
        ]);
        $motif = Motif::factory()->create();
        $absence = AbsenceRecord::factory()->create([
            'user_id' => $user->getKey(),
            'motif_id' => $motif->getKey(),
            'approved_by' => $approver->getKey(),
            'date_debut' => '2026-10-05',
            'date_fin' => '2026-10-06',
        ]);
        $mail = new AbsenceRequestApproved($absence);

        $rendered = $mail->render();

        $this->assertSame(__('ui.mail.absence_approved_subject'), $mail->envelope()->subject);
        $this->assertStringContainsString('Alex Approbateur', $rendered);
        $this->assertStringContainsString('05/10/2026', $rendered);
    }
}
