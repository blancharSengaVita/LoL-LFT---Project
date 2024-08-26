<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use \App\Models\Conversation;
use \App\Models\LftPost;
use Masmerise\Toaster\Toaster;

use function Livewire\Volt\layout;
use function Livewire\Volt\{
    state,
    on,
    mount,
    rules,
};

state([
    'openMobileMenu',
    'user',
    'birthday',
    'openModal' => false,
    'profilePictureSource',
    'lftModal',
    'jobs',
    'job' => '',
    'myJob',
    'goals',
    'goal' => '',
    'myGoal',
    'ambiances',
    'ambiance' => '',
    'myAmbiance',
    'levels',
    'level' => '',
    'myLevel',
    'description',
    'published',
    'publishedTemp',
    'myLftPost',
    'id',
]);


mount(function () {
    $this->user = Auth::user();
    $this->openMobileMenu = false;
    $this->birthday = Carbon::parse($this->user->birthday)->locale('fr_FR')->isoFormat('D MMMM YYYY');

    if($this->user->profil_picture){
        $this->profilePictureSource = '/storage/images/1024/'.$this->user->profil_picture;
    }else {
        $this->profilePictureSource =  'https://ui-avatars.com/api/?length=1&name='. $this->user->game_name;
    }

    $this->mobileMenu = false;
    $this->lftModal = false;
    $this->jobs = require __DIR__ . '/../../../../app/enum/jobs.php';
    $this->goals = require __DIR__ . '/../../../../app/enum/lookingFors.php';
    $this->ambiances = require __DIR__ . '/../../../../app/enum/ambiances.php';
    $this->levels = require __DIR__ . '/../../../../app/enum/levels.php';
    $this->user = Auth::user();

    $this->myLftPost = $this->user->lftPost()->first();
//    dd($this->myLftPost);

    $this->id = $this->myLftPost->id ?? 0;
    $this->myJob = $this->myLftPost->job ?? '';
    $this->myAmbiance = $this->myLftPost->ambiance ?? '';
    $this->myGoal = $this->myLftPost->goal ?? '';
    $this->description = $this->myLftPost->description ?? '';
    $this->published = $this->myLftPost->published ?? '';
//    $this->publishedTemp = ;

    if ($this->published === 1) {
        $this->publishedTemp = true;
    } else {
        $this->publishedTemp = false;
    }
});

$openAddSectionModal = function (){
    $this->openModal = true;
};

$addMember = function () {
    $this->openModalMember = true;
};

$addSection = function () {
    $this->openModal = true;
};

