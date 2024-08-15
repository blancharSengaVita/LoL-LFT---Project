<?php


use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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


mount(function () {
    $this->user = Auth::user();
    $this->displayed_informations = $this->user->displayedInformation()->first();
});
?>

<main class="lg:pl-72">
    <x-slot name="h1">
        {{ $user->game_name }}
    </x-slot>
    <section>
        <h2 class="sr-only">
            Profile
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
            <livewire:partials.dashboard-header/>
            <!-- Hero -->
            <livewire:partials.dashboard-hero/>
            <livewire:partials.dashboard-nav/>
            <livewire:partials.dashboard-onbaording/>
            <livewire:partials.dashboard-bio/>
            <livewire:partials.dashboard-playerexperience/>
            <livewire:partials.dashboard-awards/>
            <livewire:partials.dashboard-education/>
            <livewire:partials.dashboard-skills/>
            <livewire:partials.dashboard-languages/>
        </div>
    </section>
</main>






