<?php

use function Livewire\Volt\{state};

?>

<header class="absolute inset-x-0 top-0 z-50">
    <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
        <h2 class="sr-only">Navigation principale</h2>
        <div class="flex lg:flex-1">
            <a href="{{ route('home') }}" title="Vers la page d'accueil" wire:navigate class="-m-1.5 p-1.5">
                <span class="sr-only">LoL-LFT</span>
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
            </a>
        </div>
    </nav>
</header>
