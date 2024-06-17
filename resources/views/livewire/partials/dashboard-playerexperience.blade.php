<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;
use \App\Models\DisplayedInformations;
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
	'openPlayerExperiencesModal',
	'openSinglePlayerExperienceModal',
	'openAccordion',
	'singlePlayerExperience',
	'playerExperiences',
	'playerExperiencesShow',
	'playerExperiencesHidden',
	'displayed',
	'event',
	'placement',
	'team',
	'job',
	'date',
    'id'
]);

$renderChange = function (){
    $this->playerExperiences = $this->user->playerExperiences()->orderBy('date', 'desc')->get();
    foreach ($this->playerExperiences as $experience) {
        $experience->date = Carbon::parse($experience->date)->locale('fr_FR')->isoFormat('D MMMM YYYY');
    };

    $this->playerExperiencesShow = $this->playerExperiences->take(2);
    $this->playerExperiencesHidden = $this->playerExperiences->skip(2);
};

mount(function () {
	$this->user = Auth::user();

	$this->renderChange();

	$this->displayed = $this->user->displayedInformations->first()->player_experiences;

	if ($this->displayed === 1) {
		$this->displayed = true;
	} else {
		$this->displayed = false;
	}
	$this->openAccordion = false;
	$this->openPlayerExperiencesModal = false;
	$this->openSinglePlayerExperienceModal = false;



//	if(){
	$this->event = '';
	$this->placement = '';
	$this->team = '';
	$this->job = '';
	$this->date = '2024-05-17 14:40';
	$this->id = 0;
//    }else {

//}
});

$display = function () {

};

$saveExperiencesSettings = function () {
	DisplayedInformations::where('user_id', Auth::id())->update(['player_experiences' => $this->displayed]);
	$this->openAccordion = false;
	$this->openPlayerExperiencesModal = false;
};

$closeExperiencesSettingsModal = function () {
	$this->displayed = $this->user->displayedInformations->first()->player_experiences;

	if ($this->displayed === 1) {
		$this->displayed = true;
	} else {
		$this->displayed = false;
	}

	$this->openPlayerExperiencesModal = false;
};

$saveSingleExperience = function () {
	$this->singlePlayerExperience = $this->user->playerExperiences;

	\App\Models\PlayerExperiences::upsert([
		'user_id' => Auth::id(),
		'event' => $this->event,
		'placement' => $this->placement,
		'team' => $this->team,
		'job' => $this->job,
		'date' => $this->date,
	], ['user_id' => Auth::id(), 'id' => $this->id]);
	$this->openSinglePlayerExperienceModal = false;
    $this->renderChange();
};

$closeSingleExperienceModale = function () {
	$this->openSinglePlayerExperienceModal = false;
};

?>

<article x-data="{
openAccordion: $wire.entangle('openAccordion'),
openPlayerExperiencesModal: $wire.entangle('openPlayerExperiencesModal'),
openSinglePlayerExperienceModal: $wire.entangle('openSinglePlayerExperienceModal'),
}"
         class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
    <div class="flex justify-between gap-x-4 pb-1 items-center sm:flex-nowrap">
        <h3 class="text-base font-semibold leading-6 text-gray-900">{{'Palmarès'}}</h3>
        <div class="flex">
            <button @click="openSinglePlayerExperienceModal = !openSinglePlayerExperienceModal"
                    type="button" class="text-gray-700 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
            </button>
            <button @click="openPlayerExperiencesModal = !openPlayerExperiencesModal" type="button" class="text-gray-700 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                </svg>
            </button>
        </div>
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
                    <button type="button" class="text-gray-700 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold ml-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                        </svg>
                    </button>
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
                    <button type="button" class="text-gray-700 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold ml-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                        </svg>
                    </button>
                </li>
            @endforeach
        </ul>

        {{-- ACCORDEON --}}
        <div class="flex justify-center">
            <Bouton @click="openAccordion = !openAccordion">
                <p :class="openAccordion ? 'hidden' : ''" class="flex items-center text-sm text-gray-800">Afficher plus
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/>
                    </svg>
                </p>

                <p :class="openAccordion ? '' : 'hidden'" class="flex items-center text-sm text-gray-800">Afficher moins
                    <svg class=" h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6"/>
                    </svg>
                </p>
            </Bouton>
        </div>

        {{-- MODAL SETTINGS DE LA SECTION  --}}
        <div x-cloak x-show="openPlayerExperiencesModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                    <div @click.away="openPlayerExperiencesModal = false" class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                        <form action="" wire:submit="saveExperiencesSettings">
                            <div class="sm:flex sm:items-start">
                                <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                                    <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                        Experience</h3>
                                </div>
                            </div>
                            {{-- modale de confirmation de suppression --}}
                            <div class="mt-5 relative flex items-start">
                                <div class="flex h-6 items-center">
                                    <input wire:model="displayed" id="offers" aria-describedby="offers-description" name="offers" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 checked:">
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
                                <button @click="openPlayerExperiencesModal = false" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500  sm:w-auto">
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- MODAL SETTINGS DE LA SECTION  --}}
        <div x-cloak x-show="openSinglePlayerExperienceModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                    <div @click.away="openSinglePlayerExperiencesModal = false" class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                        <form action="" wire:submit="saveSingleExperience">
                            <div class="sm:flex sm:items-start">
                                <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                                    <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                        Experience</h3>
                                    <div class="mt-4">
                                        <label for="event" class="block text-sm font-medium leading-6 text-gray-900">
                                            Évènement
                                        </label>
                                        <div class="mt-2">
                                            <input wire:model="event" type="text" name="event" id="event" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Clash : coupe d'Europe">
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <label for="event" class="block text-sm font-medium leading-6 text-gray-900">
                                            Équipe
                                        </label>
                                        <div class="mt-2">
                                            <input wire:model="team" type="text" name="team" id="team" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="T1">
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <label for="job" class="block text-sm font-medium leading-6 text-gray-900">
                                            Poste
                                        </label>
                                        <div class="mt-2">
                                            <input wire:model="job" type="text" name="job" id="job" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Jungle">
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <label for="event" class="block text-sm font-medium leading-6 text-gray-900">
                                            Classement
                                        </label>
                                        <div class="mt-2">
                                            <input wire:model="placement" type="text" name="placement" id="placement" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="1">
                                        </div>
                                        <p class="mt-3 text-sm leading-6 text-gray-600">Écrivez votre top ou «W» pour indiquer une victoire ou «L» pour indiquer une défaite</p>
                                    </div>
                                    <div class="mt-4">
                                        <label for="date" class="block text-sm font-medium leading-6 text-gray-900">
                                            Date
                                        </label>
                                        <div class="mt-2">
                                            <input wire:model="date" type="date" name="date" id="date" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="1">
                                        </div>
                                    </div>


                                </div>

                            </div>
                            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:ml-3">
                                    sauvegarder
                                </button>
                                <button @click="openSinglePlayerExperienceModal = false" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500  sm:w-auto">
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
