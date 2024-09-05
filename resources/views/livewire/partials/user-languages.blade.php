<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\DisplayedInformation;
use App\Models\DisplayedInformationsOnce;
use App\Models\language;
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
    'languagesShow',
    'languagesHidden',
    'displayed',
    'displayedOnce',
    'showSection',
    'availableLanguages',
]);

$renderChange = function () {
    $this->languages = $this->user->language()->orderBy('created_at', 'desc')->get();
    foreach ($this->languages as $language) {
        $language->date = Carbon::parse($language->date)->locale('fr_FR')->isoFormat('D MMMM YYYY');
    }

    $this->languagesShow = $this->languages->take(2);
    $this->languagesHidden = $this->languages->skip(2);

    if (count($this->languagesShow)){
        $this->showSection = true;
    } else {
        $this->showSection = false;
    }

    $this->displayedOnce = $this->user->displayedInformationsOnce->first()->languages ?? 0;

    $this->displayed = $this->user->displayedInformation->first()->languages ?? 0;
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
    $this->availableLanguages = require __DIR__ . '/../../../../app/enum/languages.php';
});

?>
<article
    x-data="{
openAccordion: $wire.entangle('openAccordion'),
{{--openModal: $wire.entangle('openModal'),--}}
{{--openSingleModal: $wire.entangle('openSingleModal'),--}}
{{--deleteModal: $wire.entangle('deleteModal'),--}}
displayed:$wire.entangle('displayed'),
displayedOnce:$wire.entangle('displayedOnce'),
showSection:$wire.entangle('showSection'),
}">
    <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6"
         x-cloak x-show="displayed && displayedOnce && showSection">
    <div
        class="flex justify-between gap-x-4 pb-1 items-center sm:flex-nowrap">
        <h3 class="text-base font-semibold leading-6 text-gray-900">{{'Langues'}}</h3>

    </div>
    <div class=" sm:w-12/12">
        <ul role="list" class="divide-y divide-gray-100">
            @foreach($languagesShow as $language)
                <li class="flex items-center gap-x-4 py-5 w-full" wire:key="{{ $language->id }}">
                    <div class="h-14 w-14 flex justify-center items-center bg-indigo-600">
                        <p class="text-3xl text-center text-white">{{ ucfirst(array_search($language->name, $availableLanguages)) }}</p>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold leading-6 text-gray-900">{{ __('languages.'.$language->name) }}</p>
                        <p class="truncate text-sm leading-5 text-gray-900">{{ __($language->level)  }}</p>
                    </div>
                </li>
            @endforeach
            @foreach($languagesHidden as $language)
                <li :class="openAccordion ? '' : 'hidden'" class="flex items-center gap-x-4 py-5">
                    <div class="h-14 w-14 flex justify-center items-center bg-indigo-600">
                        <p class="text-3xl text-center text-white">{{ ucfirst(array_search($language->name, $availableLanguages)) }}</p>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold leading-6 text-gray-900">
                            {{ __('languages.'.$language->name) }} </p>
                        <p class="truncate text-sm leading-5 text-gray-900">{{ __($language->level) }}</p>
                    </div>
                </li>
            @endforeach
        </ul>

        {{-- ACCORDEON --}}
        @if(count($this->languagesHidden))
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
