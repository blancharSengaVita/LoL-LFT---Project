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
                'bio' => 'Un coach très impliqué, très serieux',
                'setup_completed' => true,
                'level' => 'Major league',
            ]);


        $g2 = User::factory()
            ->create([
                'email' => 'g2@proteam.gg',
                'game_name' => 'G2 Esports',
                'username' => '@G2',
                'account_type' => 'team',
                'birthday' => '2014-02-24',
                'nationality' => 'spanish',
                'region' => 'EUW',
                'job' => 'Staff',
                'bio' => 'Dominating the EU since 2014',
                'setup_completed' => true,
                'level' => 'Major league',
            ]);

        $kcorp = User::factory()
            ->create([
                'email' => 'kcorp@proteam.gg',
                'game_name' => 'Karmine Corp',
                'username' => '@Kcorp',
                'account_type' => 'team',
                'birthday' => '2020-03-01',
                'nationality' => 'french',
                'region' => 'EUW',
                'job' => 'Staff',
                'bio' => 'Blue Wall of EU',
                'setup_completed' => true,
                'level' => 'Major league',
            ]);

        $bds = User::factory()
            ->create([
                'email' => 'bds@proteam.gg',
                'game_name' => 'Team BDS',
                'username' => '@BDS',
                'account_type' => 'team',
                'birthday' => '2019-10-10',
                'nationality' => 'swiss',
                'region' => 'EUW',
                'job' => 'Staff',
                'bio' => 'Swiss precision on the Rift',
                'setup_completed' => true,
                'level' => 'Major league',
            ]);

        $nuc = User::factory()
            ->create([
                'email' => 'nuc@proplayer.gg',
                'game_name' => 'Nuclearint',
                'username' => '@Nuc',
                'account_type' => 'player',
                'birthday' => '2002-01-15',
                'nationality' => 'french',
                'region' => 'EUW',
                'job' => 'Mid',
                'bio' => 'Joueur mid laner pour la scène professionnelle, toujours prêt à carry.',
                'setup_completed' => true,
                'level' => 'Major league',
            ]);

        $skeanz = User::factory()
            ->create([
                'email' => 'skeanz@proplayer.gg',
                'game_name' => 'Skeanz',
                'username' => '@Skeanz',
                'account_type' => 'player',
                'birthday' => '1999-08-02',
                'nationality' => 'french',
                'region' => 'EUW',
                'job' => 'Jungle',
                'bio' => 'Main jungle avec un flair pour les combats intenses et les ganks bien calculés.',
                'setup_completed' => true,
                'level' => 'Professional',
            ]);

        $oneonethree = User::factory()
            ->create([
                'email' => '113@proplayer.gg',
                'game_name' => '113',
                'username' => '@113',
                'account_type' => 'player',
                'birthday' => '2003-02-13',
                'nationality' => 'turkish',
                'region' => 'EUW',
                'job' => 'Jungle',
                'bio' => 'Jeune talent prometteur, prêt à tout donner sur la faille.',
                'setup_completed' => true,
                'level' => 'Major league',
            ]);

        $jesus = User::factory()
            ->create([
                'email' => 'jesus@proplayer.gg',
                'game_name' => 'Jezu',
                'username' => '@Jesus',
                'account_type' => 'player',
                'birthday' => '2000-11-05',
                'nationality' => 'french',
                'region' => 'EUW',
                'job' => 'ADC',
                'bio' => 'Spécialiste du rôle de ADC, prêt à découper l\'équipe adverse.',
                'setup_completed' => true,
                'level' => 'Professional',
            ]);

        $canna = User::factory()
            ->create([
                'email' => 'canna@proplayer.gg',
                'game_name' => 'Canna',
                'username' => '@Canna',
                'account_type' => 'player',
                'birthday' => '2000-10-20',
                'nationality' => 'korean',
                'region' => 'KR',
                'job' => 'Top',
                'bio' => 'Force inébranlable de la toplane, défenseur de la victoire.',
                'setup_completed' => true,
                'level' => 'Major league',
            ]);

        $keria = User::factory()
            ->create([
                'email' => 'keria@proplayer.gg',
                'game_name' => 'Keria',
                'username' => '@Keria',
                'account_type' => 'player',
                'birthday' => '2002-10-14',
                'nationality' => 'korean',
                'region' => 'KR',
                'job' => 'Support',
                'bio' => 'Support exceptionnel avec une vision de jeu hors pair, prêt à protéger et guider l’équipe.',
                'setup_completed' => true,
                'level' => 'Challenger',
            ]);


        $users = [$squirtle, $blanchar, $doki, $striker, $nuc, $canna, $oneonethree, $jesus, $skeanz, $keria];
        $teams = [$g2, $bds, $kcorp];

        //Displayed things
        foreach ($users as $user) {
            DisplayedInformation::factory()->createMany([
                [
                    'user_id' => $user->id,
                ]
            ]);

            DisplayedInformationsOnce::factory()->createMany([
                [
                    'user_id' => $user->id,
                    'bio' => true,
                    'player_experiences' => true,
                    'awards' => true,
                    'skills' => true,
                    'languages' => true,
                ]
            ]);
        }
        foreach ($teams as $team) {
            DisplayedInformation::factory()->createMany([
                [
                    'user_id' => $team->id,
                ]
            ]);

            DisplayedInformationsOnce::factory()->createMany([
                [
                    'user_id' => $team->id,
                    'bio' => true,
                    'player_experiences' => true,
                    'awards' => true,
                    'skills' => true,
                    'languages' => true,
                ]
            ]);
        }


        //BLANCHAR
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

        //TOUT LE MONDE
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


        //Doki et Moi Conversation
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

        //ASSIGNING MISSION
        $m1 = OnboardingMission::where('name', 'addSection')->get()->first();
        $m2 = OnboardingMission::where('name', 'addMember')->get()->first();


        foreach ($teams as $team){
            UserMission::factory()->createMany([
                [
                    'user_id' => $team->id,
                    'mission_id' => $m1->id,
                ],
                [
                    'user_id' => $team->id,
                    'mission_id' => $m2->id,
                ]
            ]);
        }
        foreach ($users as $user) {
            UserMission::factory()->createMany([
                [
                    'user_id' => $user->id,
                    'mission_id' => $m1->id,
                ]
            ]);
        }

        //LFT POST
        LftPost::factory()->createMany([
            [
                'user_id' => $blanchar->id,
                'description' => 'salut',
                'job' => 'Undefined', //job
                'goal' => 'Ranked', //looking_for
                'ambiance' => 'Fun', //ambiance
                'published' => true,
            ],
        ]);

        //POST LFT
        LftPost::factory()->createMany([
            // Équipes
            [
                'user_id' => $g2->id,
                'description' => 'G2 cherche des opportunités en Major League pour conquérir la scène !',
                'job' => $g2->job,
                'goal' => 'Major Ligue',
                'ambiance' => 'Try-hard',
                'published' => true,
            ],
            [
                'user_id' => $kcorp->id,
                'description' => 'Kcorp est prêt pour la prochaine Minor League, à fond pour l\'ambiance sérieuse !',
                'job' => $kcorp->job,
                'goal' => 'Minor Ligue',
                'ambiance' => 'Serious',
                'published' => true,
            ],
            [
                'user_id' => $bds->id,
                'description' => 'Team BDS est ouvert pour de nouvelles opportunités compétitives en Major League.',
                'job' => $bds->job,
                'goal' => 'Major Ligue',
                'ambiance' => 'Try-hard',
                'published' => true,
            ],

            // Joueurs
            [
                'user_id' => $nuc->id,
                'description' => 'Nuclearint cherche une équipe sérieuse en Minor League.',
                'job' => $nuc->job,
                'goal' => 'Minor Ligue',
                'ambiance' => 'Serious',
                'published' => true,
            ],
            [
                'user_id' => $skeanz->id,
                'description' => 'Skeanz en recherche d\'une nouvelle aventure en Major League. Prêt à try-hard !',
                'job' => $skeanz->job,
                'goal' => 'Major Ligue',
                'ambiance' => 'Try-hard',
                'published' => true,
            ],
            [
                'user_id' => $oneonethree->id,
                'description' => '113 est disponible pour une Minor League avec une ambiance sérieuse.',
                'job' => $oneonethree->job,
                'goal' => 'Minor Ligue',
                'ambiance' => 'Serious',
                'published' => true,
            ],
            [
                'user_id' => $jesus->id,
                'description' => 'Jezu est prêt pour une nouvelle aventure compétitive en Major League.',
                'job' => $jesus->job,
                'goal' => 'Major Ligue',
                'ambiance' => 'Try-hard',
                'published' => true,
            ],
            [
                'user_id' => $canna->id,
                'description' => 'Canna est disponible pour rejoindre une Minor League avec une ambiance sérieuse.',
                'job' => $canna->job,
                'goal' => 'Minor Ligue',
                'ambiance' => 'Serious',
                'published' => true,
            ],
            [
                'user_id' => $keria->id,
                'description' => 'Keria recherche une équipe try-hard en Major League.',
                'job' => $keria->job,
                'goal' => 'Major Ligue',
                'ambiance' => 'Try-hard',
                'published' => true,
            ],
        ]);

    }
}
