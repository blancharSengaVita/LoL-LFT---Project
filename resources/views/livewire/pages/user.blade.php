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
//	dd($this->user);
    //$this->displayed_informations = $this->user->displayedInformation()->first();
});
?>

<main class="lg:pl-72">
    <x-slot name="h1">
        {{ $user->game_name . 'salut'}}
    </x-slot>
    <section>
        <h2 class="sr-only">
            {{ $user->game_name }}
        </h2>
        <!--
        This example requires updating your template:

        ```
        <html class="h-full bg-white">

        ```
        -->
        <!-- Static sidebar for desktop -->

        <div class="xl:pr-96">

            <!-- Main area -->
            <!--
              When the mobile menu is open, add `overflow-hidden` to the `body` element to prevent double scrollbars

              Open: "fixed inset-0 z-40 overflow-y-auto", Closed: ""
            -->
            <livewire:partials.dashboard-header :user="$user"/>
            <!-- Hero -->
            <livewire:partials.user-hero :user="$user"/>
            <livewire:partials.dashboard-nav/>
            <livewire:partials.user-bio :user="$user"/>
            <livewire:partials.user-playerexperience :user="$user"/>
            <livewire:partials.user-awards :user="$user"/>
            <livewire:partials.user-education :user="$user"/>
            <livewire:partials.user-skills :user="$user"/>
            <livewire:partials.user-languages :user="$user"/>
        </div>
    </section>
</main>
