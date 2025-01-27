<?php

use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;

beforeEach(function () {
    Artisan::call('migrate:fresh --seed');
});

test('that assert an user can register', function () {
    $this->browse(function (Browser $browser) {
        $browser
            ->visit('/register')
            ->type('email', 'anchar21111117@gmail.com')
            ->type('password', 'P@ssword1234')
            ->type('password_confirmation', 'P@ssword1234')
            ->press('S\'inscrire')
            ->waitForText('Etape 1 : Quel type de compte voulez-vous créer')
            ->assertPathIs('/profil-creation/account-type')
            ->waitForText('Joueur')
            ->click('@player')
            ->waitFor('@next-button')
            ->press('@next-button')
            ->waitForText('Etape 2 : Pouvez-vous vous présentez ?')
            ->assertPathIs('/profil-creation/general-info');
    });
});
