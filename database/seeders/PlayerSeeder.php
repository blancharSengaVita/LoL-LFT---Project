<?php

namespace Database\Seeders;

use App\Models\Award;
use App\Models\DisplayedInformation;
use App\Models\DisplayedInformationsOnce;
use App\Models\Language;
use App\Models\OnboardingMission;
use App\Models\PlayerExperience;
use App\Models\Skill;
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
                'username' => '@SparklesSupa',
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
            ]
        ]);

        DisplayedInformationsOnce::factory()->createMany([
            [
                'user_id' => $blanchar->id,
                'bio' => true,
                'player_experiences' => true,
                'awards'=> true,
                'skills' => true,
                'languages'=> true,
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

        //TODO: Limiter le nombre de skills à 3 pour les top 3 skills
        Skill::factory()->createMany([
            [
                'user_id' => $blanchar->id,
                'name' => 'Roaming',
            ],
            [
                'user_id' => $blanchar->id,
                'name' => 'Wave management',
            ],
            [
                'user_id' => $blanchar->id,
                'name' => 'Shootcalling',
            ],
            [
                'user_id' => $blanchar->id,
                'name' => 'Vision Control',
            ],
            [
                'user_id' => $blanchar->id,
                'name' => 'Ocean champion pool',
            ],
            [
                'user_id' => $blanchar->id,
                'name' => 'Lane Swapping',
            ],
            [
                'user_id' => $blanchar->id,
                'name' => 'Laning Phase',
            ],
            [
                'user_id' => $blanchar->id,
                'name' => 'Teamfighting',
            ],
            [
                'user_id' => $blanchar->id,
                'name' => 'Objective Control',
            ],
            [
                'user_id' => $blanchar->id,
                'name' => 'Map Awareness',
            ],
        ]);

        Language::factory()->createMany([
            [
                'user_id' => $blanchar->id,
                'name' => 'French',
                'level' => 'C2 - Langues maternelle',
            ],
            [
                'user_id' => $blanchar->id,
                'name' => 'English',
                'level' => 'B1 - Intermédiaire',
            ],
            [
                'user_id' => $blanchar->id,
                'name' => 'Japanese',
                'level' => 'Je sais dire bonjour',
            ],
        ]);

        OnboardingMission::factory()->createMany([
            [
                'name' => 'addSection',
                'title' => 'Ajouter une section',
                'description' => 'Optimisez votre profil en ligne en ajoutant des sections détaillées sur vos experiences, vos compétences et vos recompenses.',
                'button_title' => 'Nouvelle section'
            ],
            [
                'name' => 'createLftPost',
                'title' => 'Faire un poste LFT',
                'description' => 'Poster une annonce "Looking for a Team" pour trouver des coéquipiers ou une équipe',
                'button_title' => 'Nouveau poste'
            ],
            [
                'name' => 'openLft',
                'title' => 'Faire une demande de duo ou d\'équipe',
                'description' => 'Envoyez des demandes de duo ou d\'équipe pour élargir votre réseau et rencontrer des nouveaux joueurs avec qui jouer et échanger',
                'button_title' => 'Chercher des partenaires'
            ],
            [
                'name' => 'linkRiotAccount',
                'title' => 'Lier son compte League of Legends',
                'description' => 'Lier votre compte League of Legends pour montrer vos statistiques et vos performances à vos potentiels coéquipiers.',
                'button_title' => 'Lier son compte'
            ],
            [
                'name' => 'completeBio',
                'title' => 'Complete ta Bio',
                'description' => 'Donne une description complète de toi-même, tes intérêts, tes expériences et ce que tu recherches chez tes coéquipiers ou ta prochaine équipe.',
                'button_title' => 'Completer la bio'
            ],
        ]);

    }
}
