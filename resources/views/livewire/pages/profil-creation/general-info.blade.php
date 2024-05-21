<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use function Livewire\Volt\{state, rules, layout};

layout('layouts.auth');

// faire un trim ici
state([
    'firstname' => '',
    'lastname' => '',
    'gameName' => '',
    'birthday' => '',
]);

rules([
    'firstname' => 'required|string',
    'lastname' => 'required|string',
    'gameName' => 'required|string',
    'birthday' => 'required|date',
])->messages([
    'firstname.required' => 'Votre prénom est requis',
    'firstname.string' => 'Votre prénom doit être composé de lettre',
    'lastname.required' => 'Votre nom est requis',
    'lastname.string' => 'Votre nom doit être composé de lettre',
    'gameName.required' => 'Votre pseudo est requis',
    'gameName.string' => 'Votre pseudo doit être composé de lettre',
    'birthday.required' => 'Votre date de naissance est requis',
    'birthday.date' => 'Votre date de naissance ne correspond pas au format',
]);

$save = function () {
    $validated = $this->validate();
    $user = Auth::user();
    $user->firstname = $this->firstname;
    $user->lastname = $this->lastname;
    $user->game_name = $this->gameName;
    $user->birthday = $this->birthday;
    $user->setup_completed = true;
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
                    <a href="#" class="relative flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 hover:bg-indigo-900">
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
                    <a href="#" class="relative flex h-8 w-8 items-center justify-center rounded-full border-2 border-indigo-600 bg-white" aria-current="step">
                        <span class="h-2.5 w-2.5 rounded-full bg-indigo-600" aria-hidden="true"></span>
                        <span class="sr-only">Étape 2</span>
                    </a>
                </li>
                <li class="relative">
                    <!-- Upcoming Step -->
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="h-0.5 w-full bg-gray-200"></div>
                    </div>
                    <a href="#" class="group relative flex h-8 w-8 items-center justify-center rounded-full border-2 border-gray-300 bg-white hover:border-gray-400">
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
                    <label for="firstname" class="block text-sm font-medium leading-6 text-gray-900">Prénom</label>
                    <div class="mt-2">
                        <input wire:model="firstname" type="text" name="firstname" id="firstname" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Lee">
                    </div>
                    @error('firstname')
                    <p class="text-sm text-red-600 space-y-1 mt-2 mb-4"> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="surname" class="block text-sm font-medium leading-6 text-gray-900">Nom de
                        famille</label>
                    <div class="mt-2">
                        <input wire:model="lastname" type="text" name="surname" id="surname" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Sang-hyeo">
                    </div>
                    @error('lastname')
                    <p class="text-sm text-red-600 space-y-1 mt-2 mb-4"> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="gameName" class="block text-sm font-medium leading-6 text-gray-900">Pseudo</label>
                    <div class="mt-2">
                        <input type="text" wire:model="gameName" name="gameName" id="gameName" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Faker">
                    </div>
                    @error('gameName')
                    <p class="text-sm text-red-600 space-y-1 mt-2"> {{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500" id="password-description">Celui-ci sera votre pseudo de scène,
                        choisissez le bien</p>
                </div>


                <div>
                    <label for="birthday" class="block text-sm font-medium leading-6 text-gray-900">Date de naissance</label>
                    <div class="mt-2">
                        <input wire:model="birthday" type="date" name="birthday" id="birthday" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="">
                    </div>
                    @error('birthday')
                    <p class="text-sm text-red-600 space-y-1 mt-2 mb-4"> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Suivant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
