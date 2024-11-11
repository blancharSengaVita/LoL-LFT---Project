<?php


use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use \App\Models\Conversation;
use \App\Models\LftPost;
use Masmerise\Toaster\Toaster;
use \App\Models\Notification;
use \App\Events\NotificationEvent;

use function Livewire\Volt\layout;
use function Livewire\Volt\{
    state,
    mount,
    computed,
    rules,
};

layout('layouts.dashboard');


state([
    'mobileMenu',
    'lftModal',
    'user',
    'displayed_informations',
    'conversation',
    'jobs',
    'job' => '',
    'myJob',
    'goals',
    'goal' => '',
    'myGoal',
    'ambiances',
    'ambiance' => '',
    'myAmbiance',
    'levels',
    'level' => '',
    'myLevel',
    'description',
    'published',
    'publishedTemp',
    'myLftPost',
    'id',
]);

//rules([
//	'myJob' => 'required',
//	'myAmbiance' => 'required',
//	'myLevel' => 'required',
//	'description' => 'required',
//	'published' => 'required',
//])->messages([
//	'myJob.required' => 'Le champ est obligatoire.',
//	'myAmbiance.required' => 'Le champ est obligatoire.',
//	'myLevel.required' => 'Le champ est obligatoire.',
//	'description.required' => 'Le champ est obligatoire.',
//	'published' => 'required',
//])->attributes([
//
//]);


mount(function () {
    $this->mobileMenu = false;
    $this->lftModal = false;
    $this->jobs = require __DIR__ . '/../../../../app/enum/jobs.php';
    $this->goals = require __DIR__ . '/../../../../app/enum/lookingFors.php';
    $this->ambiances = require __DIR__ . '/../../../../app/enum/ambiances.php';
    $this->levels = require __DIR__ . '/../../../../app/enum/levels.php';
    $this->user = Auth::user();

    $this->myLftPost = $this->user->lftPost()->first();
//    dd($this->myLftPost);

    $this->id = $this->myLftPost->id ?? 0;
    $this->myJob = $this->myLftPost->job ?? '';
    $this->myAmbiance = $this->myLftPost->ambiance ?? '';
    $this->myGoal = $this->myLftPost->goal ?? '';
    $this->description = $this->myLftPost->description ?? '';
    $this->published = $this->myLftPost->published ?? 0;
//    $this->publishedTemp = ;

    if ($this->published === 1) {
        $this->publishedTemp = true;
    } else {
        $this->publishedTemp = false;
    }

});


$lftPosts = computed(function () {
//	$posts = \App\Models\LftPost::where('job', 'like', '%' . $this->job . '%');
    $posts = \App\Models\LftPost::whereHas('user', function ($query) {
        if ($this->job === 'Undefined') {
            $query->where('account_type', 'player');
        } else {
            $query->where('job', 'like', '%' . $this->job . '%');
        }
        $query->where('level', 'like', '%' . $this->level . '%');
    })->where('goal', 'like', '%' . $this->goal . '%')
        ->where('ambiance', 'like', '%' . $this->ambiance . '%')
        ->where('published', true)
        ->get();

    foreach ($posts as $post) {
        $post['user'] = User::find($post->user_id);
        if ($post->user->profil_picture) {
            $post->user['src'] = '/storage/images/1024/' . $post->user->profil_picture;
        } else {
            $post->user['src'] = 'https://ui-avatars.com/api/?length=1&name=' . $post->user->game_name;
        }

        $post->job = $post->job ?: 'Peu importe' ;
        $post->goal = $post->goal ?: 'Peu importe' ;
        $post->ambiance = $post->ambiance ?: 'Peu importe' ;
//        $post->job = $post->job ?: 'Peu importe' ;
//        dd($post->level);
    }

    return $posts;
});

$openMobileMenu = function () {
    $this->mobileMenu = !$this->mobileMenu;
};

$openLFTModal = function () {
    $this->publishedTemp = $this->myLftPost->published ?? 0;

    if ($this->publishedTemp === 1) {
        $this->publishedTemp = true;
    } else {
        $this->publishedTemp = false;
    }
    $this->lftModal = true;
};

$closeLFTModal = function () {
    $this->published = $this->myLftPost->published ?? 0;

    $this->publishedTemp = $this->published;

    if ($this->publishedTemp === 1) {
        $this->publishedTemp = true;
    } else {
        $this->publishedTemp = false;
    }
    $this->lftModal = false;
};

