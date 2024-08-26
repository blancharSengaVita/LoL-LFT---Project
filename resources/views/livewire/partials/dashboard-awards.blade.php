<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;
use \App\Models\DisplayedInformation;
use App\Models\DisplayedInformationsOnce;
use \App\Models\Award;
use Carbon\Carbon;
use Masmerise\Toaster\Toaster;

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
    'openAwardModal',
    'openSingleAwardModal',
    'openAccordion',
    'singleAward',
    'award',
    'awardsShow',
    'awardsHidden',
    'displayed',
    'displayedTemp',
    'displayedOnce',
    'title',
    'event',
    'team',
    'date',
    'id',
    'deleteModal',
    'award',
    'awards',
]);


rules([
    'title' => 'required|',
    'event' => 'required',
    'team' => 'required',
    'date' => 'required|date'
])->messages([
    'title.required' => 'Le champ est obligatoire.',
    'event.required' => 'Le champ est obligatoire.',
    'team.required' => 'Le champ est obligatoire.',
    'date.required' => 'Le champ est obligatoire.',
])->attributes([

]);

$renderChange = function () {
    $this->awards = $this->user->Award()->orderBy('date', 'desc')->get();
    foreach ($this->awards as $award) {
        $award->date = Carbon::parse($award->date)->locale('fr_FR')->isoFormat('D MMMM YYYY');
    };

    $this->awardsShow = $this->awards->take(2);
    $this->awardsHidden = $this->awards->skip(2);
    $this->displayedOnce = $this->user->displayedInformationsOnce->first()->awards ?? 0;

    $this->displayed = $this->user->displayedInformation->first()->awards ?? 0;
    $this->displayedTemp = $this->displayed;

    if ($this->displayedTemp === 1) {
        $this->displayedTemp = true;
    } else {
        $this->displayedTemp = false;
    }
};

mount(function () {
    $this->user = Auth::user();

    $this->renderChange();



    $this->openAccordion = false;
    $this->openAwardModal = false;
    $this->openSingleAwardModal = false;
    $this->deleteModal = false;

    $this->title = '';
    $this->event = '';
    $this->team = '';
    $this->job = '';
    $this->date = '';
    $this->id = 0;
});


$saveAwardsSettings = function () {
    $this->displayed = $this->displayedTemp;
    DisplayedInformation::where('user_id', Auth::id())->update(['awards' => $this->displayed]);
    $this->openAccordion = false;
    $this->renderChange();
    $this->openAwardModal = false;
    Toaster::success('Modification effectué avec succès');
};

$closeAwardsSettingsModal = function () {
    $this->displayed = $this->user->displayedInformation->first()->awards;
    $this->displayedTemp = $this->displayed;

    if ($this->displayedTemp === 1) {
        $this->displayedTemp = true;
    } else {
        $this->displayedTemp = false;
    }

    $this->renderChange();
    $this->openAwardModal = false;
};

$createSingleAward = function () {
    $this->openSingleAwardModal = true;
    $this->title = '';
    $this->event = '';
    $this->team = '';
    $this->date = '';
    $this->id = 0;
    $this->renderChange();
};

$closeSingleAwardModale = function () {
    $this->openSingleAwardModal = false;
};

$saveSingleAward = function () {
    try {
        $this->validate();
    } catch (\Illuminate\Validation\ValidationException $e) {
        $this->renderChange();
        throw $e;
    }

    Award::updateOrCreate([
        'user_id' => Auth::id(),
        'id' => $this->id
    ],
        [
            'event' => $this->event,
            'title' => $this->title,
            'team' => $this->team,
            'date' => $this->date,
        ]);

    DisplayedInformationsOnce::where('user_id', $this->user->id)
        ->update(['awards' => true]);

    $this->renderChange();
    $this->dispatch('renderOnboarding');
    $this->openSingleAwardModal = false;
    if($this->id === 0){
        Toaster::success('Prix ajouté avec succès');
    }

    if($this->id !== 0){
        Toaster::success('Prix modifiée avec succès');
    }
};

$editSingleAward = function (Award $award) {
    $this->openSingleAwardModal = true;
    $this->event = $award->event;
    $this->title = $award->title;
    $this->team = $award->team;
    $this->date = $award->date;
    $this->id = $award->id;
    $this->renderChange();
};

