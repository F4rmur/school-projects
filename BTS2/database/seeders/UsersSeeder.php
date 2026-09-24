<?php

namespace Database\Seeders;

use App\Models\users;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        users::factory()->count(10)->create();
    }
}