$saveMyLftPost = function () {
//    try {
//        $this->validate();
//    } catch (\Illuminate\Validation\ValidationException $e) {
//        throw $e;
//    }

    $this->published = $this->publishedTemp;

    LftPost::updateOrCreate([
        'user_id' => Auth::id(),
        'id' => $this->id
    ],
        [
            'job' => $this->myJob,
            'ambiance' => $this->myAmbiance,
            'goal' => $this->myGoal,
            'description' => $this->description,
            'published' => $this->published,
        ]);

    $this->lftModal = false;

    if ($this->id === 0) {
        Toaster::success('Post LFT crée avec succès');
    }

    if ($this->id !== 0) {
        Toaster::success('Post LFT modifiée avec succès');
    }

};

$sendLftInvitation = function ($userId) {
    Notification::firstOrCreate([
        'to' => $userId,
        'from' => Auth::id(),
        'description' => 'veut jouer avec toi.',
    ]);
    NotificationEvent::dispatch($userId, Auth::id(), 'veut jouer avec toi.');
    Toaster::success('Demande envoyé');
};

$newConversation = function ($userId) {
    $this->conversation = Conversation::where(function ($query) use ($userId) {
        $query->where('user_one_id', Auth::id())
            ->where('user_two_id', $userId);
    })->orWhere(function ($query) use ($userId) {
        $query->where('user_one_id', $userId)
            ->where('user_two_id', Auth::id());
    })->first();

    if (!$this->conversation) {
        $this->conversation = Conversation::create([
            'user_one_id' => Auth::id(),
            'user_two_id' => $userId,
        ]);
    }

    $this->redirect(route('conversation', ['conversation' => $this->conversation->id], absolute: false), navigate: true);
};
?>

<main class="lg:pl-72 h-full"
      x-data="{
      lftModal: $wire.entangle('lftModal'),
        open: $wire.entangle('mobileMenu'),
         }"
>
    <x-slot name="h1">
        {{ $user->game_name }}
    </x-slot>
    <section class="h-full">
        <h2 class="sr-only">
            Recherche de partenaire
        </h2>
        <!--
        This example requires updating your template:

        ```
        <html class="h-full bg-white">

        ```
        -->
        <!-- Static sidebar for desktop -->

        <div class="xl:pr-96 h-full flex flex-col items-stretch">
            {{--            h-full justify-center items-center--}}
            <!-- Main area -->
            <!--
              When the mobile menu is open, add `overflow-hidden` to the `body` element to prevent double scrollbars

              Open: "fixed inset-0 z-40 overflow-y-auto", Closed: ""
            -->
            <livewire:partials.dashboard-header/>
            <!--
  This example requires some changes to your config:

  ```
  // tailwind.config.js
  module.exports = {
    // ...
    plugins: [
      // ...
      require('@tailwindcss/forms'),
    ],
  }
  ```
