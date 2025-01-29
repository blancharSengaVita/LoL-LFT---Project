<?php

use Laravel\Dusk\Browser;

test('that assert an user can register', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->visit('/register')
            ->type('email', 'karminecorp@gmail.com')
            ->type('password', 'P@ssword1234')
            ->press('S\'inscrire')
            ->waitForText('Etape 1 : Quel type de compte voulez-vous créer')
            ->assertPathIs('/profil-creation/account-type');
    });
});


test('that assert an user can complete step 1 of profile création', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->waitForText('Équipe')
            ->press('@team')
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

test('that assert we make the "add experience" mission of onboarding', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->waitForText('Karmine')
            ->scrollIntoView('@addMember')
            ->press('Ajouter un membre')
            ->waitForText('Pour ajouter un membre, vous devez')
            ->press('@GoToMembers')
            ->waitForText('Joueurs')
            ->assertPathIs('/members');
    });
});

test('that we can add players', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->press('@addPlayers')
            ->waitForText('Ajouter un joueur manuellement')
            ->type('@memberPlayerUserame', 'gingembre poilu')
            ->select('@memberPlayerNationality', 'Australian')
            ->select('@memberPlayerJob', 'ADC')
            ->keys('@memberPlayerEntry_date', '10022024',)
            ->press('Sauvegarder')
            ->waitForText('Joueurs ajouté avec succès')
            ->waitForText('gingembre poilu');
    });
});

test('that we can Make my lft post', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->click('@findTeammateSideMenu')
            ->waitForText('Mon post LFT')
            ->assertPathIs('/find-teammate')
            ->press('Mon post LFT')
            ->waitForText('Écris quelques phrases à propos de ton post')
            ->select('@MyPostAmbiance', 'Fun')
            ->select('@MyPostJob', 'ADC')
            ->select('@MyPostGoal', 'Ranked')
            ->type('@MyPostDescription', 'Gagner en s\'amusant')
            ->check('@MyPostPublication')
            ->press('Sauvegarder')
            ->waitFor('@FindTeammateAmbiance')
            ->select('@FindTeammateAmbiance','Fun')
            ->waitFor('@FindTeammateGoal')
            ->select('@FindTeammateGoal','Ranked')
            ->scrollIntoView('@MyPost-@Karmine')
            ->pause(2000)
            ->assertSee('Gagner en s\'amusant')
            ->pause(2000)
            ->screenshot('end')
        ;
    });
});
