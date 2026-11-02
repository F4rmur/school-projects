<?php

namespace Tests\Feature\Repositories;

use App\Models\absence as AbsenceRecord;
use App\Models\Motif;
use App\Repositories\Contracts\MotifRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentMotifRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_returns_motifs_and_motif_relationship_returns_its_absences(): void
    {
        $firstMotif = Motif::factory()->create();
        $secondMotif = Motif::factory()->create();
        $absence = AbsenceRecord::factory()->create(['motif_id' => $firstMotif->getKey()]);

        $motifs = app(MotifRepository::class)->all();

        $this->assertCount(2, $motifs);
        $this->assertTrue($motifs->contains($firstMotif));
        $this->assertTrue($motifs->contains($secondMotif));
        $this->assertTrue($firstMotif->absences->contains($absence));
    }
}
