<?php

use function Livewire\Volt\{state};

?>

<header class="absolute inset-x-0 top-0 z-50">
    <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
        <h2 class="sr-only">Navigation principale</h2>
        <div class="flex lg:flex-1">
            <a href="{{ route('home') }}" wire:navigate class="-m-1.5 p-1.5">
                <span class="sr-only">LoL-LFT</span>
                <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="">
            </a>
        </div>
    </nav>
</header>
