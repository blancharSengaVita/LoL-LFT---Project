<?php

use function Livewire\Volt\{state};

state();

?>

<header class="absolute inset-x-0 top-0 z-50">
    <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
        <h2 class="sr-only">Navigation principale</h2>
        <div class="flex lg:flex-1">
            <a href="#" class="-m-1.5 p-1.5">
                <span class="sr-only">LoL-LFT</span>
                <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="">
            </a>
        </div>
        <div class="items-center flex flex-1 justify-end gap-x-6">
            <a href="{{ route('register') }}" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">S'inscrire</a>
            <a href="{{ route('login') }}" class="text-sm font-semibold leading-6 text-gray-900">Se connecter
                <span aria-hidden="true">&rarr;</span></a>
        </div>
    </nav>
</header>


