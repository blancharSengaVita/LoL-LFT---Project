<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use App\Rules\StartsWithAt;
use Illuminate\Validation\Rule;
use \App\Models\UserMission;
use \App\Models\OnboardingMission;
use \Carbon\Carbon;
use function Livewire\Volt\{state, rules, layout, mount, updated, computed};

layout('layouts.auth');

state([
    'user',
    'game_name',
    'username',
    'birthday',
    'nationalities',
    'nationality',
    'type',
    'pseudoInput' => '',
    'pseudoDescription' => '',
    'gameNameInput' => '',
    'gameNameDescription' => '',
]);

mount(function () {
    $this->user = Auth::user();
    $this->nationalities = require __DIR__ . '/../../../../../app/enum/nationalities.php';
    $this->game_name = $this->user->game_name ?? '';
    $this->username = $this->user->username ?? '@';
    $this->birthday = $this->user->birthday ?? '';
    $this->nationality = $this->user->nationality ?? '';
    $this->type = $this->user->account_type ?? '';

    if ($this->type === 'team') {
        $this->pseudoInput = 'Nom d\'équipe';
        $this->pseudoDescription = 'Mettez sera votre pseudo affiché';
        $this->gameNameInput = 'Nom du compte';
        $this->gameNameDescription = 'Celui-ci sera Le nom de votre compte';

    } else {
        $this->pseudoInput = 'Pseudo';
        $this->pseudoDescription = 'Celui-ci sera le nom d\'équipe affiché';
        $this->gameNameInput = 'Nom d\'utilisateur';
        $this->gameNameDescription = 'Celui-ci sera Le nom de votre compte';
    }

//	dd(now()->timestamp());
});

updated(['game_name' => function () {
    $this->username = '@' . $this->game_name;
}]);

$usernameStartWithAt = computed(function () {
    if (str_starts_with($this->username, '@')) {
        return true;
    } else {
        return false;
    }
});

$usernameIsTaken = computed(function () {
	if ($this->username === $this->user->username){
		return false;
    }
    return User::where('username', $this->username)->exists();
});

$dateIsBeforeOrEgale = computed(function () {
    return $this->date < Carbon::now();
});

rules([
    'game_name' => 'required|string|max:20',
    'username' => ['required', 'string', Rule::unique('users')->ignore(Auth::user()->id), new StartsWithAt, 'max:20'],
    'nationality' => Auth::user()->account_type !== 'team' ? 'required' : 'nullable',
    'birthday' => Auth::user()->account_type !== 'team' ? 'required|date|before_or_equal:today' : 'nullable',
])->messages([
    'nationality.required' => 'Votre nationalité est requis',
    'game_name.required' => 'Votre pseudo est requis',
    'game_name.string' => 'Votre pseudo doit être composé de lettres',
    'game_name.max' => 'Votre pseudo doit avoir maximum 20 lettres',
    'username.required' => 'Votre nom d\'utilisateur est requis',
    'username.string' => 'Votre nom d\'utilisateur doit être composé de lettre',
    'username.max' => 'Votre nom d\'utilisateur doit avoir maximum 20 lettres',
    'birthday.required' => 'Votre date de naissance est requis',
    'birthday.date' => 'Votre date de naissance ne correspond pas au format',
    'birthday.before_or_equal' => 'La date doit être une date antérieure ou égale à aujourd\'hui.',
]);

$save = function () {
    $validated = $this->validate();
    $user = Auth::user();
    $user->game_name = $this->game_name;
    $user->username = $this->username;
    if (Auth::user()->account_type !== 'team') {
        $user->birthday = $this->birthday;
        $user->nationality = $this->nationality;
    } else {
        $user->birthday = \Carbon\Carbon::now()->locale('fr_FR')->format('Y-m-d');
    }


    UserMission::create([
        'user_id' => $user->id,
        'mission_id' => OnboardingMission::where('name', 'addSection')->get()->first()->id,
    ]);

    if ($this->user->account_type === 'team')
        UserMission::create([
            'user_id' => $user->id,
            'mission_id' => OnboardingMission::where('name', 'addMember')->get()->first()->id,
        ]);

    $user->save();

    $this->redirect(route('pages.profil-creation.additional-info', absolute: false), navigate: true);
};
?>


