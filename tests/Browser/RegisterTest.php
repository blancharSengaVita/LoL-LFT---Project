<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;

test('that assert an user can register', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->visit('/register')
            ->type('email', 'anchar21111117@gmail.com')
            ->type('password', 'P@ssword1234')
            ->type('password_confirmation', 'P@ssword1234')
            ->press('S\'inscrire')
            ->waitForText('Etape 1 : Quel type de compte voulez-vous créer')
            ->assertPathIs('/profil-creation/account-type');
    });
});


test('that assert an user can complete step 1 of profile création', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->waitForText('Joueur')
            ->click('@player')
            ->waitFor('@next-button')
            ->press('@next-button')
            ->waitForText('Etape 2 : Pouvez-vous vous présentez ?')
            ->assertPathIs('/profil-creation/general-info');
    });
});

test('that assert an user can complete step 2 of profile création', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->type('#game_name', 'Blanchar')
            ->type('#usurname', 'Blanchar')
            ->select('#nationality')
            ->waitFor('button[type="submit"]')
            ->scrollIntoView('button[type="submit"]')
            ->keys('#birthday', '1990', '{tab}', '01', '01')
            ->pause(1000)
            ->press('Suivant')
            ->waitForText('Etape 3 : Dites-moi en plus sur vous')
            ->assertPathIs('/profil-creation/additional-info');
    });
});

test('that assert an user can complete step 3 of profile création', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->waitFor('#job')
            ->select('#job', 'Mid')
            ->waitFor('#region')
            ->select('#region', 'Brésil (BR)')
            ->waitFor('#level',)
            ->select('#level', 'Challenger')
            ->waitForText('Bio',)
            ->type('#bio', 'Je suis beau')
            ->scrollIntoView('button[type="submit"]')
            ->waitFor('button[type="submit"]',)
            ->waitForText('Entre dans la faille !',)
            ->pause(400)
            ->click('button[type="submit"]')
            ->waitForText('Blanchar')
            ->assertPathIs('/dashboard');
    });
});

test('that assert we make the "add experience" mission of onboarding', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->waitForText('Blanchar')
            ->press('Nouvelle section')
            ->waitForText('Ajouter une section')
            ->click('@onBoardingExperience')
            ->waitForText('Experience')
            ->type('#event', 'Worlds')
            ->type('#team', 'KC')
            ->select('#job', 'Mid')
            ->type('#placement', 1)
            ->keys('#date', '24', '11', '2024')
            ->press('Sauvegarder')
            ->waitForText('Expérience ajouté avec succès');
    });
});

test('that assert we make the "add award" mission of onboarding', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->waitForText('Blanchar')
            ->press('Nouvelle section')
            ->waitForText('Ajouter une section')
            ->click('@onBoardingAward')
            ->waitForText('Récompense')
            ->type('#title', 'Meilleurs joueurs des Worlds')
            ->type('@awardEvent', 'Worlds')
            ->type('@awardTeam', 'KC')
            ->keys('@awardDate', '24', '11', '2024')
            ->press('Sauvegarder')
            ->waitForText('Récompense ajouté avec succès');
    });
});


test('that assert we make the "add skill" mission of onboarding', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->waitForText('Blanchar')
            ->press('Nouvelle section')
            ->waitForText('Ajouter une section')
            ->click('@onBoardingSkill')
            ->waitForText('Compétence')
            ->type('@skillTitle', 'Résilience')
            ->press('Sauvegarder')
            ->waitForText('Compétence ajouté avec succès');
    });
});


test('that assert we add more skills on profile', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->scrollIntoView('@DashboardUserGameName')
            ->scrollIntoView('@DashboardSkills')
            ->assertSee('Résilience')
            ->press('@createsingleSkill')
            ->waitForText('Compétence')
            ->type('@skillTitle', 'À l\'écoute')
            ->press('Sauvegarder')
            ->waitForText('Compétence ajouté avec succès')
            ->press('@createsingleSkill')
            ->waitForText('Compétence')
            ->type('@skillTitle', 'Esprit analytique')
            ->press('Sauvegarder')
            ->scrollIntoView('@DashboardSkills')
            ->waitForText('Compétence ajouté avec succès')
            ->press('@createsingleSkill')
            ->waitForText('Compétence')
            ->type('@skillTitle', 'Adaptibilité')
            ->press('Sauvegarder')
            ->waitForText('Compétence ajouté avec succès');
        $browser->script([
            'document.body.scrollTop = 0',
            'document.documentElement.scrollTop = 0',
        ]);
    });
});

