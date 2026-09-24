<?php

namespace Database\Seeders;

use App\Models\absence;
use Illuminate\Database\Seeder;

class AbsenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        absence::factory()->count(20)->create();
    }
}