<div class="flex min-h-full flex-col justify-center py-16 sm:px-6 lg:px-8">
    <x-slot name="h1">
        Présentez-vous
    </x-slot>
    <livewire:partials.auth-header/>

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <nav aria-label="Progress">
            <ol role="list" class="flex justify-center items-center">
                <li class="relative pr-8 sm:pr-20">
                    <!-- Completed Step -->
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="h-0.5 w-full bg-indigo-600"></div>
                    </div>
                    <a href="{{route('pages.profil-creation.account-type')}}" title="vers l'étape 1" class="relative flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 hover:bg-indigo-900">
                        <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                        </svg>
                        <span class="sr-only">Étape 1</span>
                    </a>
                </li>
                <li class="relative pr-8 sm:pr-20">
                    <!-- Current Step -->
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="h-0.5 w-full bg-gray-200"></div>
                    </div>
                    <a href="#" title="vers l'étape 2" class="relative flex h-8 w-8 items-center justify-center rounded-full border-2 border-indigo-600 bg-white" aria-current="step">
                        <span class="h-2.5 w-2.5 rounded-full bg-indigo-600" aria-hidden="true"></span>
                        <span class="sr-only">Étape 2</span>
                    </a>
                </li>
                <li class="relative">
                    <!-- Upcoming Step -->
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="h-0.5 w-full bg-gray-200"></div>
                    </div>
                    <a href="#" title="vers l'étape 3" class="group relative flex h-8 w-8 items-center justify-center rounded-full border-2 border-gray-300 bg-white hover:border-gray-400">
                        <span class="h-2.5 w-2.5 rounded-full bg-transparent group-hover:bg-gray-300" aria-hidden="true"></span>
                        <span class="sr-only">Étape 3</span>
                    </a>
                </li>
            </ol>
        </nav>
        <p class="mt-6 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Etape 2 : Pouvez-vous vous
            présentez ?</p>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
        <x-auth-session-status class="mb-4" :status="session('status')"/>
        <div class="bg-white px-6 py-12 shadow sm:rounded-lg sm:px-12">
            <form wire:submit="save" class="space-y-6">

                <div>
                    <label for="game_name" class="block text-sm font-medium leading-6 text-gray-900">{{ $this->pseudoInput  }}
                        <span class="text-red-600">*</span></label>
                    <div class="mt-2">
                        <input wire:model.live="game_name" type="text" name="game_name" id="game_name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-700 sm:text-sm sm:leading-6" placeholder="Lee">
                    </div>
                    @error('game_name')
                    <p class="text-sm text-red-600 space-y-1 mt-2 mb-4"> {{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500" id="password-description">Celui-ci sera votre pseudo
                        affiché</p>
                </div>

                <div>
                    <label for="surname" class="block text-sm font-medium leading-6 text-gray-900">Nom
                        d'utilisateur<span class="text-red-600">*</span></label>
                    <div class="mt-2">
                        <input wire:model.live="username" type="text" name="surname" id="surname" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-700 sm:text-sm sm:leading-6" placeholder="Sang-hyeo">
                    </div>
                    @error('username')
                    <p class="text-sm text-red-600 space-y-1 mt-2 mb-1"> {{ $message }}</p>
                    @enderror
                    <div class="flex items-center gap-x-1 mt-2 mb-1">
                        {{--                        s'il y a un @ et on affiche rien--}}
                        {{--                        s'il y a pas de @, on affiche il y a pas de @--}}
                        {{--                        s'il y a un @ + on affiche les 2 autre cas--}}
                        @if($this->usernameStartWithAt)
                            @if($this->usernameIsTaken && $username !== '@')
                                <svg class="text-red-600 sm:size-4" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
                                    <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14ZM8 4a.75.75 0 0 1 .75.75v3a.75.75 0 0 1-1.5 0v-3A.75.75 0 0 1 8 4Zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-sm text-red-600 mt-0" id="email-error">le pseudo n'est libre pas</p>
                            @elseif($username !== '@')
                                <svg class="text-green-400 sm:size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-sm text-green-400 mt-0" id="email-error">le pseudo est libre</p>
                            @else

                            @endif

                        @else
                            <svg class="text-red-600 sm:size-4" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
                                <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14ZM8 4a.75.75 0 0 1 .75.75v3a.75.75 0 0 1-1.5 0v-3A.75.75 0 0 1 8 4Zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-red-600 mt-0" id="email-error">le pseudo doit commencer avec un @</p>
                        @endif

                        {{--                        @endif--}}
                    </div>
                    <p class="mt-1 text-sm text-gray-500" id="password-description">Celui-ci sera votre pseudo de scène,
                        choisissez le bien</p>
                </div>

                @if($type !== 'team')
                    <div>
                        <label for="nationality" class="block text-sm font-medium leading-6 text-gray-900">Nationalité<span class="text-red-600">*</span></label>
                        <select wire:model.live="nationality" id="nationality" name="nationality" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-700 sm:text-sm sm:leading-6">
                            <option value="">-- choisissez votre nationalité --</option>
                            @foreach($nationalities as $nationality)
                                <option value="{{ $nationality }}">{{ __('nationalities.'.$nationality) }}</option>
                            @endforeach
                        </select>
                        @error('nationality')
                        <p class="text-sm text-red-600 space-y-1 mt-2 mb-4"> {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birthday" class="block text-sm font-medium leading-6 text-gray-900">Date de
                            naissance<span class="text-red-600">*</span></label>
                        <div class="mt-2">
                            <input wire:model.live="birthday" type="date" name="birthday" id="birthday" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-700 sm:text-sm sm:leading-6" placeholder="">
                        </div>
                        @error('birthday')
                        <p class="text-sm text-red-600 space-y-1 mt-2 mb-4"> {{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Suivant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
