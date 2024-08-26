<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;
use \App\Models\DisplayedInformation;
use App\Models\DisplayedInformationsOnce;
use \App\Models\Education;
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
    'openEducationModal',
    'openSingleEducationModal',
    'openAccordion',
    'singleEducation',
    'education',
    'educationsShow',
    'educationsHidden',
    'displayed',
    'displayedTemp',
    'displayedOnce',
    'establishment',
    'diploma',
    'entry_date',
    'exit_date',
    'id',
    'deleteModal',
    'education',
    'educations',
]);


rules([
    'establishment' => 'required|',
    'diploma' => 'required',
    'entry_date' => 'required|date',
    'exit_date' => 'required|date'
])->messages([
    'establishment.required' => 'Le champ est obligatoire.',
    'diploma.required' => 'Le champ est obligatoire.',
    'entry_date.required' => 'Le champ est obligatoire.',
    'exit_date.required' => 'Le champ est obligatoire.',
    'entry_date.date' => 'Le format n\'est pas conforme.',
    'exit_date.date' => 'Le format n\'est pas conforme.',
])->attributes([

]);

$renderChange = function () {
    $this->educations = $this->user->education()->orderBy('entry_date', 'asc')->get();
    foreach ($this->educations as $education) {
        $education->entry_date = Carbon::parse($education->date)->locale('fr_FR')->isoFormat('D MMMM YYYY');
        $education->exit_date = Carbon::parse($education->date)->locale('fr_FR')->isoFormat('D MMMM YYYY');
    };

    $this->educationsShow = $this->educations->take(2);
    $this->educationsHidden = $this->educations->skip(2);
    $this->displayedOnce = $this->user->displayedInformationsOnce->first()->education ?? 0;

    $this->displayed = $this->user->displayedInformation->first()->education ?? 0;
    $this->displayedTemp = $this->displayed;

    if ($this->displayedTemp === 1) {
        $this->displayedTemp = true;
    } else {
        $this->displayedTemp = false;
    }
};

mount(function () {
    $this->user = Auth::user();

    $this->renderChange();

    $this->openAccordion = false;
    $this->openEducationModal = false;
    $this->openSingleEducationModal = false;
    $this->deleteModal = false;

    $this->establishment = '';
    $this->diploma = '';
    $this->entry_date = '';
    $this->exit_date = '';
    $this->id = 0;
});


$saveEducationsSettings = function () {
    $this->displayed = $this->displayedTemp;
    DisplayedInformation::where('user_id', Auth::id())->update(['education' => $this->displayed]);
    $this->openAccordion = false;
    $this->renderChange();
    $this->openEducationModal = false;
    Toaster::success('Modification effectué avec succès');
};

$closeEducationsSettingsModal = function () {
    $this->displayed = $this->user->displayedInformation->first()->education;
    $this->displayedTemp = $this->displayed;

    if ($this->displayedTemp === 1) {
        $this->displayedTemp = true;
    } else {
        $this->displayedTemp = false;
    }

    $this->renderChange();
    $this->openEducationModal = false;
};

$createSingleEducation = function () {
    $this->openSingleEducationModal = true;
    $this->establishment = '';
    $this->diploma = '';
    $this->entry_date = '';
    $this->exit_date = '';
    $this->renderChange();
};

$closeSingleEducationModale = function () {
    $this->openSingleEducationModal = false;
};

$saveSingleEducation = function () {
    Education::updateOrCreate([
        'user_id' => Auth::id(),
        'id' => $this->id
    ],
        [
            'establishment' => $this->establishment,
            'diploma' => $this->diploma,
            'entry_date' => $this->entry_date,
            'exit_date' => $this->exit_date,
        ]);

    DisplayedInformationsOnce::where('user_id', $this->user->id)
        ->update(['education' => true]);

    $this->renderChange();
    $this->dispatch('renderOnboarding');
    $this->openSingleEducationModal = false;
    if($this->id === 0){
        Toaster::success('Formation ajouté avec succès');
    }

    if($this->id !== 0){
        Toaster::success('Formation modifiée avec succès');
    }
};

$editSingleEducation = function (Education $education) {
    $this->openSingleEducationModal = true;
    $this->establishment = $education->establishment;
    $this->diploma = $education->diploma;
    $this->entry_date = $education->entry_date;
    $this->entry_exit = $education->entry_exit;
    $this->id = $education->id;
    $this->renderChange();
};

$deleteSingleEducation = function () {
    $this->education->delete();
    $this->deleteModal = false;
    $this->renderChange();
};

$openDeleteModal = function (Education $education) {
    $this->deleteModal = true;
    $this->education = $education;
    $this->renderChange();
};

$closeDeleteModal = function () {
    $this->deleteModal = false;
    $this->renderChange();
};

on(['newEducation' => function () {
    $this->createSingleEducation();
}]);

on(['render' => function () {
    $this->renderChange();
}]);
?>

