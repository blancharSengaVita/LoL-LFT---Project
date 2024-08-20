<?php

use \App\Models\User;
use \Illuminate\Support\Facades\Auth;
use \App\Models\UserMission;
use App\Models\OnboardingMission;
use Masmerise\Toaster\Toaster;
use \App\Models\DisplayedInformation;

use function Livewire\Volt\{
    state,
    on,
    mount,
    rules,
};


state([
    'user',
    'missions',
    'missionsShow',
    'missionsHidden',
    'openAccordion',
    'openModal',
    'openModalMember',
    'percent',
    'sections',
    'sectionCompletion',
    'percentMission',
    'missionsCompleted',
    'displayed',
]);

mount(function () {
    $this->user = Auth::user();
    $this->percent = 0;
    $this->sectionCompletion = 0;
    $this->renderChange();
    $this->displayed = DisplayedInformation::where('user_id', $this->user->id)->get()->first()->onboarding;

    if ($this->displayed === 1) {
        $this->displayed = true;
    } else {
        $this->displayed = false;
    }
});

$renderChange = function () {
    $id = $this->user->id;

    $query = OnboardingMission::wherehas('userMission', function ($q) use ($id) {
        $q
            ->where('user_id', $id)
            ->where('finished', false);
    });

    $this->missions = $query->get();

    if ($this->user->account_type === 'staff') {
        $this->sections = $this->user->displayedInformationsOnce()->first()->only(['player_experiences', 'awards', 'skills', 'languages', 'education']);
    }

    if ($this->user->account_type === 'player') {
        $this->sections = $this->user->displayedInformationsOnce()->first()->only(['player_experiences', 'awards', 'skills', 'languages']);
    }

    if ($this->user->account_type === 'team') {
        $this->sections = $this->user->displayedInformationsOnce()->first()->only(['player_experiences', 'awards', 'languages']);
    }

    $this->percentMission = 0;

    foreach ($this->missions as $mission) {
        $mission['percent'] = 0;
        $mission['sectionCompletion'] = 0;
        $mission['completed'] = false;
        $mission['completion'] = 'Non complétée';

        if ($mission->name === 'addSection') {

            $mission['number'] = count($this->sections);
            foreach ($this->sections as $section) {
                if ($section === 1) {
                    $mission['percent'] += 100 / count($this->sections);
                    $mission['sectionCompletion'] += 1;
                }
            };
        }

        if ($mission->name === 'addMember') {
            $isThereAtLeastOnePlayer = $this->user->players()->first();
            $mission['number'] = 1;
            if ($isThereAtLeastOnePlayer) {
                $mission['percent'] += 100;
                $mission['sectionCompletion'] += 1;
            }
        }

        if ($mission['percent'] >= 99) {
            $this->percentMission += 1;
            $mission['completed'] = true;
            $mission['completion'] = 'Complétée';
        }

        if ($this->percentMission >= count($this->missions)) {
//            $this->missionsCompleted = false;
        }
    }

    $this->missionsShow = $this->missions->take(2);
    $this->missionsHidden = $this->missions->skip(2);
};


$openMissionModal = function ($mission) {
    $this->$mission();
    $this->renderChange();
};

$stopDisplay = function () {
    $model = $this->displayed = DisplayedInformation::where('user_id', $this->user->id)->get()->first();
    $model->onboarding = false;
    $model->save();
};

$validateMission = function ($id) {
    $mission = UserMission::where('user_id', $this->user->id)
        ->where('mission_id', $id)->get()->first();
    $mission->finished = true;
    $mission->save();
    Toaster::success('Mission effectué avec succès');
    $this->renderChange();
};

$addMember = function () {
    $this->openModalMember = true;
};

$addSection = function () {
    $this->openModal = true;
};

$newEducation = function () {
    $this->openModal = false;
    $this->dispatch('newEducation');
    $this->renderChange();
};

$newExperience = function () {
    $this->openModal = false;
    $this->dispatch('newExperience');
    $this->renderChange();
};

$newAward = function () {
    $this->openModal = false;
    $this->dispatch('newAward');
    $this->renderChange();
};

