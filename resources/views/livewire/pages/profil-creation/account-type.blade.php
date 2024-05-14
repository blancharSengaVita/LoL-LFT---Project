<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

use function Livewire\Volt\{state, rules};
use function Livewire\Volt\layout;

layout('layouts.auth');

state([
    'account_type' => '',
]);

rules([
    'account_type' => 'required',
])->messages([
    'account_type.required' => 'Veuillez choisir un type de compte',
]);

$save = function () {
    $validated = $this->validate();

    $user = Auth::user();

	$user->account_type = $validated['account_type'];
    $user->update();

    $this->redirect(route('pages.profil-creation.general-info', absolute: false), navigate: true);
};

?>

<div class="flex min-h-full flex-col justify-center py-16 sm:px-6 lg:px-8">
    <x-slot name="h1">
        Choix du type de compte
    </x-slot>
    <livewire:partials.auth-header/>

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <nav aria-label="Progress">
            <ol role="list" class="flex justify-center items-center">
                <li class="relative pr-8 sm:pr-20">
                    <!-- Current Step -->
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="h-0.5 w-full bg-gray-200"></div>
                    </div>
                    <a href="#" class="relative flex h-8 w-8 items-center justify-center rounded-full border-2 border-indigo-600 bg-white" aria-current="step">
                        <span class="h-2.5 w-2.5 rounded-full bg-indigo-600" aria-hidden="true"></span>
                        <span class="sr-only">Step 3</span>
                    </a>
                </li>
                <li class="relative pr-8 sm:pr-20">
                    <!-- Upcoming Step -->
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="h-0.5 w-full bg-gray-200"></div>
                    </div>
                    <a href="#" class="group relative flex h-8 w-8 items-center justify-center rounded-full border-2 border-gray-300 bg-white hover:border-gray-400">
                        <span class="h-2.5 w-2.5 rounded-full bg-transparent group-hover:bg-gray-300" aria-hidden="true"></span>
                        <span class="sr-only">Step 4</span>
                    </a>
                </li>
                <li class="relative">
                    <!-- Upcoming Step -->
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="h-0.5 w-full bg-gray-200"></div>
                    </div>
                    <a href="#" class="group relative flex h-8 w-8 items-center justify-center rounded-full border-2 border-gray-300 bg-white hover:border-gray-400">
                        <span class="h-2.5 w-2.5 rounded-full bg-transparent group-hover:bg-gray-300" aria-hidden="true"></span>
                        <span class="sr-only">Step 5</span>
                    </a>
                </li>
            </ol>
        </nav>
        <p class="mt-6 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Etape 1 : Quel type de
            compte voulez-vous créer</p>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
        <x-auth-session-status class="mb-4" :status="session('status')"/>
        <div class="bg-white px-6 py-12 shadow sm:rounded-lg sm:px-12">
            <form wire:submit="save" class="space-y-6">

                <fieldset>
                    @if ($errors->any())
                        <div class="flex justify-center">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li class="text-sm text-red-600 space-y-1 mb-4">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <legend class="sr-only">Type de compte</legend>
                    <div class="space-y-5">
                        <div class="relative flex items-start">
                            <div class="flex h-6 items-center">
                                <input wire:model.live="account_type" value="player" id="player" aria-describedby="player-description" name="account_type" type="radio" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                            </div>
                            <div class="ml-3 text-sm leading-6">
                                <label for="player" class="font-medium text-gray-900">Joueur</label>
                                <p id="player-description" class="text-gray-500">Aussi bien joueur amateur que joueur professionnel</p>
                            </div>
                        </div>
                        <div class="relative flex items-start">
                            <div class="flex h-6 items-center">
                                <input wire:model.live="account_type" value="staff" id="staff" aria-describedby="staff-description" name="account_type" type="radio" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                            </div>
                            <div class="ml-3 text-sm leading-6">
                                <label for="staff" class="font-staff text-gray-900">Staff</label>
                                <p id="staff-description" class="text-gray-500">Tous les métiers autres que joueurs (coach, analyste, manager)</p>
                            </div>
                        </div>
                        <div class="relative flex items-start">
                            <div class="flex h-6 items-center">
                                <input wire:model.live="account_type" value="team" id="Team" aria-describedby="Team-description" name="account_type" type="radio" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                            </div>
                            <div class="ml-3 text-sm leading-6">
                                <label for="Team" class="font-medium text-gray-900">Équipe</label>
                                <p id="Team-description" class="text-gray-500">Ça peut être une équipe amateur ou une équipe d'une organisation reconnue</p>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Suivant
                    </button>
                </div>
            </form>
        </div>

        <p class="mt-10 text-center text-sm text-gray-500">
            <a href="{{ route('dashboard') }}" wire:navigate class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Ignorez</a>
        </p>
    </div>
</div>