$deleteSingleAward = function () {
    $this->award->delete();
    $this->deleteModal = false;
    $this->renderChange();
};

$openDeleteModal = function (Award $award) {
    $this->deleteModal = true;
    $this->award = $award;
    $this->renderChange();
};

$closeDeleteModal = function () {
    $this->deleteModal = false;
    $this->renderChange();
};

on(['newAward' => function () {
    $this->createSingleAward();
}]);

on(['render' => function () {
    $this->renderChange();
}]);
?>

<article x-data="{
openAccordion: $wire.entangle('openAccordion'),
openAwardModal: $wire.entangle('openAwardModal'),
openSingleAwardModal: $wire.entangle('openSingleAwardModal'),
deleteModal: $wire.entangle('deleteModal'),
displayed:$wire.entangle('displayed'),
displayedOnce:$wire.entangle('displayedOnce'),
}">

    <div x-cloak x-show="displayed && displayedOnce" class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
        <div class="flex justify-between gap-x-4 pb-1 items-center sm:flex-nowrap">
            <h3 class="text-base font-semibold leading-6 text-gray-900">{{'Récompenses'}}</h3>
            <div class="flex">
                <button wire:click="createSingleAward"
                        type="button" class="text-gray-700 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                </button>
                <button @click="openAwardModal = !openAwardModal" type="button" class="text-gray-700 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class=" sm:w-12/12">
            <ul role="list" class="divide-y divide-gray-100">
                @foreach($awardsShow as $award)
                    <li class="flex gap-x-4 py-5 w-full" wire:key="{{ $award->id }}">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{$award->title}}</p>
                            <p class="truncate text-sm leading-5 text-gray-900"> {{$award->team}}
                                · {{$award->event}}</p>
                            <p class="truncate text-sm leading-5 text-gray-500">{{$award->date }}</p>
                        </div>
                        <div class="ml-auto">
                            <button wire:click="editSingleAward({{$award}})" type="button" class="text-gray-700 group rounded-md p-2 text-sm leading-6 font-semibold ">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                </svg>
                            </button>
                            <button wire:click="openDeleteModal({{$award}})" type="button" class="text-gray-700 group rounded-md p-2 text-sm leading-6 font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                </svg>
                            </button>
                        </div>
                    </li>
                @endforeach
                @foreach($awardsHidden as $award)
                    <li :class="openAccordion ? '' : 'hidden'" class="flex gap-x-4 py-5">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{$award->title}}</p>
                            <p class="truncate text-sm leading-5 text-gray-900"> {{$award->team}}
                                · {{$award->event}}</p>
                            <p class="truncate text-sm leading-5 text-gray-500">{{$award->date }}</p>
                        </div>
                        <div class="ml-auto">
                            <button wire:click="editSingleAward({{$award}})" type="button" class="text-gray-700 group rounded-md p-2 text-sm leading-6 font-semibold ">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                </svg>
                            </button>
                            <button wire:click="openDeleteModal({{$award}})" type="button" class="text-gray-700 group rounded-md p-2 text-sm leading-6 font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                </svg>
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>

            {{-- ACCORDEON --}}
            @if(count($this->awardsHidden))
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

    {{-- MODAL SETTINGS DE LA SECTION  --}}
    <div x-cloak x-show="openAwardModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!--
          Background backdrop, show/hide based on modal state.

          Entering: "ease-out duration-300"
            From: "opacity-0"
            To: "opacity-100"
          Leaving: "ease-in duration-200"
            From: "opacity-100"
            To: "opacity-0"
        -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-40 transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!--
                  Modal panel, show/hide based on modal state.

                  Entering: "ease-out duration-300"
                    From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    To: "opacity-100 translate-y-0 sm:scale-100"
                  Leaving: "ease-in duration-200"
                    From: "opacity-100 translate-y-0 sm:scale-100"
                    To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                -->
                <div @click.away="openAwardModal = false" class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                    <form action="" wire:submit="saveAwardsSettings">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                    award</h3>
                            </div>
                        </div>
                        {{-- modale de confirmation de suppression --}}
                        <div class="mt-5 relative flex items-start">
                            <div class="flex h-6 items-center">
                                <input wire:model="displayedTemp" id="offers" aria-describedby="offers-description" name="offers" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 checked:">
                            </div>
                            <div class="ml-3 text-sm leading-6">
                                <label for="offers" class="font-medium text-gray-900">Afficher cette section au
                                    public</label>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:ml-3">
                                Enregistrer les changements
                            </button>
                            <button wire:click="closeAwardsSettingsModal" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500  sm:w-auto">
                                Annuler
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL SETTINGS DE LA SECTION  --}}
    <div x-cloak x-show="openSingleAwardModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!--
          Background backdrop, show/hide based on modal state.

          Entering: "ease-out duration-300"
            From: "opacity-0"
            To: "opacity-100"
          Leaving: "ease-in duration-200"
            From: "opacity-100"
            To: "opacity-0"
        -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-40 transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!--
                  Modal panel, show/hide based on modal state.

                  Entering: "ease-out duration-300"
                    From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    To: "opacity-100 translate-y-0 sm:scale-100"
                  Leaving: "ease-in duration-200"
                    From: "opacity-100 translate-y-0 sm:scale-100"
                    To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                -->
                <div @click.away="openSingleAwardModal = false" class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                    <form action="" wire:submit="saveSingleAward">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                    Récompense</h3>
                                <div class="mt-4">
                                    <label for="title" class="block text-sm font-medium leading-6 text-gray-900">
                                        Titre
                                    </label>
                                    <div class="mt-2">
                                        <input wire:model="title" type="text" name="title" id="title" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Meilleur joueur d'Europe">
                                    </div>
                                    @if ($messages = $errors->get('title'))
                                        <div class="text-sm text-red-600 space-y-1 mt-2">
                                            <p>{{$messages[0]}}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-4">
                                    <label for="event" class="block text-sm font-medium leading-6 text-gray-900">
                                        Évènement
                                    </label>
                                    <div class="mt-2">
                                        <input wire:model="event" type="text" name="event" id="event" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Clash : coupe d'Europe">
                                    </div>
                                    @if ($messages = $errors->get('event'))
                                        <div class="text-sm text-red-600 space-y-1 mt-2">
                                            <p>{{$messages[0]}}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-4">
                                    <label for="event" class="block text-sm font-medium leading-6 text-gray-900">
                                        Équipe
                                    </label>
                                    <div class="mt-2">
                                        <input wire:model="team" type="text" name="team" id="team" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="T1">
                                    </div>
                                    @if ($messages = $errors->get('team'))
                                        <div class="text-sm text-red-600 space-y-1 mt-2">
                                            <p>{{$messages[0]}}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-4">
                                    <label for="date" class="block text-sm font-medium leading-6 text-gray-900">
                                        Date
                                    </label>
                                    <div class="mt-2">
                                        <input wire:model="date" type="date" name="date" id="date" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="1">
                                    </div>
                                    @if ($messages = $errors->get('date'))
                                        <div class="text-sm text-red-600 space-y-1 mt-2">
                                            <p>{{$messages[0]}}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:ml-3">
                                sauvegarder
                            </button>
                            <button @click="openSingleAwardModal = false" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500  sm:w-auto">
                                Annuler
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div x-cloak x-show="deleteModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!--
          Background backdrop, show/hide based on modal state.

          Entering: "ease-out duration-300"
            From: "opacity-0"
            To: "opacity-100"
          Leaving: "ease-in duration-200"
            From: "opacity-100"
            To: "opacity-0"
        -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-40 transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!--
                  Modal panel, show/hide based on modal state.

                  Entering: "ease-out duration-300"
                    From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    To: "opacity-100 translate-y-0 sm:scale-100"
                  Leaving: "ease-in duration-200"
                    From: "opacity-100 translate-y-0 sm:scale-100"
                    To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                -->
                <div @click.away="deleteModal = false" class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Supprimer une
                                expérience</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Êtes-vous sûr de vouloir supprimer cette award ?
                                    L'expérience sera définitivement supprimée de nos serveurs. Cette action ne peut
                                    être annulée.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button wire:click="deleteSingleAward" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                            Supprimer
                        </button>
                        <button wire:click="closeDeleteModal" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
