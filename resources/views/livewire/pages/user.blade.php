<?php

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use function Livewire\Volt\layout;
use function Livewire\Volt\{
    state,
    mount,
};

layout('layouts.dashboard');

state([
    'user',
    'displayed_informations'
]);


mount(function (User $user) {
    $this->user = $user;
});
?>

<main class="lg:pl-72">
    <x-slot name="h1">
        {{  $user->game_name }}
    </x-slot>
    <section>
        <h2 class="sr-only">
            {{ 'Profil' }}
        </h2>
        <div class="xl:pr-96">
            <livewire:partials.dashboard-header/>
            <!-- Hero -->
            <livewire:partials.user-hero :user="$user"/>
            <livewire:partials.user-nav :user="$user"/>
            <livewire:partials.user-bio :user="$user"/>
            <livewire:partials.user-playerexperience :user="$user"/>
            <livewire:partials.user-awards :user="$user"/>
            <livewire:partials.user-education :user="$user"/>
            <livewire:partials.user-skills :user="$user"/>
            <livewire:partials.user-languages :user="$user"/>
        </div>
    </section>
</main>
