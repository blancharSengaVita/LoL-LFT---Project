<?php

use Laravel\Dusk\Browser;

test('that assert an user can register', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->visit('/register')
            ->type('email', 'KarmineCorp@gmail.com')
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
            ->waitForText('Team')
            ->click('@team')
            ->waitFor('@next-button')
            ->press('@next-button')
            ->waitForText('Etape 2 : Pouvez-vous vous présentez ?')
            ->assertPathIs('/profil-creation/general-info');
    });
});

test('that assert an user can complete step 2 of profile création', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->type('#game_name', 'Karmine')
            ->type('#usurname', 'Karmine')
            ->waitFor('button[type="submit"]')
            ->scrollIntoView('button[type="submit"]')
            ->pause(1000)
            ->press('Suivant')
            ->waitForText('Etape 3 : Dites-moi en plus sur vous')
            ->assertPathIs('/profil-creation/additional-info');

    });
});

test('that assert an user can complete step 3 of profile création', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->waitFor('#region')
            ->select('#region', 'Japon (JP)')
            ->waitFor('#level',)
            ->select('#level', 'Major league')
            ->waitForText('Bio',)
            ->type('#bio', 'Invaincu.')
            ->scrollIntoView('button[type="submit"]')
            ->waitFor('button[type="submit"]',)
            ->waitForText('Entre dans la faille !',)
            ->pause(400)
            ->click('button[type="submit"]')
            ->waitForText('Karmine')
            ->assertPathIs('/dashboard');
    });
});
