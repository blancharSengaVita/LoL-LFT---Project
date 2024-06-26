<?php

namespace Database\Seeders;

use App\Models\Award;
use App\Models\DisplayedInformation;
use App\Models\PlayerExperience;
use App\Models\User;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blanchar = User::factory()
            ->create([
                'email' => 'anchar2107@gmail.com',
                'game_name' => 'SparklesSupa',
                'displayed_name' => 'SparklesSupa',
                'firstname' => 'Blanchar',
                'lastname' => 'Senga-Vita',
                'account_type' => 'player',
                'birthday' => '2000-07-21',
                'nationality' => 'belgian',
                'region' => 'EUW',
                'job' => 'Mid',
                'bio' => 'Je m\'appelle Blanchar Senga-Vita. Je joue à Leaque of Legends depuis 2019, je cumule 200 heures de jeux. Je suis sur cette application car, je voudrais créer ma propre structure les "junkyards" Une bande d\'ami qui joue à league of legends pour s\'amuser',
                'setup_completed' => true,
            ]);

        PlayerExperience::factory()->createMany([
            [
                'user_id' => $blanchar->id,
                'placement' => '1',
                'event' => 'Clash : Coupe de Noxus',
                'team' => 'Junkyard',
                'job' => 'Jungle',
                'date' => '2024-01-27',
            ],
            [
                'user_id' => $blanchar->id,
                'placement' => '2',
                'event' => 'Clash : Coupe de Demacia',
                'team' => 'Warriors',
                'job' => 'Support',
                'date' => '2024-02-15',
            ],
            [
                'user_id' => $blanchar->id,
                'placement' => '3',
                'event' => 'Clash : Coupe des Iles Obscures',
                'team' => 'Shadow Isles',
                'job' => 'Mid',
                'date' => '2024-03-10',
            ],
            [
                'user_id' => $blanchar->id,
                'placement' => '4',
                'event' => 'Clash : Coupe de Shurima',
                'team' => 'Desert Warriors',
                'job' => 'Top',
                'date' => '2024-04-05',
            ],
            [
                'user_id' => $blanchar->id,
                'placement' => '5',
                'event' => 'Clash : Coupe de Piltover',
                'team' => 'Tech Innovators',
                'job' => 'ADC',
                'date' => '2024-05-20',
            ],
            [
                'user_id' => $blanchar->id,
                'placement' => '6',
                'event' => 'Clash : Coupe de Zaun',
                'team' => 'Chemtech Savages',
                'job' => 'Support',
                'date' => '2024-06-15',
            ],
        ]);
        DisplayedInformation::factory()->createMany([
            [
                'user_id' => $blanchar->id,
                'bio' => '0',
                'player_experiences' => '0'
            ]
        ]);

        Award::factory()->createMany([
            [
                'user_id' => $blanchar->id,
                'title' => 'Meilleur rookie',
                'event' => 'LEC',
                'team' => 'Junkyard',
                'date' => '2024-06-23',
            ],
            [
                'user_id' => $blanchar->id,
                'title' => 'MVP du split',
                'event' => 'LEC',
                'team' => 'Junkies',
                'date' => '2024-04-15',
            ],
            [
                'user_id' => $blanchar->id,
                'title' => 'Meilleur carry AD',
                'event' => 'LEC',
                'team' => 'Junkies',
                'date' => '2024-05-10',
            ],
            [
                'user_id' => $blanchar->id,
                'title' => 'Meilleur joueur des playoffs',
                'event' => 'LEC',
                'team' => 'Junkies',
                'date' => '2024-06-30',
            ],
            [
                'user_id' => $blanchar->id,
                'title' => 'Meilleur KDA',
                'event' => 'LEC',
                'team' => 'Junkies',
                'date' => '2024-06-01',
            ],
            [
                'user_id' => $blanchar->id,
                'title' => 'Meilleur joueur d\'Europe',
                'event' => 'Worlds',
                'team' => 'Junkies',
                'date' => '2024-10-15',
            ]
        ]);
    }
}