$newSkill = function () {
    $this->openModal = false;
    $this->dispatch('newSkill');
    $this->renderChange();
};

$newLanguage = function () {
    $this->openModal = false;
    $this->dispatch('newLanguage');
    $this->renderChange();
};

$newBio = function () {
    $this->openModal = false;
    $this->dispatch('newBio');
    $this->renderChange();
};

on(['openAddSectionModal' => function () {
    $this->openModal = true;
    $this->renderChange();
}]);

on(['renderOnboarding' => function () {
    $this->renderChange();
}]);
?>

<article
    x-data="{
    openAccordion: $wire.entangle('openAccordion'),
    openModal: $wire.entangle('openModal'),
    openModalMember: $wire.entangle('openModalMember'),
    missionsCompleted: $wire.entangle('missionsCompleted'),
    displayed: $wire.entangle('displayed'),
    {{--openSinglePlayerExperienceModal: $wire.entangle('openSinglePlayerExperienceModal'),--}}
    {{--deleteModal: $wire.entangle('deleteModal'),--}}
    }"
    >
    <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6" x-cloak x-show="displayed">
        <div class="flex justify-between gap-x-4 pb-1 items-center sm:flex-nowrap">
            <h3 class="text-base font-semibold leading-6 text-gray-900">{{'Complétez votre profile !'}}</h3>
            <div class="flex">
                {{--            <button wire:click="createSingleExperience"--}}
                {{--                    type="button" class="text-gray-700 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">--}}
                {{--                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">--}}
                {{--                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>--}}
                {{--                </svg>--}}
                {{--            </button>--}}
                {{--            <button @click="openPlayerExperiencesModal = !openPlayerExperiencesModal" type="button" class="text-gray-700 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">--}}
                {{--                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">--}}
                {{--                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>--}}
                {{--                </svg>--}}
                {{--            </button>--}}
            </div>
        </div>
        <div class=" sm:w-12/12">
            <ul role="list" class="divide-y divide-gray-100">
                @if(!count($missionsShow))
                    <p class="mb-2">Toutes vos missions ont été accompli !</p>
                    <button wire:click="stopDisplay" type="button" class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500  sm:w-auto">
                        Archiver cette section
                    </button>
                @endif
                @foreach($missionsShow as $mission)
                    <li
                        class="gap-x-4 py-5 w-full">
                        {{--                    <div class="h-14 w-14 flex justify-center items-center bg-indigo-600">--}}
                        {{--                    <p class="text-3xl text-center text-white">{{$mission['description']}}</p>--}}
                        {{--                    </div>--}}
                        <div class="min-w-0">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{$mission->title}}</p>
                            <p class="mt-2 text-sm leading-5 text-gray-900">{{$mission->description}}</p>
                            @if($mission->completed)
                                <button wire:click="validateMission('{{$mission->id}}')" type="button" class="mt-2 text-sm inline-flex flex-shrink-0 items-center justify-center rounded-md bg-green-600 px-3 py-2 text -sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:flex-1">
                                    {{'Valider la mission'}}
                                </button>
                            @else
                                <button wire:click="openMissionModal('{{$mission->name}}')" type="button" class="mt-2 text-sm inline-flex flex-shrink-0 items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text -sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:flex-1">
                                    {{$mission->button_title}}
                                </button>
                            @endif
                            <div class="flex gap-x-2 items-center mt-2" aria-hidden="true">
                                <div class="max-h-2 grow-1 w-full overflow-hidden rounded-full bg-gray-200">
                                    <div @class([ "h-2 rounded-full", 'bg-green-600' => $mission->completed,'bg-indigo-600' =>!$mission->completed,])
                                         style="width: {{$mission->percent}}%"></div>
                                </div>
                                <p> {{$mission->sectionCompletion}}/{{$mission->number}} </p>
                            </div>
                            <p class="truncate mt-2 text-sm leading-5 text-gray-500">{{ $mission->completion }}</p>
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
                        </div>
                    </li>
                @endforeach
            </ul>

            {{-- ACCORDEON --}}
            @if(count($this->missionsHidden))
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
    {{--     MODAL pour ajouter des sections --}}
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
                                    <button type="button" wire:click="newExperience">
                                        <p class="text-left text-sm font-semibold leading-6 text-gray-900">Ajouter une
                                            expérience</p>
                                        <p class="mt-1 text-sm leading-5 text-gray-500">Ça peut aussi bien être une
                                            saison en LEC qu'une demi-finale de clash</p>
                                    </button>
                                </div>
                            </li>
                            <li class="flex gap-x-4 py-5">
                                <div class="min-w-0">
                                    <button type="button" wire:click="newAward">
                                        <p class="text-left text-sm font-semibold leading-6 text-gray-900">Ajouter une
                                            récompense</p>
                                        <p class="mt-1 truncate text-sm leading-5 text-gray-500">Vous avez gagnez les
                                            worlds ou la lan de la région ? Dites-le nous !</p>
                                    </button>
                                </div>
                            </li>
                            @if($user->account_type !== 'team')
                                <li class="flex gap-x-4 py-5">
                                    <div class="min-w-0">
                                        <button type="button" wire:click="newSkill">
                                            <p class="text-left text-sm font-semibold leading-6 text-gray-900">Ajouter
                                                une compétence</p>
                                            <p class="text-left mt-1 text-sm leading-5 text-gray-500"> Faites nous
                                                savoir si vous êtes un bon shotcalleur ou que vous avez un excellent
                                                control de la vision, ou encore si vous êtes capable de jouer un grand
                                                nombre de champions </p>
                                        </button>
                                    </div>
                                </li>
                            @endif
                            @if($user->account_type === 'staff')
                                <li class="flex gap-x-4 py-5">
                                    <div class="min-w-0">
                                        <button type="button" wire:click="newEducation">
                                            <p class="text-left text-sm font-semibold leading-6 text-gray-900">Ajouter
                                                une formation</p>
                                            <p class="text-left mt-1 text-sm leading-5 text-gray-500">
                                                Faites savoir à tous quelles formations vous avez suivies et comment
                                                elles ont enrichi votre parcours. Partagez vos atouts et démontrez ce
                                                qui vous rend exceptionnel !
                                            </p>
                                        </button>
                                    </div>
                                </li>
                            @endif
                            <li class="flex gap-x-4 py-5">
                                <div class="min-w-0">
                                    <button type="button" wire:click="newLanguage">
                                        <p class="text-left text-sm font-semibold leading-6 text-gray-900">Ajouter une
                                            langue</p>
                                        <p class="mt-1 text-sm leading-5 text-gray-500"> Agrandissez votre champ de
                                            possibilité en montrant quelle langue vous savez parler</p>
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
    {{--     MODAL pour ajouter un membre --}}
    <div x-cloak x-show="openModalMember" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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

                <div @click.away="openModalMember = false" class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                    <div>
                        <p class="text-base font-semibold leading-6 text-gray-900">Pour ajouter un membre, vous devez
                            :</p>
                        <dl>
                            <dt class="text-gray-700 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                1. Aller sur l'onglet membre
                            </dt>
                            <dd class="mb-2">
                                <img class="border-indigo-600 border-2 h-20" src="{{ Vite::asset('resources/images/member1.png') }}" alt="Onglet Membre">
                            </dd>

                            <dt class="text-gray-700 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                2. cliquer sur le "+" en choissisant dans la liste des joueurs ou staff en fonction de
                                ce que vous voulez ajouter
                            </dt>
                            <dd class="mb-2">
                                <img class="border-indigo-600 border-2 h-20" src="{{ Vite::asset('resources/images/member2.png') }}" alt="Section A">
                            </dd>

                            <dt class="text-gray-700 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                3. remplir le formulaire
                            </dt>
                            <dd class="mb-2"></dd>
                        </dl>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-x-2">
                        <a href="{{ route('members') }}" class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500  sm:w-auto">
                            Aller sur l'onglet membre
                        </a>
                        <button @click="openModalMember = false" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500  sm:w-auto">
                            Retour
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
