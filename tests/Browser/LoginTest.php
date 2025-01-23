<?php
use Laravel\Dusk\Browser;
use App\Models\User;

test('that assert that we can see LoL LFT on homepage', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/home')
            ->assertSee('LoL LFT');
    });
});

test('that assert SparklesSupa can login', function () {
    $sparklesSupa = User::where('email', 'anchar2107@gmail.com')->first();
    $this->browse(function (Browser $browser) use ($sparklesSupa) {
        $browser->visit('/login')
            ->type('email', $sparklesSupa->email)
            ->type('password', 'password')
            ->press('Se connecter')
            ->waitForText($sparklesSupa->game_name)
            ->assertPathIs('/dashboard');
    });
});

