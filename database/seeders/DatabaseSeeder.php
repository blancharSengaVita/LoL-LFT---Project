<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'email' => 'anchar2107@gmail.com',
            'game_name' => 'SparklesSupa',
            'display_name' => 'SparklesSupa',
            'firstname' => 'Blanchar',
            'lastname' => 'Senga-Vita',
            'account_type' => 'player',
            'birthday' => '2000-07-21',
            'nationality' => 'belgian',
            'region' => 'EUW',
            'job' => 'Undefined',
            'bio' => 'Salut',
        ]);
    }
}
