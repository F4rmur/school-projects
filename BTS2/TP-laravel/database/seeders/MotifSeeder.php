<?php

namespace Database\Seeders;

use App\Models\Motif;
use Illuminate\Database\Seeder;

class MotifSeeder extends Seeder
{
    protected $model = Motif::class;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->model::factory()->count(10)->create();
    }
}
