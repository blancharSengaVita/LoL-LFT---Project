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
            <div class="h-full flex items-center mt-20 flex-col" >
                    <p class="text-xl font-bold text-gray-900 sm:text-2xl">Coming soon</p>
                    <p class="mt-2 text-sm text-gray-900" >Page en construction</p>
            </div>
        </div>
    </section>
</main>






