<?php

use function Livewire\Volt\{
    state,
    on,
    mount,
    rules,
};
use App\Models\OnboardingMission;

state([
    'user',
    'missions',
    'missionsShow',
    'missionsHidden',
    'openAccordion',
    'openModal',
]);

mount(function () {
    $this->renderChange();
});

$renderChange = function () {
//	dd($this->user->playerExperience);
    $this->missions = OnboardingMission::where('name', 'addSection')->orderBy('created_at', 'asc')->get();
//    foreach ($this->playerExperiences as $experience) {
//        $experience->date = Carbon::parse($experience->date)->locale('fr_FR')->isoFormat('D MMMM YYYY');
//    }

    $this->missionsShow = $this->missions->take(2);
    $this->missionsHidden = $this->missions->skip(2);
};

$openMissionModal = function ($mission){
    $this->$mission();
};

$addSection = function(){
	$this->openModal = true;
};

$newExperience = function(){
    $this->openModal = false;
    $this->dispatch('newExperience');
};
$newAward = function(){
    $this->openModal = false;
    $this->dispatch('newAward');

};
$newSkill = function(){
    $this->openModal = false;
    $this->dispatch('newSkill');

};
$newLanguage = function(){
    $this->openModal = false;
    $this->dispatch('newLanguage');
};


?>

