<?php

use App\Livewire\Actions\Logout;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use  \Illuminate\Support\Facades\Route;
use function Livewire\Volt\{
    state,
    mount,
    computed,
    boot,
};

state([
    'mobileMenu',
    'search' => '',
    'profilePictureSource',
    'route' => request()->url(),
    'user',
]);

mount(function () {
    $this->mobileMenu = false;
    $this->user = Auth::user();

    if ($this->user->profil_picture) {
        $this->profilePictureSource = '/storage/images/1024/' . $this->user->profil_picture;
    } else {
        $this->profilePictureSource = 'https://ui-avatars.com/api/?length=1&name=' . $this->user->game_name;
    }
});


$filteredUser = computed(function () {
    $results = User::where('username', 'like', '%' . $this->search . '%')
        ->orWhere('game_name', 'like', '%' . $this->search . '%')
        ->limit(4)
        ->get();

    foreach ($results as $result) {
        if ($result->profil_picture) {
            $result['src'] = '/storage/images/1024/' . $result->profil_picture;
        } else {
            $result['src'] = 'https://ui-avatars.com/api/?length=1&name=' . $result->game_name;
        }
    }

    return $results;
});

$openMobileMenu = function () {
    $this->mobileMenu = !$this->mobileMenu;
    $this->dispatch('openMobileMenu');
};

$logout = function (Logout $logout) {
    $logout();

    $this->redirect(\route('login'), navigate: true);
};
?>