test('that assert we can delete a skill', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->scrollIntoView('@DashboardSkills')
            ->press('@deleteSkill-14')
            ->waitForText('Supprimer une compétence')
            ->Press('Supprimer')
            ->screenshot('end');
        $browser->script([
            'document.body.scrollTop = 0',
            'document.documentElement.scrollTop = 0',
        ]);
    });
});

test('that assert we can modify a skill', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->scrollIntoView('@DashboardSkills')
            ->press('@editSkill-13')
            ->waitForText('Compétence')
            ->type('@skillTitle', 'Adaptibilité de ouf')
            ->Press('Sauvegarder')
            ->waitForText('Compétence modifiée avec succès')
            ->screenshot('end');
        $browser->script([
            'document.body.scrollTop = 0',
            'document.documentElement.scrollTop = 0',
        ]);
    });
});

test('that hide and show button works', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->scrollIntoView('@DashboardSkills')
            ->waitFor('@skillsShowMore')
            ->waitForText('Afficher plus')
            ->press('@skillsShowMore')
            ->waitFor('@skillsShowMore')
            ->waitForText('Afficher moins')
            ->press('@skillsShowMore')
            ->screenshot('end');
    });
});

test('that assert we can add languages', function () {
    $this->browse(function (Browser $browser) {
        $browser->script([
            'document.body.scrollTop = 0',
            'document.documentElement.scrollTop = 0',
        ]);

        $browser
            ->waitForText('Blanchar')
            ->press('Nouvelle section')
            ->waitForText('Ajouter une section')
            ->click('@onBoardingLanguage')
            ->waitForText('Langues')
            ->select('@languageName', 'English')
            ->select('@languageLevel', 'C2 - Proficient')
            ->press('Sauvegarder')
            ->waitForText('Langue ajouté avec succès');
    });
});

test('that assert we can validate mission', function () {
    $this->browse(function (Browser $browser) {
        $browser->script([
            'document.body.scrollTop = 0',
            'document.documentElement.scrollTop = 0',
        ]);

        $browser
            ->waitForText('Valider la mission')
            ->press('Valider la mission')
            ->waitForText('Mission effectué avec succès')
            ->press('Archiver cette section');
    });
});

test('that assert we can find teammate', function () {
    $this->browse(function (Browser $browser,) {
        $browser->script([
            'document.body.scrollTop = 0',
            'document.documentElement.scrollTop = 0',
        ]);

        $browser
            ->click('@findTeammateSideMenu')
            ->waitForText('Mon post LFT')
            ->assertPathIs('/find-teammate')
            ->select('@FindTeammateAmbiance', 'Fun')
            ->assertSee('SparklesSupa')
            ->press('Demande LFT')
            ->waitForText('Demande envoyé')
            ->assertSee('Demande envoyé')
            ->press('Message')
            ->waitForText('SparklesSupa')
            ->assertPathIs('/messages*')
            ->type('@ConversationMessageInput', 'Coucou Comment ça va ?')
            ->press('@ConversationMessageSending')
            ->waitForText('Coucou Comment ça va ?')
            ->press('@headerMenu')
            ->waitForText('Se déconnecter')
            ->click('@DisconnectButton');
    });
});

test('that assert SparklesSupa can answer to the registerd user', function () {
    $this->browse(function (Browser $browser,) {
        $sparklesSupa = User::where('email', 'anchar2107@gmail.com')->first();

        $browser->visit('/login')
            ->waitForText('Connectez-Vous')
            ->type('email', $sparklesSupa->email)
            ->type('password', 'password')
            ->press('Se connecter')
            ->waitForText($sparklesSupa->game_name)
            ->assertPathIs('/dashboard')
            ->click('@messageSideMenu')
            ->waitFor('@conversation-Blanchar')
            ->click('@conversation-Blanchar')
            ->waitForText('Coucou Comment ça va ?')
            ->assertSee('Coucou Comment ça va ?')
            ->type('@ConversationMessageInput', 'bien et toi')
            ->press('@ConversationMessageSending')
            ->waitForText('bien et toi');
    });
});