$newExperience = function () {
    $this->openModal = false;
    try {
        \App\Models\DisplayedInformation::where('user_id', $this->user->id)
            ->update(['player_experiences' => true]);
		\App\Models\DisplayedInformationsOnce::where('user_id', $this->user->id)
            ->update(['player_experiences' => true]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        throw $e;
    }

    $this->dispatch('render')->to('partials.dashboard-playerexperience');
    Toaster::success('Section affiché');
};

$newEducation = function () {
    $this->openModal = false;
    try {
        \App\Models\DisplayedInformation::where('user_id', $this->user->id)
            ->update(['education' => true]);
        \App\Models\DisplayedInformationsOnce::where('user_id', $this->user->id)
            ->update(['education' => true]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        throw $e;
    }

    $this->dispatch('render')->to('partials.dashboard-education');
    Toaster::success('Section affiché');
};

$newAward = function () {
    $this->openModal = false;
    try {
        \App\Models\DisplayedInformation::where('user_id', $this->user->id)
            ->update(['awards' => true]);
        \App\Models\DisplayedInformationsOnce::where('user_id', $this->user->id)
            ->update(['awards' => true]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        throw $e;
    }

    $this->dispatch('render')->to('partials.dashboard-awards');
    Toaster::success('Section affiché');
};

$newSkill = function () {
    $this->openModal = false;
    try {
        \App\Models\DisplayedInformation::where('user_id', $this->user->id)
            ->update(['skills' => true]);
        \App\Models\DisplayedInformationsOnce::where('user_id', $this->user->id)
            ->update(['skills' => true]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        throw $e;
    }

    $this->dispatch('render')->to('partials.dashboard-skills');
    Toaster::success('Section affiché');
};

$newLanguage = function () {
    $this->openModal = false;
    try {
        \App\Models\DisplayedInformation::where('user_id', $this->user->id)
            ->update(['languages' => true]);
        \App\Models\DisplayedInformationsOnce::where('user_id', $this->user->id)
            ->update(['languages' => true]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        throw $e;
    }

    $this->dispatch('render')->to('partials.dashboard-languages');
    Toaster::success('Section affiché');
};

$openLFTModal = function () {
    $this->publishedTemp = $this->published;

    if ($this->publishedTemp === 1) {
        $this->publishedTemp = true;
    } else {
        $this->publishedTemp = false;
    }
    $this->lftModal = true;
};

$closeLFTModal = function () {
    $this->published = $this->myLftPost->published ?? '';

    $this->publishedTemp = $this->published;

    if ($this->publishedTemp === 1) {
        $this->publishedTemp = true;
    } else {
        $this->publishedTemp = false;
    }
    $this->lftModal = false;
};

$saveMyLftPost = function () {
//    try {
//        $this->validate();
//    } catch (\Illuminate\Validation\ValidationException $e) {
//        throw $e;
//    }

    $this->published = $this->publishedTemp ;

    LftPost::updateOrCreate([
        'user_id' => Auth::id(),
        'id' => $this->id
    ],
        [
            'job' => $this->myJob,
            'ambiance' => $this->myAmbiance,
            'goal' => $this->myGoal,
            'description' => $this->description,
            'published' => $this->published,
        ]);

    $this->lftModal = false;

    if($this->id === 0){
        Toaster::success('Post LFT crée avec succès');
    }

    if($this->id !== 0){
        Toaster::success('Post LFT modifiée avec succès');
    }

};

?>

<div class="divide-y divide-gray-200 border-b"
     x-data="{
        openDropdownMenu: false,
        openModal: $wire.entangle('openModal'),
        lftModal: $wire.entangle('lftModal'),
         }"
>
    <div class="pb-6 bg-white">
        <div class="h-24 bg-indigo-700 sm:h-20 lg:h-28"></div>
        <div class="-mt-8 flow-root px-4 sm:-mt-4 sm:flex sm:items-end sm:px-6 lg:-mt-8">
            <div>
                <div class="-m-1 flex">
                    <div class="inline-flex overflow-hidden rounded-lg border-4 border-white">
                        <img class="h-24 w-24 flex-shrink-0 sm:h-40 sm:w-40 lg:h-48 lg:w-48" src="{{ $profilePictureSource }}" alt="">
                    </div>
                </div>
            </div>
            <div class="mt-4 sm:mt-6 sm:ml-6 sm:flex-1">
                <div>
                    <p class="text-xl font-bold text-gray-900 sm:text-2xl">{{ $user->game_name }}
                </div>
                <div class="mb-2">
                    <span class="text-sm text-gray-500">{{ $user->username }}</span>
                </div>
                <div>
                    <p class="text-gray-900">{{ $user->job }}  · {{ __('levels.'.$user->level) }}</p>
                </div>

                <div class="mt-5 flex flex-wrap space-y-3 sm:space-x-3 sm:space-y-0">
                    {{--                    <button type="button" class="inline-flex w-full flex-shrink-0 items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:flex-1">--}}
                    {{--                        Invitation LFT--}}
                    {{--                    </button>--}}
                    {{--                    <button type="button" class="inline-flex w-full flex-1 items-center justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">--}}
                    {{--                        Message--}}
                    {{--                    </button>--}}
                    {{--                    Envoyer une demande d'ami--}}
                    <button wire:click="openAddSectionModal" type="button" class="inline-flex w-full flex-shrink-0 items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:flex-1">
                        Ajouter une section
                    </button>
                    <button wire:click="openLFTModal" type="button" class="inline-flex w-full flex-1 items-center justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        Mon post LFT
                    </button>
                    <div class="ml-3 inline-flex sm:ml-0">
                        <div class="relative inline-block text-left">
                            <button x-cloak @click="openDropdownMenu = !openDropdownMenu" type="button" class="relative inline-flex items-center rounded-md bg-white p-2 text-gray-400 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50" id="options-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="absolute -inset-1"></span>
                                <span class="sr-only">Open options menu</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"/>
                                </svg>
                            </button>
                            <!--
                              Dropdown panel, show/hide based on dropdown state.

                              Entering: "transition ease-out duration-100"
                                From: "transform opacity-0 scale-95"
                                To: "transform opacity-100 scale-100"
                              Leaving: "transition ease-in duration-75"
                                From: "transform opacity-100 scale-100"
                                To: "transform opacity-0 scale-95"
                            -->
                            <div x-cloak x-show="openDropdownMenu" @click.away="openDropdownMenu = false" class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="options-menu-button" tabindex="-1">
                                <div class="py-1" role="none">
                                    <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                                    <a href="#" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="options-menu-item-0">Ajouter un CV sur le profil</a>
                                    {{--                                    <a href="#" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="options-menu-item-0">Voir--}}
                                    {{--                                        CV</a>--}}
                                    {{--                                    <a href="#" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="options-menu-item-0">Demande--}}
                                    {{--                                        d'ami</a>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex px-4 sm:px-6 mt-4">
            <p class="flex items-center mr-2 text-sm text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-0.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                </svg>
                {{ $user->region  }}
            </p>

            <p class="flex items-center text-sm text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-0.5 w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.871c1.355 0 2.697.056 4.024.166C17.155 8.51 18 9.473 18 10.608v2.513M15 8.25v-1.5m-6 1.5v-1.5m12 9.75-1.5.75a3.354 3.354 0 0 1-3 0 3.354 3.354 0 0 0-3 0 3.354 3.354 0 0 1-3 0 3.354 3.354 0 0 0-3 0 3.354 3.354 0 0 1-3 0L3 16.5m15-3.379a48.474 48.474 0 0 0-6-.371c-2.032 0-4.034.126-6 .371m12 0c.39.049.777.102 1.163.16 1.07.16 1.837 1.094 1.837 2.175v5.169c0 .621-.504 1.125-1.125 1.125H4.125A1.125 1.125 0 0 1 3 20.625v-5.17c0-1.08.768-2.014 1.837-2.174A47.78 47.78 0 0 1 6 13.12M12.265 3.11a.375.375 0 1 1-.53 0L12 2.845l.265.265Zm-3 0a.375.375 0 1 1-.53 0L9 2.845l.265.265Zm6 0a.375.375 0 1 1-.53 0L15 2.845l.265.265Z"/>
                </svg>
                {{ $birthday }}
            </p>
        </div>
    </div>
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
    <div x-cloak x-show="lftModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                <div @click.away="lftModal = false" class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                    <form action="" wire:submit="saveMyLftPost">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                    Mon post LFT</h3>
                                <div class="flex gap-x-4">
                                    <div class="mt-4">
                                        <label for="myJob" class="block text-sm font-medium leading-6 text-gray-900">Recherche
                                            un/une</label>
                                        <select wire:model.live="myJob" id="myJob" name="myJob" class="w-10 mt-2 block w-32 rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            <option value="">Peu importe</option>
                                            <optgroup label="JOUEUR">
                                                @foreach($jobs['player'] as $job)
                                                    <option value="{{ $job }}">{{ __('jobs.'.$job) }}</option>
                                                @endforeach
                                            </optgroup>
                                            <optgroup label="STAFF">
                                                @foreach($jobs['staff'] as $job)
                                                    <option value="{{ $job }}">{{ __('jobs.'.$job) }}</option>
                                                @endforeach
                                            </optgroup>
                                            <optgroup label="ÉQUIPE">
                                                @foreach($jobs['team'] as $job)
                                                    <option value="{{ $job }}">{{ __('jobs.'.$job) }}</option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                        @if ($messages = $errors->get('myJob'))
                                            <div class="text-sm text-red-600 space-y-1 mt-2">
                                                <p>{{$messages[0]}}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mt-4">
                                        <label for="myGoal" class="block text-sm font-medium leading-6 text-gray-900">Pour</label>
                                        <select wire:model.live="myGoal" id="myGoal" name="myGoal" class="w-10 mt-2 block w-32 rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            <option value="">Peu importe</option>
                                            @foreach($goals['team'] as $goal)
                                                <option value="{{ $goal }}">{{ __('lookingFors.'.$goal) }}</option>
                                            @endforeach
                                            @foreach($goals['duo'] as $goal)
                                                <option value="{{ $goal }}">{{ __('lookingFors.'.$goal) }}</option>
                                            @endforeach
                                        </select>
                                        @if ($messages = $errors->get('myGoal'))
                                            <div class="text-sm text-red-600 space-y-1 mt-2">
                                                <p>{{$messages[0]}}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mt-4">
                                        <label for="myAmbiance" class="block text-sm font-medium leading-6 text-gray-900">Ambiance</label>
                                        <select wire:model.live="myAmbiance" id="myAmbiance" name="myAmbiance" class="w-10 mt-2 block w-32 rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            <option value="">Peu importe</option>
                                            @foreach($ambiances as $ambiance)
                                                <option value="{{ $ambiance }}">{{ __('ambiances.'.$ambiance) }}</option>
                                            @endforeach
                                        </select>
                                        @if ($messages = $errors->get('myAmbiance'))
                                            <div class="text-sm text-red-600 space-y-1 mt-2">
                                                <p>{{$messages[0]}}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                                    <div class="mt-2">
                                        <textarea wire:model="description" id="description" name="description" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                    </div>
                                    @error('description')
                                    <p class="text-sm text-red-600 space-y-1 mt-2"> {{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm leading-6 text-gray-600">Écris quelques phrases à
                                        propos de ton post</p>
                                </div>
                                <div class="mt-5 relative flex items-start">
                                    <div class="flex h-6 items-center">
                                        <input wire:model="publishedTemp" id="displayed" aria-describedby="offers-description" name="offers" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 checked:">
                                    </div>
                                    <div class="ml-3 text-sm leading-6">
                                        <label for="displayed" class="font-medium text-gray-900">Publier mon post</label>
                                    </div>
                                </div>
                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                    <button type="submit" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:ml-3">
                                        sauvegarder
                                    </button>
                                    <button @click="lftModal = false" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500  sm:w-auto">
                                        Annuler
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
