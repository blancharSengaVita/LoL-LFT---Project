<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\DisplayedInformation;
use App\Models\DisplayedInformationsOnce;
use App\Models\language;
use Carbon\Carbon;
use Masmerise\Toaster\Toaster;
use function Livewire\Volt\layout;
use function Livewire\Volt\{
    state,
    on,
    mount,
    rules,
};

layout('layouts.dashboard');

state([
    'user',
    'openModal',
    'openSingleModal',
    'openAccordion',
    'singleLanguage',
    'language',
    'languagesShow',
    'languagesHidden',
    'displayed',
    'displayedTemp',
    'displayedOnce',
    'name',
    'level',
    'level',
    'id',
    'deleteModal',
    'languages',
    'availableLanguages',
]);


rules([
    'name' => 'required',
    'level' => 'required|string',
])->messages([
    'name.required' => 'Le champ est obligatoire.',
    'level.required' => 'Le champ est obligatoire.',
    'level.string' => 'Le champ doit etre un string',
])->attributes([

]);

$renderChange = function () {
    $this->languages = $this->user->language()->orderBy('created_at', 'desc')->get();
    foreach ($this->languages as $language) {
        $language->date = Carbon::parse($language->date)->locale('fr_FR')->isoFormat('D MMMM YYYY');
    }

    $this->languagesShow = $this->languages->take(2);
    $this->languagesHidden = $this->languages->skip(2);
    $this->displayedOnce = $this->user->displayedInformationsOnce->first()->languages ?? 0;
};

mount(function () {
    $this->user = Auth::user();

    $this->renderChange();

    $this->displayed = $this->user->displayedInformation->first()->languages ?? 0;
    $this->displayedTemp = $this->displayed;

    if ($this->displayedTemp === 1) {
        $this->displayedTemp = true;
    } else {
        $this->displayedTemp = false;
    }

	$this->openAccordion = false;
    $this->openModal = false;
    $this->openSingleModal = false;
    $this->deleteModal = false;

    $this->name = '';
    $this->level = '';
    $this->id = 0;
    $this->availableLanguages = require __DIR__ . '/../../../../app/enum/languages.php';
});

$savelanguagesSettings = function () {
    $this->displayed = $this->displayedTemp;
    DisplayedInformation::where('user_id', Auth::id())->update(['languages' => $this->displayed]);
    $this->openAccordion = false;
    $this->renderChange();
    $this->openModal = false;
    Toaster::success('Modification effectué avec succès');
};

$closelanguagesSettingsModal = function () {
    $this->displayed = $this->user->displayedInformation->first()->languages ?? 0;
    $this->displayedTemp = $this->displayed;

    if ($this->displayedTemp === 1) {
        $this->displayedTemp = true;
    } else {
        $this->displayedTemp = false;
    }

    $this->renderChange();
    $this->openModal = false;
};

$createsingleLanguage = function () {
    $this->openSingleModal = true;
    $this->name = '';
    $this->level = '';
    $this->id = 0;
    $this->renderChange();
};

$closesingleLanguageModale = function () {
    $this->openSingleModal = false;
};

$savesingleLanguage = function () {
    try {
        $this->validate();
    } catch (\Illuminate\Validation\ValidationException $e) {
        $this->renderChange();
        throw $e;
    }

    Language::updateOrCreate(
        [
            'user_id' => Auth::id(),
            'id' => $this->id
        ],
        [
            'level' => $this->level,
            'name' => $this->name
        ]
    );

	DisplayedInformationsOnce::where('user_id', $this->user->id)
        ->update(['languages' => true]);

    $this->renderChange();
    $this->dispatch('renderOnboarding');
    $this->openSingleModal = false;
    if($this->id === 0){
        Toaster::success('Langue ajouté avec succès');
    }

    if($this->id !== 0){
        Toaster::success('Langue modifiée avec succès');
    }
};

$editsingleLanguage = function (Language $language) {
    $this->openSingleModal = true;
    $this->name = $language->name;
    $this->level = $language->level;
    $this->renderChange();
};

$deletesingleLanguage = function () {
    $this->language->delete();
    $this->deleteModal = false;
    $this->renderChange();
};

$openDeleteModal = function (Language $language) {
    $this->deleteModal = true;
    $this->language = $language;
    $this->renderChange();
};

$closeDeleteModal = function () {
    $this->deleteModal = false;
    $this->renderChange();
};

on(['newLanguage' => function () {
    $this->createsinglelanguage();
}]);
?>
<article
    x-data="{
