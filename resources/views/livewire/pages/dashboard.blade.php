<?php


use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use function Livewire\Volt\layout;
use function Livewire\Volt\{
    state,
    on,
    mount,
};

layout('layouts.dashboard');


state([
    'user',
    'playerExperiences',
    'playerExperiencesShow',
    'playerExperiencesHidden',
]);


mount(function () {
    $this->user = Auth::user();
    $this->playerExperiences = $this->user->playerExperiences()->get();
    foreach ($this->playerExperiences as $experience) {
        $experience->date = Carbon::parse($experience->date)->locale('fr_FR')->isoFormat('D MMMM YYYY');
    };
    $this->playerExperiencesShow = $this->playerExperiences->take(2);
    $this->playerExperiencesHidden = $this->playerExperiences->skip(2);
});



?>

<main class="lg:pl-72">
        <x-slot name="h1">
            {{ $user->displayed_name }}
        </x-slot>
<section>
    <h2 class="sr-only" >
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
        {{--        <section>--}}

        {{--        </section>--}}
        <!-- Hero -->
        <livewire:partials.dashboard-hero/>
        <!-- Secondary  Nav -->
        <livewire:partials.dashboard-nav/>

        <livewire:partials.dashboard-bio/>
        <livewire:partials.dashboard-playerexperience/>
    </div>
</section>
</main>






