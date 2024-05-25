<?php

namespace Database\Seeders;

use App\Models\PlayerExperiences;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PlayerSeeder::class,
            // Ajoutez ici d'autres seeders si nécessaire
        ]);
        // User::factory(10)->create();

        User::factory()->create([
            'email' => 'test@example.com',
        ]);

    }
}
