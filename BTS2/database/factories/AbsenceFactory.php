<?php

namespace Database\Factories;

use App\Models\absence;
use App\Models\Motif;
use App\Models\users;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<absence>
 */
class AbsenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => users::factory(),
            'motif_id' => Motif::factory(),
            'conges_payes' => false,
            'type_conge' => null,
            'date_debut' => fake()->dateTimeBetween('-1 year', 'today')->format('Y-m-d'),
            'date_fin' => fake()->dateTimeBetween('today', '+1 month')->format('Y-m-d'),
        ];
    }
}
