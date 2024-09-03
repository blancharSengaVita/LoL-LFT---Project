<?php

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('layouts.guest');

state(['email' => '']);

rules(['email' => ['required', 'string', 'email']]);

$sendPasswordResetLink = function () {
    $this->validate();

    // We will send the password reset link to this user. Once we have attempted
    // to send the link, we will examine the response then see the message we
    // need to show to the user. Finally, we'll send out a proper response.
    $status = Password::sendResetLink(
        $this->only('email')
    );

    if ($status != Password::RESET_LINK_SENT) {
        $this->addError('email', __($status));

        return;
    }

    $this->reset('email');

    Session::flash('status', __($status));
};

?>

<div class="flex min-h-full flex-col justify-center py-16 sm:px-6 lg:px-8">
    <x-slot name="h1">
        Mot de passe oublié ?
    </x-slot>
    <livewire:partials.auth-header/>



    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="my-auto flex h-16 shrink-0 justify-center items-center text-indigo-600">
            <svg class="fill-current" width="37" height="37" viewBox="0 0 37 37" xmlns="http://www.w3.org/2000/svg">
                <rect x="35.1433" y="17.14" width="6" height="24" transform="rotate(130.61 35.1433 17.14)" />
                <path d="M16.9235 1.51831L21.4784 5.42372L11.064 17.5703L8.46173 11.3874L16.9235 1.51831Z" />
                <path d="M19.2009 3.47102L16.9235 1.51831L18.2253 -8.88109e-06L19.2009 3.47102Z" />
                <path d="M32.8659 15.1873L35.1433 17.14L36.4451 15.6216L32.8659 15.1873Z" />
                <rect x="1.3018" y="19.7382" width="6" height="24" transform="rotate(-49.3903 1.3018 19.7382)" />
                <path d="M19.5217 35.3598L14.9667 31.4544L25.3811 19.3079L27.9834 25.4907L19.5217 35.3598Z" />
                <path d="M17.2442 33.4071L19.5217 35.3598L18.2199 36.8782L17.2442 33.4071Z" />
                <path d="M3.57929 21.6909L1.3018 19.7382L-3.51667e-06 21.2565L3.57929 21.6909Z" />
                <rect x="22.4451" y="18.3232" width="6" height="6" transform="rotate(130.61 22.4451 18.3232)" />
            </svg>
        </div>
        <p class="mt-6 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Mot de passe oublié ?</p>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
        <div class="bg-white px-6 py-12 shadow sm:rounded-lg sm:px-12">
            <form wire:submit="sendPasswordResetLink" class="space-y-6" >
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">E-mail</label>
                    <div class="mt-2">
                        <input wire:model="email" id="email" name="email" type="email" autocomplete="email" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Envoyer le lien de reinitialisation</button>
                </div>
            </form>
        </div>
        <p class="mt-10 text-center text-sm text-gray-500">
            <a href="{{ route('login') }}" title="vers la page de login" wire:navigate class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Retourner à la page de connexion</a>
        </p>
    </div>
</div>