openAccordion: $wire.entangle('openAccordion'),
openModal: $wire.entangle('openModal'),
openSingleModal: $wire.entangle('openSingleModal'),
deleteModal: $wire.entangle('deleteModal'),
displayed:$wire.entangle('displayed'),
displayedOnce:$wire.entangle('displayedOnce'),
}">
    <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6"  x-cloack x-show="displayed && displayedOnce">
    <div
        class="flex justify-between gap-x-4 pb-1 items-center sm:flex-nowrap">
        <h3 class="text-base font-semibold leading-6 text-gray-900">{{'Langues'}}</h3>

        <div class="flex">
            <button wire:click="createsingleLanguage"
                    type="button" class="text-gray-700 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
            </button>
            <button @click="openModal = !openModal" type="button" class="text-gray-700 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                </svg>
            </button>
        </div>
    </div>
    <div class=" sm:w-12/12">
        <ul role="list" class="divide-y divide-gray-100">
            @foreach($languagesShow as $language)
                <li class="flex items-center gap-x-4 py-5 w-full" wire:key="{{ $language->id }}">
                    <div class="h-14 w-14 flex justify-center items-center bg-indigo-600">
                        <p class="text-3xl text-center text-white">{{ ucfirst(array_search($language->name, $availableLanguages)) }}</p>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold leading-6 text-gray-900">{{$language->name}}</p>
                        <p class="truncate text-sm leading-5 text-gray-900">{{$language->level}}</p>
                    </div>
                    <div class="ml-auto">
                        <button wire:click="editsingleLanguage({{$language}})" type="button" class="text-gray-700 group rounded-md p-2 text-sm leading-6 font-semibold ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                            </svg>
                        </button>
                        <button wire:click="openDeleteModal({{$language}})" type="button" class="text-gray-700 group rounded-md p-2 text-sm leading-6 font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                            </svg>
                        </button>
                    </div>
                </li>
            @endforeach
            @foreach($languagesHidden as $language)
                <li :class="openAccordion ? '' : 'hidden'" class="flex items-center gap-x-4 py-5">
                    <div class="h-14 w-14 flex justify-center items-center bg-indigo-600">
                        <p class="text-3xl text-center text-white">{{ ucfirst(array_search($language->name, $availableLanguages)) }}</p>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold leading-6 text-gray-900">{{$language->name}}</p>
                        <p class="truncate text-sm leading-5 text-gray-900">{{$language->level}}</p>
                    </div>
                    <div class="ml-auto">
                        <button wire:click="editsingleLanguage({{$language}})" type="button" class="text-gray-700 group rounded-md p-2 text-sm leading-6 font-semibold ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                            </svg>
                        </button>
                        <button wire:click="openDeleteModal({{$language}})" type="button" class="text-gray-700 group rounded-md p-2 text-sm leading-6 font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                            </svg>
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>

        {{-- ACCORDEON --}}
        @if(count($this->languagesHidden))
            <div class="flex justify-center">
                <Bouton @click="openAccordion = !openAccordion">
                    <p :class="openAccordion ? 'hidden' : ''" class="flex items-center text-sm text-gray-800">Afficher
                        plus
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/>
                        </svg>
                    </p>

                    <p :class="openAccordion ? '' : 'hidden'" class="flex items-center text-sm text-gray-800">Afficher
                        moins
                        <svg class=" h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6"/>
                        </svg>
                    </p>
                </Bouton>
            </div>
        @endif
    </div>
    </div>
    {{-- MODAL SETTINGS DE LA SECTION  --}}
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
                <div @click.away="$wire.closelanguagesSettingsModal" class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                    <form action="" wire:submit="savelanguagesSettings">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                    Langues</h3>
                            </div>
                        </div>
                        {{-- modale de confirmation de suppression --}}
                        <div class="mt-5 relative flex items-start">
                            <div class="flex h-6 items-center">
                                <input wire:model="displayedTemp" id="displayed" aria-describedby="section-displayed-or-not" name="displayed" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 checked:">
                            </div>
                            <div class="ml-3 text-sm leading-6">
                                <label for="displayed" class="font-medium text-gray-900">Afficher cette section aux
                                    public</label>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:ml-3">
                                Enregistrer les changements
                            </button>
                            <button wire:click="closelanguagesSettingsModal" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500  sm:w-auto">
                                Annuler
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL SETTINGS DE LA SECTION  --}}
    <div x-cloak x-show="openSingleModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                <div @click.away="openSingleModal = false" class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                    <form action="" wire:submit="savesingleLanguage">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                    Langues</h3>
                                <div class="mt-4">
                                    {{--                                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">--}}
                                    {{--                                        nom--}}
                                    {{--                                    </label>--}}
                                    {{--                                    <div class="mt-2">--}}
                                    {{--                                        <input wire:model="name" type="text" name="name" id="name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Meilleur joueur d'Europe">--}}
                                    {{--                                    </div>--}}
                                    {{--                                    @if ($messages = $errors->get('name'))--}}
                                    {{--                                        <div class="text-sm text-red-600 space-y-1 mt-2">--}}
                                    {{--                                            <p>{{$messages[0]}}</p>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    @endif--}}

                                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">name</label>
                                    <select wire:model="name" id="name" name="name" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="">-- choisissez une langue --</option>
                                        @foreach($availableLanguages as $language)
                                            <option value="{{ $language }}">{{ __('languages.'.$language) }}</option>
                                        @endforeach
                                    </select>
                                    @error('name')
                                    <p class="text-sm text-red-600 space-y-1 mt-2 mb-4"> {{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="level" class="block text-sm font-medium leading-6 text-gray-900">
                                        Niveau
                                    </label>
                                    <div class="mt-2">
                                        <input wire:model.live="level" type="text" name="level" id="level" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Meilleur joueur d'Europe">
                                    </div>
                                    @if ($messages = $errors->get('level'))
                                        <div class="text-sm text-red-600 space-y-1 mt-2">
                                            <p>{{$messages[0]}}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:ml-3">
                                sauvegarder
                            </button>
                            <button @click="openSingleModal = false" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500  sm:w-auto">
                                Annuler
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div x-cloak x-show="deleteModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                <div @click.away="deleteModal = false" class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Supprimer une
                                langue</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Êtes-vous sûr de vouloir supprimer cette langue ?
                                    L'expérience sera définitivement supprimée de nos serveurs. Cette action ne peut
                                    être annulée.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button wire:click="deletesingleLanguage" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                            Supprimer
                        </button>
                        <button wire:click="closeDeleteModal" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
