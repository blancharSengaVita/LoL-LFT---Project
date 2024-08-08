<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\DisplayedInformation;
use App\Models\DisplayedInformationsOnce;
use App\Models\Award;
use App\Models\User;
use Carbon\Carbon;
use Masmerise\Toaster\Toaster;
use App\Models\TeamMember;

use function Livewire\Volt\layout;
use function Livewire\Volt\{
	state,
	on,
	mount,
	rules,
	computed,
};

layout('layouts.dashboard');

state([
	'user',
	'openModal',
	'openSingleModal',
	'openAccordion',
	'singleModel',
	'models',
	'model',
	'nationalities',
	'jobs',
	'username',
	'job',
	'nationality',
	'entry_date',
    'archived',
	'id',
	'deleteModal',
	'search' => '',
	'create',
]);


rules([
	'username' => 'required|',
	'job' => 'required',
	'nationality' => 'required',
	'entry_date' => 'date'
])->messages([
	'username.required' => 'Le champ est obligatoire.',
	'job.required' => 'Le champ est obligatoire.',
	'nationality.required' => 'Le champ est obligatoire.',
	'entry_date.date' => 'Le champ doit être une date',
])->attributes([

]);

$renderChange = function () {
	$this->models = $this->user->players()->where('type', 'player')->where('archived', false)->get();

	foreach ($this->models as $model) {
		$model->entry_date = Carbon::parse($model->entry_date)->locale('fr_FR')->isoFormat('D MMMM YYYY');
	}
};

mount(function () {
	$this->user = Auth::user();

	$this->renderChange();
	$this->nationalities = require __DIR__ . '/../../../../app/enum/nationalities.php';
	$this->jobs = require __DIR__ . '/../../../../app/enum/jobs.php';
	$this->jobs = $this->jobs['player'];
	$this->openAccordion = false;
	$this->openModal = false;
	$this->openSingleModal = false;
	$this->deleteModal = false;

	$this->username = '';
	$this->job = '';
	$this->nationality = '';
	$this->entry_date = '';
    $this->archived = false;
	$this->id = 0;
});

$filteredUser = computed(function () {
	return User::where('account_type', '=', 'player')
        ->orWhere('account_type', '=', 'staff')
		->where(function ($query) {
			$query->where('username', 'like', '%' . $this->search . '%')
				->orWhere('game_name', 'like', '%' . $this->search . '%');
		})
		->limit(4)
		->get();
});

$createSingleModel = function () {
	$this->create = true;
	$this->openSingleModal = true;
	$this->username = '';
	$this->job = '';
	$this->nationality = '';
	$this->entry_date = Carbon::now()->format('Y-m-d');
    $this->archived = false;
	$this->id = 0;
	$this->renderChange();
};

$closeSingleModelModale = function () {
	$this->openSingleModal = false;
};

$saveSingleModel = function () {
	try {
		$this->validate();
	} catch (\Illuminate\Validation\ValidationException $e) {
		$this->renderChange();
		throw $e;
	}

	TeamMember::updateOrCreate([
		'team_id' => Auth::id(),
		'id' => $this->id
	],
		[
			'username' => $this->username,
			'job' => $this->job,
			'nationality' => $this->nationality,
			'entry_date' => $this->entry_date,
			'type' => 'player',
            'archived' => $this->archived,
		]);

	$this->renderChange();
	$this->dispatch('renderOnboarding');
    $this->dispatch('archiveMember');
	$this->openSingleModal = false;
	if ($this->id === 0) {
		Toaster::success('Joueurs ajouté avec succès');
	}

	if ($this->id !== 0) {
		Toaster::success('Joueurs modifiée avec succès');
	}
};

$editSingleModel = function (TeamMember $model) {
	$this->create = false;
	$this->openSingleModal = true;
	$this->username = $model->username;
	$this->job = $model->job;
	$this->nationality = $model->nationality;
	$this->entry_date = $model->entry_date;
    if ($model->archived === 1) {
        $model->archived = true;
    } else {
        $model->archived = false;
    }
    $this->archived = $model->archived;
	$this->id = $model->id;
	$this->renderChange();
};

$deleteSingleModel = function () {
	$this->model->delete();
	$this->deleteModal = false;
	$this->renderChange();
};

$openDeleteModal = function (TeamMember $model) {
	$this->deleteModal = true;
	$this->model = $model;
	$this->renderChange();
};

