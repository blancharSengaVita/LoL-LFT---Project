<?php

use \App\Models\User;
use \Illuminate\Support\Facades\Auth;
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
    'percent',
    'sections',
    'sectionCompleted',
    'missionCompleted' => true,
]);

mount(function () {
	$this->user = Auth::user();
    $this->percent = 0;
    $this->sectionCompleted = 0;
    $this->renderChange();
});

$renderChange = function () {
    $this->missions = OnboardingMission::where('name', 'addSection')->orderBy('created_at', 'asc')->get();
    $this->sections = $this->user->displayedInformationsOnce()->first()->only(['player_experiences', 'awards', 'skills', 'languages']);
    $this->percent = 0;
    $this->sectionCompleted = 0;
    foreach ($this->sections as $section){
        if ($section === 1){
			//100 deviser par le nombre de mission
            $this->percent += 25;
            $this->sectionCompleted +=1;
        }
    }

    if ($this->percent >= 100){
		$this->missionCompleted = false;
    }
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

$newBio = function(){
    $this->openModal = false;
    $this->dispatch('newBio');
};

on(['renderOnboarding' => function () {
    $this->renderChange();
}]);
?>

<article
    x-data="{
    openAccordion: $wire.entangle('openAccordion'),
    openModal: $wire.entangle('openModal'),
    missionCompleted: $wire.entangle('missionCompleted'),
    {{--openSinglePlayerExperienceModal: $wire.entangle('openSinglePlayerExperienceModal'),--}}
    {{--deleteModal: $wire.entangle('deleteModal'),--}}
    }"
    x-show="missionCompleted"
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
                                <div class="h-2 rounded-full bg-indigo-600" style="width: {{$percent}}%"></div>
                            </div>
                            <p> {{$sectionCompleted}}/{{count($sections)}} </p>
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
                                <div class="h-2 rounded-full bg-indigo-600" style="width: {{$percent}}"></div>
                            </div>
                            <p> 1/{{count($sections)}} </p>
                        </div>


                        <p class="truncate mt-2 text-sm leading-5 text-gray-500">Non complété</p>
                    </div>
                </li>
            @endforeach
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
{{--                            <li class="flex gap-x-4 py-5">--}}
{{--                                <div class="min-w-0">--}}
{{--                                    <button type="button" wire:click="newBio">--}}
{{--                                      <p class="text-left text-sm font-semibold leading-6 text-gray-900">Modifie ta bio</p>--}}
{{--                                        <p class="mt-1 text-sm leading-5 text-gray-500">Donne une description de toi-même, tes intérêts, tes expériences et ce que tu recherches</p>--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </li>--}}
                            <li class="flex gap-x-4 py-5">
                                <div class="min-w-0">
                                    <button type="button" wire:click="newExperience" >
                                        <p class="text-left text-sm font-semibold leading-6 text-gray-900">Ajouter une expérience</p>
                                        <p class="mt-1 text-sm leading-5 text-gray-500">Ça peut aussi bien être une saison en LEC qu'une demi-finale de clash</p>
                                    </button>
                                </div>
                            </li>
                            @if($user->account_type === 'team')
                                <li class="flex gap-x-4 py-5">
                                    <div class="min-w-0">
                                        <button type="button" wire:click="newMember" >
                                            <p class="text-left text-sm font-semibold leading-6 text-gray-900">Ajouter un membre</p>
                                            <p class="mt-1 text-sm leading-5 text-gray-500">Envoyé une demande à une personne pour qu'elle rejoigne votre équipe</p>
                                        </button>
                                    </div>
                                </li>
                            @endif
                            <li class="flex gap-x-4 py-5">
                                <div class="min-w-0">
                                    <button type="button" wire:click="newAward" >
                                        <p class="text-left text-sm font-semibold leading-6 text-gray-900">Ajouter une récompense</p>
                                        <p class="mt-1 truncate text-sm leading-5 text-gray-500">Vous avez gagnez les worlds ou la lan de la région ? Dites-le nous !</p>
                                    </button>
                                </div>
                            </li>
                            @if($user->account_type !== 'team')
                            <li class="flex gap-x-4 py-5">
                                <div class="min-w-0">
                                    <button type="button" wire:click="newSkill">
                                    <p class="text-left text-sm font-semibold leading-6 text-gray-900">Ajouter une compétence</p>
                                    <p class="text-left mt-1 text-sm leading-5 text-gray-500"> Faites nous savoir si vous êtes un bon shotcalleur ou que vous avez un excellent control de la vision, ou encore si vous êtes capable de jouer un grand nombre de champions </p>
                                    </button>
                                </div>
                            </li>
                            @endif
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
</article>