-->
            <div class="bg-gray-50">
                <!--
                  Mobile filter dialog

                  Off-canvas menu for mobile, show/hide based on off-canvas menu state.
                -->

                {{--                MOBILE MENU FILTER--}}
                <div x-cloak x-show="open" class="relative z-40 sm:hidden" role="dialog" aria-modal="true">
                    <!--
                      Off-canvas menu backdrop, show/hide based on off-canvas menu state.

                      Entering: "transition-opacity ease-linear duration-300"
                        From: "opacity-0"
                        To: "opacity-100"
                      Leaving: "transition-opacity ease-linear duration-300"
                        From: "opacity-100"
                        To: "opacity-0"
                    -->
                    <div class="fixed inset-0 bg-black bg-opacity-25" aria-hidden="true"></div>

                    <div class="fixed inset-0 z-40 flex">
                        <!--
                          Off-canvas menu, show/hide based on off-canvas menu state.

                          Entering: "transition ease-in-out duration-300 transform"
                            From: "translate-x-full"
                            To: "translate-x-0"
                          Leaving: "transition ease-in-out duration-300 transform"
                            From: "translate-x-0"
                            To: "translate-x-full"
                        -->
                        <div @click.away="$wire.openMobileMenu" class="relative ml-auto flex h-full w-full max-w-xs flex-col overflow-y-auto bg-white py-4 pb-6 shadow-xl">
                            <div class="flex items-center justify-between px-4">
                                <h2 class="text-lg font-medium text-gray-900">Filtre</h2>
                                <button wire:click="openMobileMenu" type="button" class="-mr-2 flex h-10 w-10 items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <span class="sr-only">Close menu</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Filters -->
                            <form class="mt-4 ">
                                <div class="border-t border-gray-200 px-4 py-6">
                                    <h3 class="-mx-2 -my-3 flow-root">
                                        <!-- Expand/collapse question button -->
                                        <div class="flex flex-col w-full bg-white px-2 py-3 text-sm text-gray-400" aria-controls="filter-section-0" aria-expanded="false">
                                            <label for="job" class="block text-sm font-medium leading-6 text-gray-900">
                                                Recherche un/une
                                            </label>
                                            <select wire:model.live="job" id="job" name="job" class="text-base w-10 mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                <option value="">Peu importe</option>
                                                <optgroup label="JOUEUR">
                                                    @foreach($jobs['player'] as $job)
                                                        <option value="{{ $job }}">{{ __('jobs.'.$job) }}</option>
                                                    @endforeach
                                                </optgroup>
                                                <optgroup label="STAFF">
                                                    @foreach($jobs['staff'] as $job)
                                                        <option value="{{ $job }}">{{ __('jobs.'.$job) }}</option>
                                                    @endforeach
                                                </optgroup>
                                                <optgroup label="ÉQUIPE">
                                                    @foreach($jobs['team'] as $job)
                                                        <option value="{{ $job }}">{{ __('jobs.'.$job) }}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>
                                    </h3>
                                </div>
                                <div class="border-t border-gray-200 px-4 py-6">
                                    <h3 class="-mx-2 -my-3 flow-root">
                                        <!-- Expand/collapse question button -->
                                        <div class="flex flex-col w-full bg-white px-2 py-3 text-sm text-gray-400" aria-controls="filter-section-0" aria-expanded="false">
                                            <label for="level" class="block text-sm font-medium leading-6 text-gray-900">
                                                Niveau
                                            </label>
                                            <select wire:model.live="level" id="level" name="level" class="w-10 mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                <option value="">Peu importe</option>
                                                @foreach($levels as $level)
                                                    <option value="{{ $level }}">{{ __('levels.'.$level) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </h3>
                                </div>
                                <div class="border-t border-gray-200 px-4 py-6">
                                    <h3 class="-mx-2 -my-3 flow-root">
                                        <!-- Expand/collapse question button -->
                                        <div class="flex flex-col w-full bg-white px-2 py-3 text-sm text-gray-400" aria-controls="filter-section-0" aria-expanded="false">
                                            <div class="relative inline-block text-left">
                                                <label for="goal" class="block text-sm font-medium leading-6 text-gray-900">
                                                    Pour
                                                </label>
                                                <select wire:model.live="goal" id="goal" name="goal" class="w-10 mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                    <option value="">Peu importe</option>
                                                    @foreach($goals['team'] as $goal)
                                                        <option value="{{ $goal }}">{{ __('lookingFors.'.$goal) }}</option>
                                                    @endforeach
                                                    @foreach($goals['duo'] as $goal)
                                                        <option value="{{ $goal }}">{{ __('lookingFors.'.$goal) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </h3>
                                </div>
                                <div class="border-t border-gray-200 px-4 py-6">
                                    <h3 class="-mx-2 -my-3 flow-root">
                                        <!-- Expand/collapse question button -->
                                        <div class="flex flex-col w-full bg-white px-2 py-3 text-sm text-gray-400" aria-controls="filter-section-0" aria-expanded="false">
                                            <label for="ambiance" class="block text-sm font-medium leading-6 text-gray-900">
                                                Ambiance
                                            </label>
                                            <select wire:model.live="ambiance" id="ambiance" name="ambiance" class="w-10 mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                <option value="">Peu importe</option>
                                                @foreach($ambiances as $ambiance)
                                                    <option value="{{ $ambiance }}">{{ __('ambiances.'.$ambiance) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </h3>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{--                FILTERS --}}
                <div class="bg-white border-t mx-auto px-4 text-center sm:px-6 lg:max-w-7xl lg:px-8">
                    <section aria-labelledby="filter-heading" class="border-gray-200 py-3">
                        <h2 id="filter-heading" class="sr-only">Product filters</h2>

                        <div class="flex items-center justify-between">
                            <div class="relative inline-block text-left">
                                <div>
                                    <button wire:click="openLFTModal" type="button" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                        Mon post LFT
                                    </button>
                                </div>
                            </div>
                            <!-- Mobile filter dialog toggle, controls the 'mobileFilterDialogOpen' state. -->
                            <button wire:click="openMobileMenu" type="button" class="inline-block text-sm font-medium text-gray-700 hover:text-gray-900 md:hidden">
                                Filtres
                            </button>

                            <div class="hidden md:flex md:items-baseline md:space-x-2">
                                <div class="relative inline-block text-left">
                                    <label for="job" class="block text-sm font-medium leading-6 text-gray-900">
                                        Recherche un/une
                                    </label>
                                    <select wire:model.live="job" id="job" name="job" class="w-10 mt-2 block w-32 rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="">Peu importe</option>
                                        <optgroup label="JOUEUR">
                                            @foreach($jobs['player'] as $job)
                                                <option value="{{ $job }}">{{ __('jobs.'.$job) }}</option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="STAFF">
                                            @foreach($jobs['staff'] as $job)
                                                <option value="{{ $job }}">{{ __('jobs.'.$job) }}</option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="ÉQUIPE">
                                            @foreach($jobs['team'] as $job)
                                                <option value="{{ $job }}">{{ __('jobs.'.$job) }}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="relative inline-block text-left">
                                    <label for="level" class="block text-sm font-medium leading-6 text-gray-900">
                                        Niveau
                                    </label>
                                    <select wire:model.live="level" id="level" name="level" class="w-10 mt-2 block w-32 rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="">Peu importe</option>
                                        @foreach($levels as $level)
                                            <option value="{{ $level }}">{{ __('levels.'.$level) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="relative inline-block text-left">
                                    <label for="goal" class="block text-sm font-medium leading-6 text-gray-900">
                                        Pour
                                    </label>
                                    <select wire:model.live="goal" id="goal" name="goal" class="w-10 mt-2 block w-32 rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="">Peu importe</option>
                                        @foreach($goals['team'] as $goal)
                                            <option value="{{ $goal }}">{{ __('lookingFors.'.$goal) }}</option>
                                        @endforeach
                                        @foreach($goals['duo'] as $goal)
                                            <option value="{{ $goal }}">{{ __('lookingFors.'.$goal) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="relative inline-block text-left">
                                    <label for="ambiance" class="block text-sm font-medium leading-6 text-gray-900">
                                        Ambiance
                                    </label>
                                    <select wire:model.live="ambiance" id="ambiance" name="ambiance" class="w-10 mt-2 block w-32 rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="">Peu importe</option>
                                        @foreach($ambiances as $ambiance)
                                            <option value="{{ $ambiance }}">{{ __('ambiances.'.$ambiance) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <article>
                <div class="border-y border-gray-200 bg-white ">
                    {{--                    <div class="flex justify-between gap-x-4 pb-1 items-center sm:flex-nowrap">--}}
                    {{--                        <h3 class="text-base font-semibold leading-6 text-gray-900">{{'Expérience'}}</h3>--}}

                    {{--                    </div>--}}
                    <div class=" sm:w-12/12 divide-y divide-gray-100">
                        @if(!count($this->lftPosts))
                            <p class="px-4 py-4"> Aucun resultat
                            </p>
                        @endif
                        <ul role="list" class="divide-y divide-gray-100">
                            @foreach($this->lftPosts as $post)
                                <div class="">
                                    <li class="flex gap-x-4 w-full py-4 px-4" wire:key="{{ $post->id }}">
                                        {{--                                    <div class="h-14 w-14 flex justify-center items-center bg-indigo-600">--}}
                                        <a class=" h-12 w-12 flex-none rounded-full" href="{{route('user', ['user' => $post->user->username])}}" title="aller vers la page de {{$post->user->game_name}}">
                                            <img class="h-12 w-12 flex-none rounded-full bg-gray-50" src="{{$post->user->src}}" alt="photo de profile de {{$post->user->game_name}}">
                                        </a>
                                        {{--                                        {{$post}}--}}
                                        <div class="w-full">
                                            <a class="hover:underline" href="{{route('user', ['user' => $post->user->username])}}" title="aller vers la page de {{$post->user->game_name}}">
                                                <span class="text-base font-medium leading-6 text-gray-900  ">{{$post->user->game_name}} </span>
                                            </a>

                                            <p class="truncate font-sans text-sm leading-5 text-gray-900 lg:mb-4 mb-4">{{$post->user->job}}
                                                · {{$post->user->level}}</p>
                                            <dl class="divide-y divide-gray-100">
                                                <div class="  pb-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                    <dt class="text-sm font-medium leading-6 text-gray-900">Recherche
                                                        un/une
                                                    </dt>
                                                    <dd class="lg:mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $post->job}}</dd>
                                                </div>
                                                <div class=" py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                    <dt class="text-sm font-medium leading-6 text-gray-900">Pour</dt>
                                                    <dd class="lg:mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{$post->goal}}</dd>
                                                </div>
                                                <div class=" py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                    <dt class="text-sm font-medium leading-6 text-gray-900">Ambiance
                                                    </dt>
                                                    <dd class="lg:mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{$post->ambiance}}</dd>
                                                </div>
                                                <div class=" py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                    <dt class="text-sm font-medium leading-6 text-gray-900">
                                                        Description
                                                    </dt>
                                                    <dd class="lg:mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{$post->description}}</dd>
                                                </div>
                                            </dl>
                                            <div class="my-2 flex justify-end gap-x-2">
                                                <button wire:click="sendLftInvitation({{$post->user->id}})" type="button" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                                    Demande LFT
                                                </button>
                                                <button wire:click="newConversation({{$post->user->id}})" type="button" class="rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                                    Message
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                </div>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </article>
            {{-- MODAL SETTINGS DE LA SECTION  --}}
            <div x-cloak x-show="lftModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                        <div @click.away="lftModal = false" class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                            <form action="" wire:submit="saveMyLftPost">
                                <div class="sm:flex sm:items-start">
                                    <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                                        <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                            Mon post LFT</h3>
                                        <div class="flex gap-x-4">
                                            <div class="mt-4">
                                                <label for="myJob" class="block text-sm font-medium leading-6 text-gray-900">Recherche
                                                    un/une</label>
                                                <select wire:model.live="myJob" id="myJob" name="myJob" class="w-10 mt-2 block w-32 rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                    <option value="">Peu importe</option>
                                                    <optgroup label="JOUEUR">
                                                        @foreach($jobs['player'] as $job)
                                                            <option value="{{ $job }}">{{ __('jobs.'.$job) }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="STAFF">
                                                        @foreach($jobs['staff'] as $job)
                                                            <option value="{{ $job }}">{{ __('jobs.'.$job) }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="ÉQUIPE">
                                                        @foreach($jobs['team'] as $job)
                                                            <option value="{{ $job }}">{{ __('jobs.'.$job) }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                                @if ($messages = $errors->get('myJob'))
                                                    <div class="text-sm text-red-600 space-y-1 mt-2">
                                                        <p>{{$messages[0]}}</p>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="mt-4">
                                                <label for="myGoal" class="block text-sm font-medium leading-6 text-gray-900">Pour</label>
                                                <select wire:model.live="myGoal" id="myGoal" name="myGoal" class="w-10 mt-2 block w-32 rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                    <option value="">Peu importe</option>
                                                    @foreach($goals['team'] as $goal)
                                                        <option value="{{ $goal }}">{{ __('lookingFors.'.$goal) }}</option>
                                                    @endforeach
                                                    @foreach($goals['duo'] as $goal)
                                                        <option value="{{ $goal }}">{{ __('lookingFors.'.$goal) }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($messages = $errors->get('myGoal'))
                                                    <div class="text-sm text-red-600 space-y-1 mt-2">
                                                        <p>{{$messages[0]}}</p>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="mt-4">
                                                <label for="myAmbiance" class="block text-sm font-medium leading-6 text-gray-900">Ambiance</label>
                                                <select wire:model.live="myAmbiance" id="myAmbiance" name="myAmbiance" class="w-10 mt-2 block w-32 rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                    <option value="">Peu importe</option>
                                                    @foreach($ambiances as $ambiance)
                                                        <option value="{{ $ambiance }}">{{ __('ambiances.'.$ambiance) }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($messages = $errors->get('myAmbiance'))
                                                    <div class="text-sm text-red-600 space-y-1 mt-2">
                                                        <p>{{$messages[0]}}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                                            <div class="mt-2">
                                                <textarea wire:model="description" id="description" name="description" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                            </div>
                                            @error('description')
                                            <p class="text-sm text-red-600 space-y-1 mt-2"> {{ $message }}</p>
                                            @enderror
                                            <p class="mt-1 text-sm leading-6 text-gray-600">Écris quelques phrases à
                                                propos de ton post</p>
                                        </div>
                                        <div class="mt-5 relative flex items-start">
                                            <div class="flex h-6 items-center">
                                                <input wire:model="publishedTemp" id="displayed" aria-describedby="offers-description" name="offers" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 checked:">
                                            </div>
                                            <div class="ml-3 text-sm leading-6">
                                                <label for="displayed" class="font-medium text-gray-900">Publier mon
                                                    post</label>
                                            </div>
                                        </div>
                                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                            <button type="submit" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:ml-3">
                                                sauvegarder
                                            </button>
                                            <button @click="lftModal = false" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500  sm:w-auto">
                                                Annuler
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>






