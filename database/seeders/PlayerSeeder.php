<?php

namespace Database\Seeders;

use App\Models\Award;
use App\Models\Conversation;
use App\Models\DisplayedInformation;
use App\Models\DisplayedInformationsOnce;
use App\Models\Language;
use App\Models\LftPost;
use App\Models\Message;
use App\Models\OnboardingMission;
use App\Models\PlayerExperience;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserMission;
use Carbon\Carbon;
use Database\Factories\LftPostFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                'level' => 'Silver',
            ]);

        $squirtle = User::factory()
            ->create([
                'email' => 'squirtle0407@gmail.com',
                'game_name' => 'Squirle is back',
                'username' => '@Squirle',
                'account_type' => 'player',
                'birthday' => '2000-07-04',
                'nationality' => 'belgian',
                'region' => 'EUW',
                'job' => 'Supp',
                'bio' => 'Je vote à droite',
                'setup_completed' => true,
                'level' => 'Silver',
            ]);

        $doki = User::factory()
            ->create([
                'email' => '$doki@gmail.com',
                'game_name' => 'Doki',
                'username' => '@$doki',
                'account_type' => 'player',
                'birthday' => '2002-04-15',
                'nationality' => 'belgian',
                'region' => 'EUW',
                'job' => 'Top',
                'bio' => 'Piqué par league malgré moi',
                'setup_completed' => true,
                'level' => 'Platine',
            ]);

        $UwU = User::factory()
            ->create([
                'email' => 'UwU@gang.gg',
                'game_name' => 'UwU GanG',
                'username' => '@UwU',
                'account_type' => 'team',
                'birthday' => '2024-07-30',
                'nationality' => 'belgian',
                'region' => 'EUW',
                'job' => 'Staff',
                'bio' => 'Toujours cute, toujours kawainé',
                'setup_completed' => false,
                'level' => 'Argent',
            ]);

        $striker = User::factory()
            ->create([
                'email' => 'striker@salut.com',
                'game_name' => 'striker',
                'username' => '@striker',
                'account_type' => 'staff',
                'birthday' => '2024-07-30',
                'nationality' => 'belgian',
                'region' => 'EUW',
                'job' => 'Assistant coach',
                'bio' => 'Toujours cute, toujours kawainé',
                'setup_completed' => true,
                'level' => 'Ligue majeure',
            ]);

        $users = [$squirtle, $blanchar, $doki, $striker];


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

        foreach ($users as $user) {
            DisplayedInformation::factory()->createMany([
                [
                    'user_id' => $user->id,
                ]
            ]);
        }

        DisplayedInformation::factory()->createMany([
            [
                'user_id' => $UwU->id,
            ]
        ]);

        DisplayedInformationsOnce::factory()->createMany([
            [
                'user_id' => $blanchar->id,
                'bio' => true,
                'player_experiences' => true,
                'awards' => true,
                'skills' => true,
                'languages' => true,
            ]
        ]);

        DisplayedInformationsOnce::factory()->createMany([
            [
                'user_id' => $striker->id,
                'bio' => true,
                'player_experiences' => true,
                'awards' => true,
                'skills' => true,
                'languages' => true,
            ]
        ]);

        DisplayedInformationsOnce::factory()->createMany([
            [
                'user_id' => $doki->id,
                'bio' => true,
                'player_experiences' => true,
                'awards' => true,
                'skills' => true,
                'languages' => true,
            ]
        ]);

        DisplayedInformationsOnce::factory()->createMany([
            [
                'user_id' => $UwU->id,
                'bio' => true,
                'player_experiences' => true,
                'awards' => true,
                'skills' => true,
                'languages' => true,
            ]
        ]);

        DisplayedInformationsOnce::factory()->createMany([
            [
                'user_id' => $squirtle->id,
                'bio' => true,
                'player_experiences' => true,
                'awards' => true,
                'skills' => true,
                'languages' => true,
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
                'name' => 'addMember',
                'title' => 'Ajouter un membre',
                'description' => 'Ajoutez de nouveaux membres à votre équipe, qu\'ils soient joueurs ou membres du staff, et complétez leurs profils avec des informations détaillées.',
                'button_title' => 'Ajouter un membre'
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

        DB::table('team_members')->insert([
            'team_id' => $UwU->id,
            'username' => 'Mini',
            'type' => 'player',
            'nationality' => 'French',
            'job' => 'Fill',
            'entry_date' => Carbon::now()->format('Y-m-d'),
            'archived' => true,
        ]);

        DB::table('team_members')->insert([
            'team_id' => $UwU->id,
            'type' => 'player',
            'username' => 'DoKi',
            'nationality' => 'French',
            'job' => 'Mid',
            'entry_date' => Carbon::now()->format('Y-m-d'),
            'archived' => false,
        ]);

        DB::table('team_members')->insert([
            'team_id' => $UwU->id,
            'type' => 'player',
            'username' => 'Squirlte',
            'nationality' => 'Belgian',
            'job' => 'Support',
            'entry_date' => Carbon::now()->format('Y-m-d'),
            'archived' => false,
        ]);

        DB::table('team_members')->insert([
            'team_id' => $UwU->id,
            'username' => 'Striker',
            'type' => 'staff',
            'nationality' => 'French',
            'job' => 'Head coach',
            'entry_date' => Carbon::now()->format('Y-m-d'),
            'archived' => true,
        ]);

        DB::table('team_members')->insert([
            'team_id' => $UwU->id,
            'type' => 'staff',
            'username' => 'Reha',
            'nationality' => 'French',
            'job' => 'Assistant coach',
            'entry_date' => Carbon::now()->format('Y-m-d'),
            'archived' => false,
        ]);

        DB::table('team_members')->insert([
            'team_id' => $UwU->id,
            'type' => 'staff',
            'username' => 'Nalkya',
            'nationality' => 'Belgian',
            'job' => 'Analyst',
            'entry_date' => Carbon::now()->format('Y-m-d'),
            'archived' => false,
        ]);

        $m1 = OnboardingMission::where('name', 'addSection')->get()->first();
        $m2 = OnboardingMission::where('name', 'addMember')->get()->first();

        Conversation::factory()->create([
            'user_one_id' => $blanchar->id,
            'user_two_id' => $doki->id,
        ]);

        Message::factory()->createMany([
            [
                'conversation_id' => 1,
                'user_id' => $doki->id,
                'message' => 'Salut',
            ],
            [
                'conversation_id' => 1,
                'user_id' => $blanchar->id,
                'message' => 'Salut',
            ],
            [
                'conversation_id' => 1,
                'user_id' => $doki->id,
                'message' => 'Comment va va',
            ],
            [
                'conversation_id' => 1,
                'user_id' => $blanchar->id,
                'message' => 'Bien et toi',
            ],
        ]);

        UserMission::factory()->createMany([
            [
                'user_id' => $UwU->id,
                'mission_id' => $m1->id,
            ],
            [
                'user_id' => $UwU->id,
                'mission_id' => $m2->id,
            ]
        ]);

        LftPost::factory()->createMany([
            [
                'user_id' => $blanchar->id,
                'description' => 'Cherche un duo avec qui s\'amuser peu importe le mode de jeux',
                'job' => '', //job
                'goal' => '', //looking_for
                'ambiance' => 'Fun', //ambiance
                'published' => true,
            ],
        ]);

        LftPost::factory()->createMany([
            [
                'user_id' => $UwU->id,
                'description' => 'Cherche des gens avec qui on pourrait souvent clash',
                'job' => '', //job
                'goal' => 'Clash', //looking_for
                'ambiance' => 'Try-hard', //ambiance
                'published' => true,
            ],
        ]);

        LftPost::factory()->createMany([
            [
                'user_id' => $doki->id,
                'description' => 'Je suis une personne calme qui aime beaucoup jouer Neeko et Gwen, je cherche un coach qui pourrait m\'aider à ameliorer mon niveau de jeu',
                'job' => 'Performance coach', //job
                'goal' => 'Ranked', //looking_for
                'ambiance' => '', //ambiance
                'published' => true,
            ],
        ]);

        LftPost::factory()->createMany([
            [
                'user_id' => $striker->id,
                'description' => 'Recherche une équipe pour gagner les championnat du monde',
                'job' => 'team', //job
                'goal' => 'Major Ligue', //looking_for
                'ambiance' => 'Try-hard', //ambiance
                'published' => true,
            ],
        ]);

        LftPost::factory()->createMany([
            [
                'user_id' => $squirtle->id,
                'description' => 'Chercher une personne avec qui je pourrais monter haut dans le classement',
                'job' => 'ADC', //job
                'goal' => 'Ranked', //looking_for
                'ambiance' => 'Serious', //ambiance
                'published' => true,
            ],
        ]);

        foreach ($users as $user) {
            UserMission::factory()->createMany([
                [
                    'user_id' => $user->id,
                    'mission_id' => $m1->id,
                ]
            ]);
        }
    }
}
