<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;

beforeEach(function () {
    Artisan::call('migrate:fresh --seed --database=test_db');
});


test('that assert SparklesSupa can login', function () {
    $UwU = User::where('email', 'uwu@gang.gg')->first();
    $this->browse(function (Browser $browser) use ($UwU) {
        $browser->visit('/login')
            ->type('email', $UwU->email)
            ->type('password', 'password')
            ->press('Se connecter')
            ->waitForText($UwU->game_name)
            ->assertPathIs('/dashboard');
    });
});
