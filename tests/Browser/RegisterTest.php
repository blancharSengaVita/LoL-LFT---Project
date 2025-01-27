<?php

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
            ->pause(20000)
            ->waitForText('Blanchar')
            ->assertPathIs('/dashboard');
    });
});

test('that assert we make the first mission of onboarding', function () {
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
            ->waitForText('Expérience ajouté avec succès')
            ->screenshot('end');
    });
});






