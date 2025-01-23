<?php

use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Faker\Factory as Faker;

$faker = Faker::create();


beforeEach(function () {
    Artisan::call('migrate:fresh --seed --database=test_db');
});


test('that assert an user can register', function () use ($faker) {
    $this->browse(function (Browser $browser) use ($faker) {
        $browser->visit('/register')
            ->type('email', 'anchar21111117@gmail.com')
            ->type('password', 'P@ssword1234')
            ->type('password_confirmation', 'P@ssword1234')
            ->press('S\'inscrire')
            ->waitForText('Etape 1 : Quel type de compte voulez-vous créer')
            ->assertPathIs('/profil-creation/account-type');
    });
});

//test('that assert that we are in step two of creation profil', function () use ($faker) {
//    $this->browse(function (Browser $browser) use ($faker) {
//        $browser
//            ->waitForText('Joueur')
////            ->click('label[for="player"]')
//            ->click('@player')
//            ->dump()
//            ->pause(1000)
//            ->click('@next-button', 10)  // Wait for up to 10 seconds for the button
//            ->click('@next-button')
//            ->waitForText('Etape 2 : Pouvez-vous vous présentez ?')
//            ->assertPathIs('/profil-creation/general-info');
//    });
//});
