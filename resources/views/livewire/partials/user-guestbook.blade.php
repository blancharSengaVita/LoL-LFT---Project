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

<main class="lg:pl-72 h-full">
    <x-slot name="h1">
        {{ $user->game_name }}
    </x-slot>
    <section class="h-full">
        <h2 class="sr-only">
            Recherche de partenaire
        </h2>
        <!--
        This example requires updating your template:

        ```
        <html class="h-full bg-white">

        ```
        -->
        <!-- Static sidebar for desktop -->

        <div class="xl:pr-96 h-full flex flex-col items-stretch">
            {{--            h-full justify-center items-center--}}
            <!-- Main area -->
            <!--
              When the mobile menu is open, add `overflow-hidden` to the `body` element to prevent double scrollbars

              Open: "fixed inset-0 z-40 overflow-y-auto", Closed: ""
            -->
            <livewire:partials.dashboard-header/>
            <livewire:partials.cooming-soon/>
        </div>
    </section>
</main>