<header class="bg-white shadow-sm lg:static lg:overflow-y-visible"
        x-data="{
        open: $wire.entangle('mobileMenu'),
        openMenuDropdown: false,
         }"
        :class=" open ? 'fixed inset-0 z-40 overflow-y-auto' : ''"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="relative flex justify-between lg:gap-8 xl:grid xl:grid-cols-12">
            <div class="min-w-0 flex-1 md:px-8 lg:px-0 xl:col-span-6">
                <div class="flex items-center px-6 py-4 md:mx-auto md:max-w-3xl lg:mx-0 lg:max-w-none xl:px-0">
                    <div class="w-full">
                        <label for="search" class="sr-only">Search</label>
                        <div class="relative"
                             x-data="{
                                                    isFocused: false,
                                                    blurTimeout: null
                                                    }"
                        >
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <input
                                autocomplete="off"
                                @focus="clearTimeout(blurTimeout); isFocused = true"
                                @blur="blurTimeout = setTimeout(() => { isFocused = false }, 200)"
                                wire:model.live="search"
                                id="search" name="search" class="block w-full rounded-md border-0 bg-white py-1.5 pl-10 pr-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6" placeholder="Search" type="search">
                            <ul
                                x-data="{
                                                    searchValue: $wire.entangle('search'),
                                                    }"
                                x-cloak x-show="searchValue && isFocused"
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
                                        {{--                                                            wire:click="sendNotification"--}}
                                        x-data="{ isHovered: false }"
                                        @mouseenter="isHovered = true"
                                        @mouseleave="isHovered = false"
                                        :class="isHovered ? 'text-white bg-indigo-600' : 'text-gray-900'"
                                        class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900" id="option-0" role="option" tabindex="-1"
                                    >
                                        <a wire:navigate class="flex items-center" href="{{route('user', ['user' => $player->username])}}" title="voir la page de {{$player->game_name}}">
                                            <img src="{{$player->src}}" alt="" class="h-10 w-10 flex-shrink-0 rounded-full">
                                            <!-- Selected: "font-semibold" -->
                                            <span class="ml-3 truncate">{{ $player->game_name }}</span>
                                            <span :class="isHovered ? 'text-indigo-200' : 'text-gray-500'" class="ml-2 truncate text-gray-500">{{ $player->username }}</span>
                                        </a>

                                        <!--
                                          Checkmark, only display for selected option.

                                          Active: "text-white", Not Active: "text-indigo-600"
                                        -->
                                        {{--                                        <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600">--}}
                                        {{--                                            <svg x-show="isHovered" class="h-5 w-5 text-indigo-500 " viewBox="0 0 24 24" stroke-width="2px" stroke="white" fill="none" aria-hidden="true">--}}
                                        {{--                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/>--}}
                                        {{--                                            </svg>--}}
                                        {{--                                        </span>--}}
                                    </li>
                                @endforeach


                                <!-- More items... -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center md:absolute md:inset-y-0 md:right-0 lg:hidden">
                <!-- Mobile menu button -->
                <button wire:click="openMobileMenu" type="button" class="relative -mx-2 inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-expanded="false">
                    <span class="absolute -inset-0.5"></span>
                    <span class="sr-only">Open menu</span>
                    <!--
                      Icon when menu is closed.

                      Menu open: "hidden", Menu closed: "block"
                    -->
                    <svg :class="open ?  'hidden' : 'block'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                    <!--
                      Icon when menu is open.

                      Menu open: "block", Menu closed: "hidden"
                    -->
                    <svg :class="open ? 'block' : 'hidden' " class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="hidden lg:flex lg:items-center lg:justify-end xl:col-span-6">
{{--                <button type="button" class="relative ml-5 flex-shrink-0 rounded-full bg-white p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">--}}
{{--                    <span class="absolute -inset-1.5"></span>--}}
{{--                    <span class="sr-only">View notifications</span>--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6" aria-hidden="true">--}}
{{--                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/>--}}
{{--                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>--}}
{{--                    </svg>--}}
{{--                </button>--}}

                <!-- Profile dropdown -->
                <div class="relative ml-5 flex-shrink-0">
                    <div>
                        <button @click="openMenuDropdown = !openMenuDropdown" type="button" class="relative flex rounded-full bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                            <span class="absolute -inset-1.5"></span>
                            <span class="sr-only">Open user menu</span>
                            <img class="h-8 w-8 rounded-full" src="{{$profilePictureSource}}" alt="">
                        </button>
                    </div>

                    <!--
                      Dropdown menu, show/hide based on menu state.

                      Entering: "transition ease-out duration-100"
                        From: "transform opacity-0 scale-95"
                        To: "transform opacity-100 scale-100"
                      Leaving: "transition ease-in duration-75"
                        From: "transform opacity-100 scale-100"
                        To: "transform opacity-0 scale-95"
                    -->
                    <div x-cloak x-show="openMenuDropdown" @click.away="openMenuDropdown = false" class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                        <!-- Active: "bg-gray-100", Not Active: "" -->
                        <a wire:navigate href="{{route('dashboard')}}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-0">Dashboard</a>
                        <a wire:navigate href="{{route('settings')}}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-1 cursor-pointer">Paramètres</a>
                        <a wire:click="logout" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-0">Se déconnecter</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state. -->
        <nav x-cloak x-show='open' class="lg:hidden" aria-label="Global">
            <h2 class="sr-only">
                Menu de navigation principal
            </h2>
            <div class="mx-auto max-w-3xl space-y-1 px-2 pb-3 pt-2 sm:px-4">
                <!-- Current: "bg-gray-100 text-gray-900", Default: "hover:bg-gray-50" -->
                <a wire:navigate href="{{route('dashboard')}}"  class="{{ Route::is('dashboard') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }} block rounded-md py-2 px-3 text-base font-medium">Dashboard</a>

                <a wire:navigate href="{{route('find-teammate')}}" class="{{ Route::is('find-teammate') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }} block rounded-md py-2 px-3 text-base font-medium">Recherche de partenaires</a>
                <a wire:navigate href="{{route('messages')}}" class="{{ Route::is('messages') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }} block rounded-md py-2 px-3 text-base font-medium">Messages</a>
                <a wire:navigate href="{{route('notifications')}}" class="{{ Route::is('notifications') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }} block rounded-md py-2 px-3 text-base font-medium">Notifications</a>
                <a wire:navigate href="{{route('missions')}}" class="{{ Route::is('missions') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }} block rounded-md py-2 px-3 text-base font-medium">Missions</a>
                <a wire:navigate href="{{route('settings')}}" class="{{ Route::is('settings') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }} block rounded-md py-2 px-3 text-base font-medium">Paramètres</a>

{{--                <a wire:navigate href="{{route('dashboard')}}" aria-current="page" class=" bg-gray-100 text-gray-900 block rounded-md py-2 px-3 text-base font-medium">Dashboard</a>--}}
{{--                <a wire:navigate href="#" class="block rounded-md py-2 px-3 text-base font-medium">Calendar</a>--}}
{{--                <a wire:navigate href="#" class="block rounded-md py-2 px-3 text-base font-medium">Teams</a>--}}
{{--                <a wire:navigate href="#" class="block rounded-md py-2 px-3 text-base font-medium">Directory</a>--}}
            </div>
            <div class="border-t border-gray-200 pb-3 pt-4">
                <div class="mx-auto flex max-w-3xl items-center px-4 sm:px-6">
                    <div class="flex-shrink-0">
                        <img class="h-10 w-10 rounded-full" src="{{$profilePictureSource}}" alt="">
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-800">{{ $user->game_name }}</div>
                        <div class="text-sm font-medium text-gray-500">{{ $user->username }}</div>
                    </div>
                </div>
                <div class="mx-auto mt-3 max-w-3xl space-y-1 px-2 sm:px-4">
                    <a wire:navigate href="{{route('dashboard')}}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-900">Profil</a>
                    <a wire:navigate href="{{route('settings')}}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-900">Paramètres</a>
                    <button wire:click="logout" class="block rounded-md px-3 py-2 text-base font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-900 cursor-pointer">Se déconnecter</button>
                </div>
            </div>
        </nav>
    </div>
</header>