$closeDeleteModal = function () {
	$this->deleteModal = false;
	$this->renderChange();
};

on(['newAward' => function () {
	$this->createSingleModel();
}]);

$sendNotification = function () {
//    dd('salut');
	$this->openSingleModal = false;
	Toaster::success('Demande d\'ajout envoyé ');
};

on(['archiveMember' => function () {
    $this->renderChange();
}]);
?>

<article x-data="{
openAccordion: $wire.entangle('openAccordion'),
openModal: $wire.entangle('openModal'),
openSingleModal: $wire.entangle('openSingleModal'),
deleteModal: $wire.entangle('deleteModal'),
}">

    <div x-cloack class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
        <div class="flex justify-between gap-x-4 pb-1 items-center sm:flex-nowrap">
            <h3 class="text-base font-semibold leading-6 text-gray-900">{{'Joueurs'}}</h3>
            <div class="flex">
                <button wire:click="createSingleModel"
                        type="button" class="text-gray-700 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class=" sm:w-12/12">
            <ul role="list" class="divide-y divide-gray-100">
                @if(count($models)===0 || null)
                    <p class="mt-1 mb-1 flex justify-center">Il n'y a aucun joueur dans cette équipe</p>
                @else
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                        <tr class="flex">
                            <th scope="col" class= "flex-1 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                Nom
                            </th>
                            <th scope="col" class="flex-1  px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Poste</th>
                            <th scope="col" class="flex-1  hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">
                                Nationalité
                            </th>
                            <th scope="col" class="flex-1  hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 md:table-cell">
                                Date d'entrée
                            </th>
                            <th scope="col" class="flex-1  relative py-3.5 pl-3 pr-4 sm:pr-0">
                                <span class="sr-only">actions</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @foreach($models as $player)
                            <tr class="flex" >
                                <td class="truncate flex-1 whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{ $player->username }}</td>
                                <td class="flex-1 whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{  __('jobs.'.$player->job)  }}</td>
                                <td class="flex-1 hidden whitespace-nowrap px-3 py-4 text-sm text-gray-500 sm:table-cell">{{ __('nationalities.'.$player->nationality) }}</td>
                                <td class="flex-1 hidden whitespace-nowrap px-3 py-4 text-sm text-gray-500 md:table-cell">{{ $player->entry_date }}</td>
                                <td class="flex-1 relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                    <div class="ml-auto">
                                        <button wire:click="editSingleModel({{$player}})" type="button" class="text-gray-700 group rounded-md px-2 text-sm leading-6 font-semibold ">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                            </svg>
                                        </button>
                                        <button wire:click="openDeleteModal({{$player}})" type="button" class="text-gray-700 group rounded-md px-2 text-sm leading-6 font-semibold">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif

            </ul>
        </div>
    </div>

    {{-- MODAL SINGLE MODEL  --}}
    <div x-cloak
         x-show="openSingleModal"
         {{--         x-show="true"--}}
         class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                    <form action="" wire:submit="saveSingleModel">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                                <div x-show="$wire.create" class="flex gap-x-4 items-center mb-4">
                                    <h3 class="w-auto text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                        Ajouter un joueur existant</h3>
                                    <div x-show="$wire.create" class="w-auto">
                                        <label for="combobox" class="sr-only block text-sm font-medium leading-6 text-gray-900">Assigned
                                            to</label>
                                        <div class="relative"
                                             x-data="{
                                                    isFocused: false,
                                                    blurTimeout: null
                                                    }"

                                        >
                                            <input
                                                autocomplete="off"
                                                @focus="clearTimeout(blurTimeout); isFocused = true"
                                                @blur="blurTimeout = setTimeout(() => { isFocused = false }, 200)"
                                                wire:model.live="search" id="combobox" type="search" class="w-80 rounded-md border-0 bg-white py-1.5 pl-8 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" role="combobox" aria-controls="options" aria-expanded="false">
                                            <button type="button" class="absolute inset-y-0 left-0 flex items-center rounded-r-md px-2 focus:outline-none">
                                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>

                                            <ul
                                                x-data="{
                                                    searchValue: $wire.entangle('search'),
                                                    }"

                                                x-show="searchValue && isFocused"
                                                class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" id="options" role="listbox">
                                                <!--
                                                  Combobox option, manage highlight styles based on mouseenter/mouseleave and keyboard navigation.

                                                  Active: "text-white bg-indigo-600", Not Active: "text-gray-900"
                                                -->
                                                @if(!count($this->filteredUser))
                                                    <li class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900" id="option-0" role="option" tabindex="-1">
                                                        <p>Aucun résultat</p>
                                                    </li>
                                                @endif
                                                @foreach($this->filteredUser as $player)
                                                    <li
                                                        wire:key="player-{{$player->id}}"
                                                        {{--                                                            wire:click="sendNotification"--}}@click="$wire.sendNotification"
                                                        x-data="{ isHovered: false }"
                                                        @mouseenter="isHovered = true"
                                                        @mouseleave="isHovered = false"
                                                        :class="isHovered ? 'text-white bg-indigo-600' : 'text-gray-900'"
                                                        class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900" id="option-0" role="option" tabindex="-1"
                                                    >
                                                        <div class="flex items-center">
                                                            {{--                                                                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="h-6 w-6 flex-shrink-0 rounded-full">--}}
                                                            <!-- Selected: "font-semibold" -->
                                                            <span class="ml-3 truncate">{{ $player->game_name }}</span>
                                                            <span :class="isHovered ? 'text-indigo-200' : 'text-gray-500'" class="ml-2 truncate text-gray-500">{{ $player->username }}</span>
                                                        </div>

                                                        <!--
                                                          Checkmark, only display for selected option.

                                                          Active: "text-white", Not Active: "text-indigo-600"
                                                        -->
                                                        <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600">
                                                            <svg x-show="isHovered" class="h-5 w-5 text-indigo-500 " viewBox="0 0 24 24" stroke-width="2px" stroke="white" fill="none" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/>
                                                            </svg>
                                                        </span>
                                                    </li>
                                                @endforeach


                                                <!-- More items... -->
                                            </ul>
                                        </div>
                                    </div>

                                </div>

                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                    Ajouter un joueur manuellement </h3>

                                <div class="mt-4">
                                    <label for="username" class="block text-sm font-medium leading-6 text-gray-900">
                                        Nom<span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-2">
                                        <input wire:model="username" type="text" name="username" id="username" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Faker">
                                    </div>
                                    @if ($messages = $errors->get('username'))
                                        <div class="text-sm text-red-600 space-y-1 mt-2">
                                            <p>{{$messages[0]}}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-span-3 mt-4">
                                    <label for="job" class="block text-sm font-medium leading-6 text-gray-900">Poste<span class="text-red-500">*</span></label>
                                    <select wire:model="job" id="job" name="job" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="">-- choisissez votre poste --</option>
                                        @foreach($jobs as $job)
                                            <option value="{{ $job }}">{{ __('jobs.'.$job) }}</option>
                                        @endforeach
                                    </select>
                                    @error('job')
                                    <p class="text-sm text-red-600 space-y-1 mt-2 mb-4"> {{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="nationality" class="block text-sm font-medium leading-6 text-gray-900">Nationalité<span class="text-red-500">*</span></label>
                                    <select wire:model="nationality" id="nationality" name="nationality" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="">-- choisissez votre nationalité --</option>
                                        @foreach($nationalities as $nationality)
                                            <option value="{{ $nationality }}">{{ __('nationalities.'.$nationality) }}</option>
                                        @endforeach
                                    </select>
                                    @error('nationality')
                                    <p class="text-sm text-red-600 space-y-1 mt-2 mb-4"> {{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mt-4">
                                    <label for="entry_date" class="block text-sm font-medium leading-6 text-gray-900">
                                        Date
                                    </label>
                                    <div class="mt-2">
                                        <input wire:model="entry_date" type="date" name="entry_date" id="entry_date" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="1">
                                    </div>
                                    @if ($messages = $errors->get('entry_date'))
                                        <div class="text-sm text-red-600 space-y-1 mt-2">
                                            <p>{{$messages[0]}}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-5 relative flex items-start">
                                    <div class="flex h-6 items-center">
                                        <input wire:model="archived" id="archived" aria-describedby="offers-description" name="archived" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 checked:">
                                    </div>
                                    <div class="ml-3 text-sm leading-6">
                                        <label for="archived" class="font-medium text-gray-900">Archiver ce membre</label>
                                    </div>
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
                                expérience</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Êtes-vous sûr de vouloir supprimer cette award ?
                                    L'expérience sera définitivement supprimée de nos serveurs. Cette action ne peut
                                    être annulée.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button wire:click="deleteSingleModel" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
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