<article x-data="{
openAccordion: $wire.entangle('openAccordion'),
openEducationModal: $wire.entangle('openEducationModal'),
openSingleEducationModal: $wire.entangle('openSingleEducationModal'),
deleteModal: $wire.entangle('deleteModal'),
displayed:$wire.entangle('displayed'),
displayedOnce:$wire.entangle('displayedOnce'),
}"
>

    <div x-cloak x-show="displayed && displayedOnce" class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
        <div class="flex justify-between gap-x-4 pb-1 items-center sm:flex-nowrap">
            <h3 class="text-base font-semibold leading-6 text-gray-900">{{'Formations'}}</h3>
            <div class="flex">
                <button wire:click="createSingleEducation"
                        type="button" class="text-gray-700 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                </button>
                <button @click="openEducationModal = !openEducationModal" type="button" class="text-gray-700 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class=" sm:w-12/12">
            <ul role="list" class="divide-y divide-gray-100">
{{--                @dd($educationsShow);--}}
                @foreach($educationsShow as $education)
                    <li class="flex gap-x-4 py-5 w-full" wire:key="{{ $education->id }}">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{$education->diploma}}</p>
                            <p class="truncate text-sm leading-5 text-gray-900"> {{$education->establishment}}
                                 {{$education->event}}</p>
                            <p class="truncate text-sm leading-5 text-gray-500">{{'Du ' . $education->entry_date . ' au ' . $education->exit_date}}</p>
                        </div>
                        <div class="ml-auto">
                            <button wire:click="editSingleEducation({{$education}})" type="button" class="text-gray-700 group rounded-md p-2 text-sm leading-6 font-semibold ">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                </svg>
                            </button>
                            <button wire:click="openDeleteModal({{$education}})" type="button" class="text-gray-700 group rounded-md p-2 text-sm leading-6 font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                </svg>
                            </button>
                        </div>
                    </li>
                @endforeach
                @foreach($educationsHidden as $education)
                    <li :class="openAccordion ? '' : 'hidden'" class="flex gap-x-4 py-5">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{$education->title}}</p>
                            <p class="truncate text-sm leading-5 text-gray-900"> {{$education->team}}
                                · {{$education->event}}</p>
                            <p class="truncate text-sm leading-5 text-gray-500">{{$education->date }}</p>
                        </div>
                        <div class="ml-auto">
                            <button wire:click="editSingleEducation({{$education}})" type="button" class="text-gray-700 group rounded-md p-2 text-sm leading-6 font-semibold ">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                </svg>
                            </button>
                            <button wire:click="openDeleteModal({{$education}})" type="button" class="text-gray-700 group rounded-md p-2 text-sm leading-6 font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                </svg>
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>

            {{-- ACCORDEON --}}
            @if(count($this->educationsHidden))
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
    <div x-cloak x-show="openEducationModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                <div @click.away="openEducationModal = false" class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                    <form action="" wire:submit="saveEducationsSettings">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                    education</h3>
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
                            <button wire:click="closeEducationsSettingsModal" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500  sm:w-auto">
                                Annuler
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL SINGLE --}}
    <div x-cloak x-show="openSingleEducationModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                <div @click.away="openSingleEducationModal = false" class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                    <form action="" wire:submit="saveSingleEducation">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                    Éducation</h3>
                                <div class="mt-4">
                                    <label for="establishment" class="block text-sm font-medium leading-6 text-gray-900">
                                        Établissement
                                    </label>
                                    <div class="mt-2">
                                        <input wire:model="establishment" type="text" name="establishment" id="establishment" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="HEPL">
                                    </div>
                                    @if ($messages = $errors->get('establishment'))
                                        <div class="text-sm text-red-600 space-y-1 mt-2">
                                            <p>{{$messages[0]}}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-4">
                                    <label for="diploma" class="block text-sm font-medium leading-6 text-gray-900">
                                        Diplome
                                    </label>
                                    <div class="mt-2">
                                        <input wire:model="diploma" type="text" name="diploma" id="diploma" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Bachelier en web design">
                                    </div>
                                    @if ($messages = $errors->get('diploma'))
                                        <div class="text-sm text-red-600 space-y-1 mt-2">
                                            <p>{{$messages[0]}}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-4">
                                    <label for="entry_date" class="block text-sm font-medium leading-6 text-gray-900">
                                        Date d'entrée
                                    </label>
                                    <div class="mt-2">
                                        <input wire:model="entry_date" type="date" name="entry_date" id="entry_date" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="T1">
                                    </div>
                                    @if ($messages = $errors->get('entry_date'))
                                        <div class="text-sm text-red-600 space-y-1 mt-2">
                                            <p>{{$messages[0]}}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-4">
                                    <label for="exit_date" class="block text-sm font-medium leading-6 text-gray-900">
                                        Date de sortie
                                    </label>
                                    <div class="mt-2">
                                        <input wire:model="exit_date" type="date" name="exit_date" id="exit_date" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="1">
                                    </div>
                                    @if ($messages = $errors->get('exit_date'))
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
                            <button @click="openSingleEducationModal = false" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500  sm:w-auto">
                                Annuler
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- DELETE MODAL --}}
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
                                expérience</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Êtes-vous sûr de vouloir supprimer cette education ?
                                    L'expérience sera définitivement supprimée de nos serveurs. Cette action ne peut
                                    être annulée.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button wire:click="deleteSingleEducation" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
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