<article
    x-data="{
    openAccordion: $wire.entangle('openAccordion'),
    openModal: $wire.entangle('openModal'),
    {{--openSinglePlayerExperienceModal: $wire.entangle('openSinglePlayerExperienceModal'),--}}
    {{--deleteModal: $wire.entangle('deleteModal'),--}}
    }"
    class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
    <div class="flex justify-between gap-x-4 pb-1 items-center sm:flex-nowrap">
        <h3 class="text-base font-semibold leading-6 text-gray-900">{{'Complétez votre profile !'}}</h3>
        <div class="flex">
            <button wire:click="createSingleExperience"
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
            @foreach($missionsShow as $mission)
                <li class="gap-x-4 py-5 w-full">
                    {{--                    <div class="h-14 w-14 flex justify-center items-center bg-indigo-600">--}}
                    {{--                    <p class="text-3xl text-center text-white">{{$mission['description']}}</p>--}}
                    {{--                    </div>--}}
                    <div class="min-w-0">
                        <p class="text-sm font-semibold leading-6 text-gray-900">{{$mission->title}}</p>
                        <p class="mt-2 text-sm leading-5 text-gray-900">{{$mission->description}}</p>
                        <button wire:click="openMissionModal('{{$mission->name}}')" type="button" class="mt-2 text-sm inline-flex flex-shrink-0 items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text -sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:flex-1">
                            {{$mission->button_title}}
                        </button>
                        <div class="flex gap-x-2 items-center mt-2" aria-hidden="true">
                            <div class="max-h-2 grow-1 w-full overflow-hidden rounded-full bg-gray-200">
                                <div class="h-2 rounded-full bg-indigo-600" style="width: 50.0%"></div>
                            </div>
                            <p> 1/2 </p>
                        </div>


                        <p class="truncate mt-2 text-sm leading-5 text-gray-500">Non complété</p>
                    </div>
                    {{--                    <div class="ml-auto">--}}
                    {{--                        <button wire:click="editSingleExperience({{$experience}})" type="button" class="text-gray-700 group rounded-md p-2 text-sm leading-6 font-semibold ">--}}
                    {{--                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">--}}
                    {{--                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>--}}
                    {{--                            </svg>--}}
                    {{--                        </button>--}}
                    {{--                        <button wire:click="openDeleteModal({{$experience}})" type="button" class="text-gray-700 group rounded-md p-2 text-sm leading-6 font-semibold">--}}
                    {{--                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">--}}
                    {{--                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>--}}
                    {{--                            </svg>--}}
                    {{--                        </button>--}}
                    {{--                    </div>--}}
                </li>
            @endforeach
            @foreach($missionsHidden as $mission)
                <li :class="openAccordion ? '' : 'hidden'" class="gap-x-4 py-5 w-full">
                    {{--                    <div class="h-14 w-14 flex justify-center items-center bg-indigo-600">--}}
                    {{--                    <p class="text-3xl text-center text-white">{{$mission['description']}}</p>--}}
                    {{--                    </div>--}}
                    <div class="min-w-0">
                        <p class="text-sm font-semibold leading-6 text-gray-900">{{$mission->title}}</p>
                        <p class="mt-2 text-sm leading-5 text-gray-900">{{$mission->description}}</p>
                        <button type="button" class="mt-2 text-sm inline-flex flex-shrink-0 items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text -sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:flex-1">
                            {{$mission->button_title}}
                        </button>
                        <div class="flex gap-x-2 items-center mt-2" aria-hidden="true">
                            <div class="max-h-2 grow-1 w-full overflow-hidden rounded-full bg-gray-200">
                                <div class="h-2 rounded-full bg-indigo-600" style="width: 50.0%"></div>
                            </div>
                            <p> 1/2 </p>
                        </div>


                        <p class="truncate mt-2 text-sm leading-5 text-gray-500">Non complété</p>
                    </div>
                    {{--                    <div class="ml-auto">--}}
                    {{--                        <button wire:click="editSingleExperience({{$experience}})" type="button" class="text-gray-700 group rounded-md p-2 text-sm leading-6 font-semibold ">--}}
                    {{--                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">--}}
                    {{--                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>--}}
                    {{--                            </svg>--}}
                    {{--                        </button>--}}
                    {{--                        <button wire:click="openDeleteModal({{$experience}})" type="button" class="text-gray-700 group rounded-md p-2 text-sm leading-6 font-semibold">--}}
                    {{--                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">--}}
                    {{--                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>--}}
                    {{--                            </svg>--}}
                    {{--                        </button>--}}
                    {{--                    </div>--}}
                </li>
            @endforeach
            {{--            @foreach($playerExperiencesHidden as $experience)--}}
            {{--                <li :class="openAccordion ? '' : 'hidden'" class="flex gap-x-4 py-5">--}}
            {{--                    <div class="h-14 w-14 flex justify-center items-center bg-indigo-600">--}}
            {{--                        <p class="text-3xl text-center text-white">{{$experience->placement}}</p>--}}
            {{--                    </div>--}}
            {{--                    <div class="min-w-0">--}}
            {{--                        <p class="text-sm font-semibold leading-6 text-gray-900">{{$experience->event}}</p>--}}
            {{--                        <p class="truncate text-sm leading-5 text-gray-900">{{$experience->team}}--}}
            {{--                            · {{$experience->job}}</p>--}}
            {{--                        <p class="truncate text-sm leading-5 text-gray-500">{{$experience->date }}</p>--}}
            {{--                    </div>--}}
            {{--                    <div class="ml-auto">--}}
            {{--                        <button wire:click="editSingleExperience({{$experience}})" type="button" class="text-gray-700 group rounded-md p-2 text-sm leading-6 font-semibold ">--}}
            {{--                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">--}}
            {{--                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>--}}
            {{--                            </svg>--}}
            {{--                        </button>--}}
            {{--                        <button wire:click="openDeleteModal({{$experience}})" type="button" class="text-gray-700 group rounded-md p-2 text-sm leading-6 font-semibold">--}}
            {{--                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">--}}
            {{--                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>--}}
            {{--                            </svg>--}}
            {{--                        </button>--}}
            {{--                    </div>--}}
            {{--                </li>--}}
            {{--            @endforeach--}}
        </ul>

        {{-- ACCORDEON --}}
                @if(count($this->missionsHidden))
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
    {{--     MODAL SETTINGS DE LA SECTION--}}
    <div x-cloak x-show="openModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                <div @click.away="openModal = false" class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                    <ul>
                        <div class="sm:flex sm:items-start">
                            <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                    Ajouter une section</h3>
                            </div>
                        </div>

                        <ul role="list" class="divide-y divide-gray-100">
                            <li class="flex gap-x-4 py-5">
                                <div class="min-w-0">
                                    <button type="button" wire:click="newExperience" >
                                      <p class="text-left text-sm font-semibold leading-6 text-gray-900">Ajouter une expérience</p>
                                        <p class="mt-1 text-sm leading-5 text-gray-500">Ça peut aussi bien être une saison en LEC qu'une demi-finale de clash</p>
                                    </button>

                                </div>
                            </li>
                            <li class="flex gap-x-4 py-5">
                                <div class="min-w-0">
                                    <button type="button" wire:click="newAward" >
                                        <p class="text-left text-sm font-semibold leading-6 text-gray-900">Ajouter une récompense</p>
                                        <p class="mt-1 truncate text-sm leading-5 text-gray-500">Vous avez gagnez les worlds ou la lan de la région ? Dites-le nous !</p>
                                    </button>
                                </div>
                            </li>
                            <li class="flex gap-x-4 py-5">
                                <div class="min-w-0">
                                    <button type="button" wire:click="newSkill">
                                    <p class="text-left text-sm font-semibold leading-6 text-gray-900">Ajouter une compétence</p>
                                    <p class="text-left mt-1 text-sm leading-5 text-gray-500"> Faites nous savoir si vous êtes un bon shotcalleur ou que vous avez un excellent control de la vision, ou encore si vous êtes capable de jouer un grand nombre de champions </p>
                                    </button>
                                </div>
                            </li>
                            <li class="flex gap-x-4 py-5">
                                <div class="min-w-0">
                                    <button type="button" wire:click="newLanguage">
                                        <p class="text-left text-sm font-semibold leading-6 text-gray-900">Ajouter une langue</p>
                                        <p class="mt-1 text-sm leading-5 text-gray-500"> Agrandissez votre champ de possibilité en montrant quelle langue vous savez parler</p>
                                    </button>
                                </div>
                            </li>
                        </ul>
                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button @click="openModal = false" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500  sm:w-auto">
                                Retour
                            </button>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    {{--     MODAL SETTINGS DE LA SECTION--}}
    {{--    <div x-cloak x-show="openSinglePlayerExperienceModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">--}}
    {{--        <!----}}
    {{--          Background backdrop, show/hide based on modal state.--}}

    {{--          Entering: "ease-out duration-300"--}}
    {{--            From: "opacity-0"--}}
    {{--            To: "opacity-100"--}}
    {{--          Leaving: "ease-in duration-200"--}}
    {{--            From: "opacity-100"--}}
    {{--            To: "opacity-0"--}}
    {{--        -->--}}
    {{--        <div class="fixed inset-0 bg-gray-500 bg-opacity-40 transition-opacity"></div>--}}

    {{--        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">--}}
    {{--            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">--}}
    {{--                <!----}}
    {{--                  Modal panel, show/hide based on modal state.--}}

    {{--                  Entering: "ease-out duration-300"--}}
    {{--                    From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"--}}
    {{--                    To: "opacity-100 translate-y-0 sm:scale-100"--}}
    {{--                  Leaving: "ease-in duration-200"--}}
    {{--                    From: "opacity-100 translate-y-0 sm:scale-100"--}}
    {{--                    To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"--}}
    {{--                -->--}}
    {{--                <div @click.away="openSinglePlayerExperiencesModal = false" class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">--}}
    {{--                    <form action="" wire:submit="saveSingleExperience">--}}
    {{--                        <div class="sm:flex sm:items-start">--}}
    {{--                            <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">--}}
    {{--                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">--}}
    {{--                                    Experience</h3>--}}
    {{--                                <div class="mt-4">--}}
    {{--                                    <label for="event" class="block text-sm font-medium leading-6 text-gray-900">--}}
    {{--                                        Évènement--}}
    {{--                                    </label>--}}
    {{--                                    <div class="mt-2">--}}
    {{--                                        <input wire:model="event" type="text" name="event" id="event" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Clash : coupe d'Europe">--}}
    {{--                                    </div>--}}
    {{--                                    @if ($messages = $errors->get('event'))--}}
    {{--                                        <div class="text-sm text-red-600 space-y-1 mt-2">--}}
    {{--                                            <p>{{$messages[0]}}</p>--}}
    {{--                                        </div>--}}
    {{--                                    @endif--}}
    {{--                                </div>--}}
    {{--                                <div class="mt-4">--}}
    {{--                                    <label for="team" class="block text-sm font-medium leading-6 text-gray-900">--}}
    {{--                                        Équipe--}}
    {{--                                    </label>--}}
    {{--                                    <div class="mt-2">--}}
    {{--                                        <input wire:model="team" type="text" name="team" id="team" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="T1">--}}
    {{--                                    </div>--}}
    {{--                                    @if ($messages = $errors->get('team'))--}}
    {{--                                        <div class="text-sm text-red-600 space-y-1 mt-2">--}}
    {{--                                            <p>{{$messages[0]}}</p>--}}
    {{--                                        </div>--}}
    {{--                                    @endif--}}
    {{--                                </div>--}}
    {{--                                <div class="mt-4">--}}
    {{--                                    <label for="job" class="block text-sm font-medium leading-6 text-gray-900">--}}
    {{--                                        Poste--}}
    {{--                                    </label>--}}
    {{--                                    <div class="mt-2">--}}
    {{--                                        <input wire:model="job" type="text" name="job" id="job" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Jungle">--}}
    {{--                                    </div>--}}
    {{--                                    @if ($messages = $errors->get('job'))--}}
    {{--                                        <div class="text-sm text-red-600 space-y-1 mt-2">--}}
    {{--                                            <p>{{$messages[0]}}</p>--}}
    {{--                                        </div>--}}
    {{--                                    @endif--}}
    {{--                                </div>--}}
    {{--                                <div class="mt-4">--}}
    {{--                                    <label for="placement" class="block text-sm font-medium leading-6 text-gray-900">--}}
    {{--                                        Classement--}}
    {{--                                    </label>--}}
    {{--                                    <div class="mt-2">--}}
    {{--                                        <input wire:model="placement" type="text" name="placement" id="placement" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="1">--}}
    {{--                                    </div>--}}
    {{--                                    @if ($messages = $errors->get('placement'))--}}
    {{--                                        <div class="text-sm text-red-600 space-y-1 mt-2">--}}
    {{--                                            <p>{{$messages[0]}}</p>--}}
    {{--                                        </div>--}}
    {{--                                    @endif--}}
    {{--                                    <p class="mt-3 text-sm leading-6 text-gray-600">Écrivez votre top ou «&nbsp;W&nbsp;» pour--}}
    {{--                                        indiquer une victoire ou «&nbsp;L&nbsp;» pour indiquer une défaite</p>--}}
    {{--                                </div>--}}
    {{--                                <div class="mt-4">--}}
    {{--                                    <label for="date" class="block text-sm font-medium leading-6 text-gray-900">--}}
    {{--                                        Date--}}
    {{--                                    </label>--}}
    {{--                                    <div class="mt-2">--}}
    {{--                                        <input wire:model="date" type="date" name="date" id="date" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="1">--}}
    {{--                                    </div>--}}
    {{--                                    @if ($messages = $errors->get('date'))--}}
    {{--                                        <div class="text-sm text-red-600 space-y-1 mt-2">--}}
    {{--                                            <p>{{$messages[0]}}</p>--}}
    {{--                                        </div>--}}
    {{--                                    @endif--}}
    {{--                                </div>--}}
    {{--                            </div>--}}

    {{--                        </div>--}}
    {{--                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">--}}
    {{--                            <button type="submit" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:ml-3">--}}
    {{--                                sauvegarder--}}
    {{--                            </button>--}}
    {{--                            <button @click="openSinglePlayerExperienceModal = false" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500  sm:w-auto">--}}
    {{--                                Annuler--}}
    {{--                            </button>--}}
    {{--                        </div>--}}
    {{--                    </form>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--    <div x-cloak x-show="deleteModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">--}}
    {{--        <!----}}
    {{--          Background backdrop, show/hide based on modal state.--}}

    {{--          Entering: "ease-out duration-300"--}}
    {{--            From: "opacity-0"--}}
    {{--            To: "opacity-100"--}}
    {{--          Leaving: "ease-in duration-200"--}}
    {{--            From: "opacity-100"--}}
    {{--            To: "opacity-0"--}}
    {{--        -->--}}
    {{--        <div class="fixed inset-0 bg-gray-500 bg-opacity-40 transition-opacity"></div>--}}

    {{--        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">--}}
    {{--            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">--}}
    {{--                <!----}}
    {{--                  Modal panel, show/hide based on modal state.--}}

    {{--                  Entering: "ease-out duration-300"--}}
    {{--                    From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"--}}
    {{--                    To: "opacity-100 translate-y-0 sm:scale-100"--}}
    {{--                  Leaving: "ease-in duration-200"--}}
    {{--                    From: "opacity-100 translate-y-0 sm:scale-100"--}}
    {{--                    To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"--}}
    {{--                -->--}}
    {{--                <div @click.away="deleteModal = false" class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">--}}
    {{--                    <div class="sm:flex sm:items-start">--}}
    {{--                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">--}}
    {{--                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">--}}
    {{--                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>--}}
    {{--                            </svg>--}}
    {{--                        </div>--}}
    {{--                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">--}}
    {{--                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Supprimer une--}}
    {{--                                expérience</h3>--}}
    {{--                            <div class="mt-2">--}}
    {{--                                <p class="text-sm text-gray-500">Êtes-vous sûr de vouloir supprimer cette experience ?--}}
    {{--                                    L'expérience sera définitivement supprimée de nos serveurs. Cette action ne peut--}}
    {{--                                    être annulée.</p>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">--}}
    {{--                        <button wire:click="deleteSingleExperience" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">--}}
    {{--                            Supprimer--}}
    {{--                        </button>--}}
    {{--                        <button wire:click="closeDeleteModal" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">--}}
    {{--                            Annuler--}}
    {{--                        </button>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
</article>
