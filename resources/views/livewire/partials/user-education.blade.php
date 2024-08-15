<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;
use \App\Models\DisplayedInformation;
use App\Models\DisplayedInformationsOnce;
use \App\Models\Education;
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
    'educationsShow',
    'educationsHidden',
    'displayed',
    'displayedOnce',
    'showSection',
]);

$renderChange = function () {
    $this->educations = $this->user->education()->orderBy('entry_date', 'asc')->get();
    foreach ($this->educations as $education) {
        $education->entry_date = Carbon::parse($education->date)->locale('fr_FR')->isoFormat('D MMMM YYYY');
        $education->exit_date = Carbon::parse($education->date)->locale('fr_FR')->isoFormat('D MMMM YYYY');
    };

    $this->educationsShow = $this->educations->take(2);
    $this->educationsHidden = $this->educations->skip(2);

    if (count($this->educationsShow)){
        $this->showSection = true;
    } else {
        $this->showSection = false;
    }

    $this->displayedOnce = $this->user->displayedInformationsOnce->first()->education ?? 0;

    $this->displayed = $this->user->displayedInformation->first()->education ?? 0;
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
}"
>

    <div x-cloack x-show="displayed && displayedOnce && showSection" class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
        <div class="flex justify-between gap-x-4 pb-1 items-center sm:flex-nowrap">
            <h3 class="text-base font-semibold leading-6 text-gray-900">{{'Formations'}}</h3>
        </div>
        <div class=" sm:w-12/12">
            <ul role="list" class="divide-y divide-gray-100">
                @foreach($educationsShow as $education)
                    <li class="flex gap-x-4 py-5 w-full" wire:key="{{ $education->id }}">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{$education->diploma}}</p>
                            <p class="truncate text-sm leading-5 text-gray-900"> {{$education->establishment}}
                                 {{$education->event}}</p>
                            <p class="truncate text-sm leading-5 text-gray-500">{{'Du ' . $education->entry_date . ' au ' . $education->exit_date}}</p>
                        </div>

                    </li>
                @endforeach
                @foreach($educationsHidden as $education)
                    <li :class="openAccordion ? '' : 'hidden'" class="flex gap-x-4 py-5">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{$education->title}}</p>
                            <p class="truncate text-sm leading-5 text-gray-900"> {{$education->team}}
                                · {{$education->event}}</p>
                            <p class="truncate text-sm leading-5 text-gray-500">{{$education->date }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>

            {{-- ACCORDEON --}}
            @if(count($this->educationsHidden))
                <div class="flex justify-center">
                    <Bouton @click="openAccordion = !openAccordion">
                        <p :class="openAccordion ? 'hidden' : ''" class="flex items-center text-sm text-gray-800">Afficher
                            plus
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/>
                            </svg>
                        </p>

                        <p :class="openAccordion ? '' : 'hidden'" class="flex items-center text-sm text-gray-800">Afficher
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
