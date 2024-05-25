<?php

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use function Livewire\Volt\layout;
use function Livewire\Volt\{
    state,
    on,
    mount,
};

layout('layouts.dashboard');


state([
    'user',
    'playerExperiences',
    'playerExperiencesShow',
    'playerExperiencesHidden',
]);


mount(function () {
    $this->user = Auth::user();
    $this->playerExperiences = $this->user->playerExperiences()->get();
    foreach ($this->playerExperiences as $experience) {
        $experience->date = Carbon::parse($experience->date)->locale('fr_FR')->isoFormat('D MMMM YYYY');
    };
    $this->playerExperiencesShow = $this->playerExperiences->take(2);
    $this->playerExperiencesHidden = $this->playerExperiences->skip(2);
});

?>

<x-dashboard.article :title="'Palmarès'">
    <ul role="list" class="divide-y divide-gray-100">
        @foreach($playerExperiencesShow as $experience)
            <li class="flex gap-x-4 py-5 w-full" wire:key="{{ $experience->id }}">
                <div class="h-14 w-14 flex justify-center items-center bg-indigo-600">
                    <p class="text-3xl text-center text-white">{{$experience->placement}}</p>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold leading-6 text-gray-900">{{$experience->event}}</p>
                    <p class="truncate text-sm leading-5 text-gray-900">{{$experience->team}}
                        · {{$experience->job}}</p>
                    <p class="truncate text-sm leading-5 text-gray-500">{{$experience->date }}</p>
                </div>
                <button type="button" class="text-gray-700 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold ml-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                    </svg>
                </button>
            </li>
        @endforeach
        @foreach($playerExperiencesHidden as $experience)
            <li :class="open ? '' : 'hidden'" class="flex gap-x-4 py-5">
                <div class="h-14 w-14 flex justify-center items-center bg-indigo-600">
                    <p class="text-3xl text-center text-white">{{$experience->placement}}</p>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold leading-6 text-gray-900">{{$experience->event}}</p>
                    <p class="truncate text-sm leading-5 text-gray-900">{{$experience->team}}
                        · {{$experience->job}}</p>
                    <p class="truncate text-sm leading-5 text-gray-500">{{$experience->date }}</p>
                </div>
                <button type="button" class="text-gray-700 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold ml-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                    </svg>
                </button>
            </li>
        @endforeach
    </ul>
    <div class="flex justify-center">
        <Bouton @click="open = !open">
            <p :class="open ? 'hidden' : ''" class="flex items-center text-sm text-gray-800">Afficher plus
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/>
                </svg>
            </p>

            <p :class="open ? '' : 'hidden'" class="flex items-center text-sm text-gray-800">Afficher moins
                <svg class=" h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6"/>
                </svg>
            </p>
        </Bouton>
    </div>
</x-dashboard.article>
