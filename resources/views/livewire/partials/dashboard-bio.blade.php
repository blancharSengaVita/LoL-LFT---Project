<?php

use Illuminate\Support\Facades\Auth;
use \App\Models\DisplayedInformation;
use Masmerise\Toaster\Toaster;
use function Livewire\Volt\{
    state,
    mount,
    on,
};
state([
    'user',
    'open',
    'bio',
    'bioDisplayed',
    'displayedTemp',
    'displayedOnce',
]);

$renderChange = function () {
    $this->bio = $this->user->bio;
    $this->displayedOnce = $this->user->displayedInformationsOnce->first()->bio ?? 0;
};

mount(function () {
    $this->user = Auth::user();

	$this->renderChange();

    $this->bioDisplayed = $this->user->displayedInformation->first()?->bio;
    $this->displayedTemp = $this->bioDisplayed;

    if ($this->displayedTemp === 1) {
        $this->displayedTemp = true;
    } else {
        $this->displayedTemp = false;
    }

    $this->open = false;
});

$save = function () {
    $this->bioDisplayed = $this->displayedTemp;
    DisplayedInformation::where('user_id', Auth::id())->update(['bio' => $this->bioDisplayed]);
    $this->user->bio = $this->bio;
    $this->user->save();
    $this->open = false;
    Toaster::success('Modification effectué avec succès');
};

$close = function () {
    $this->bio = $this->user->bio;
    $this->bioDisplayed = $this->user->displayedInformation->first()->bio ?? 0;
    $this->displayedTemp = $this->bioDisplayed;

    if ($this->displayedTemp === 1) {
        $this->displayedTemp = true;
    } else {
        $this->displayedTemp = false;
    }

    $this->open = false;
};

$openModal = function () {
    $this->open = true;
};

on(['newBio' => function () {
    $this->openModal();
}]);
?>

<article x-data="{
open: $wire.entangle('open'),
bioDisplayed : $wire.entangle('bioDisplayed'),
displayedOnce : $wire.entangle('displayedOnce'),
}">
    <div x-show="bioDisplayed && displayedOnce" class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
        <div class="flex justify-between gap-x-4 pb-1 items-center sm:flex-nowrap">
            <h3 class="text-base font-semibold leading-6 text-gray-900">Bio</h3>
            <div class="flex">
                <button @click="open = !open" type="button" class="text-gray-700 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class=" sm:w-12/12">
            <p class="mt-2 text-sm text-gray-900 ">{{ $bio }}</p>
        </div>
    </div>
    <div x-cloak x-show="open" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                <div @click.away="$wire.close" class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                    <form action="" wire:submit="save">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Bio</h3>
                                <div>
                                    <label for="comment" class=" block text-sm font-medium leading-6 text-gray-900"></label>
                                    <div class="mt-2 w-full">
                                        <textarea wire:model="bio" rows="4" name="comment" id="comment" class=" block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- modale de confirmation de suppression --}}
                        <div class="mt-5 relative flex items-start">
                            <div class="flex h-6 items-center">
                                <input wire:model="displayedTemp" id="offers" aria-describedby="offers-description" name="offers" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 checked:">
                            </div>
                            <div class="ml-3 text-sm leading-6">
                                <label for="offers" class="font-medium text-gray-900">Afficher cette section au
                                    public</label>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:ml-3">
                                Enregistrer les changements
                            </button>
                            <button @click="$wire.close()" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500  sm:w-auto">
                                Annuler
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</article>
