<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\DisplayedInformationsOnce;
use App\Models\DisplayedInformation;
use App\Models\PlayerExperience;
use Carbon\Carbon;
use Masmerise\Toaster\Toaster;
use App\Models\User;
use function Livewire\Volt\layout;
use function Livewire\Volt\{
    state,
    on,
    mount,
    rules,
};

layout('layouts.dashboard');

state([
    'user',
    'openAccordion',
    'playerExperiences',
    'playerExperiencesShow',
    'playerExperiencesHidden',
    'displayed',
    'displayedOnce',
    'showSection',
]);

$renderChange = function () {
    $this->playerExperiences = $this->user->playerExperience()->orderBy('date', 'desc')->get();
    foreach ($this->playerExperiences as $experience) {
        $experience->date = Carbon::parse($experience->date)->locale('fr_FR')->isoFormat('D MMMM YYYY');
    }

    $this->playerExperiencesShow = $this->playerExperiences->take(2);
    $this->playerExperiencesHidden = $this->playerExperiences->skip(2);

    if (count($this->playerExperiencesShow)){
        $this->showSection = true;
    } else {
        $this->showSection = false;
    }

    $this->displayedOnce = $this->user->displayedInformationsOnce->first()->player_experiences ?? 0;

    $this->displayed = $this->user->displayedInformation->first()->player_experiences ?? 0;
    $this->displayedTemp = $this->displayed;

    if ($this->displayedTemp === 1) {
        $this->displayedTemp = true;
    } else {
        $this->displayedTemp = false;
    }
};

mount(function (User $user) {
    $this->user = $user;

    $this->renderChange();

    $this->openAccordion = false;
});

?>

<article x-data="{
openAccordion: $wire.entangle('openAccordion'),
displayed:$wire.entangle('displayed'),
displayedOnce:$wire.entangle('displayedOnce'),
showSection:$wire.entangle('showSection'),
}">

    <div x-cloak x-show="displayed && displayedOnce && showSection" class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
        <div class="flex justify-between gap-x-4 pb-1 items-center sm:flex-nowrap">
            <h3 class="text-base font-semibold leading-6 text-gray-900">{{'Expérience'}}</h3>

        </div>
        <div class=" sm:w-12/12">
            <ul role="list" class="divide-y divide-gray-100">
                @foreach($playerExperiencesShow as $experience)
                    <li class="flex gap-x-4 py-5 w-full" wire:key="{{ $experience->id }}">
                        <div class="h-14 w-14 flex justify-center items-center bg-indigo-600">
                            <p class="text-3xl text-center text-white">{{$experience->placement}}</p>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{$experience->event}}</p>
                            <p class="truncate text-sm leading-5 text-gray-900">{{$experience->team}}
                                · {{$experience->job}}</p>
                            <p class="truncate text-sm leading-5 text-gray-500">{{$experience->date }}</p>
                        </div>
                    </li>
                @endforeach
                @foreach($playerExperiencesHidden as $experience)
                    <li :class="openAccordion ? '' : 'hidden'" class="flex gap-x-4 py-5">
                        <div class="h-14 w-14 flex justify-center items-center bg-indigo-600">
                            <p class="text-3xl text-center text-white">{{$experience->placement}}</p>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{$experience->event}}</p>
                            <p class="truncate text-sm leading-5 text-gray-900">{{$experience->team}}
                                · {{$experience->job}}</p>
                            <p class="truncate text-sm leading-5 text-gray-500">{{$experience->date }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>

            {{-- ACCORDEON --}}
            @if(count($this->playerExperiencesHidden))
                <div class="flex justify-center">
                    <Bouton @click="openAccordion = !openAccordion">
                        <p :class="openAccordion ? 'hidden' : ''" class="flex items-center text-sm text-gray-800">
                            Afficher
                            plus
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/>
                            </svg>
                        </p>

                        <p :class="openAccordion ? '' : 'hidden'" class="flex items-center text-sm text-gray-800">
                            Afficher
                            moins
                            <svg class=" h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6"/>
                            </svg>
                        </p>
                    </Bouton>
                </div>
            @endif
        </div>
    </div>
</article>
