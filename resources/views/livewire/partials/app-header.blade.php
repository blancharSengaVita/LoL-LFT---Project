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
    'title',
]);

mount(function (string $title) {
	$this->$title = $title;
    $this->mobileMenu = false;
    $this->user = Auth::user();

    if ($this->user->profil_picture) {
        $this->profilePictureSource = '/storage/images/1024/' . $this->user->profil_picture;
    } else {
        $this->profilePictureSource = 'https://ui-avatars.com/api/?length=1&name=' . $this->user->game_name;
    }
});

$openMobileMenu = function () {
    $this->mobileMenu = !$this->mobileMenu;
    $this->dispatch('openMobileMenu');

};

$logout = function (Logout $logout) {
    $logout();

    $this->redirect('/', navigate: true);
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
                    <h2 class="text-lg font-medium" > {{$title}} </h2>
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
                        <a href="{{route('dashboard')}}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-0">Dashboard</a>
                        <a href="{{route('settings')}}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-1">Paramètres</a>
                        <boutton wire:click="logout" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-2">Se déconnecter</boutton>
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
                <a href="{{route('dashboard')}}"  class="{{ Route::is('dashboard') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }} {{ Route::is('dashboard') ? 'bg-gray-50 text-indigo-600' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50' }} rounded-md py-2 px-3 text-base font-medium">Dashboard</a>
                <a href="{{route('find-teammate')}}" class="{{ Route::is('find-teammate') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }} block rounded-md py-2 px-3 text-base font-medium">Recherche de partenaires</a>
                <a href="{{route('messages')}}" class="{{ Route::is('messages') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }} block rounded-md py-2 px-3 text-base font-medium">Messages</a>
                <a href="{{route('notifications')}}" class="{{ Route::is('notifications') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }} block rounded-md py-2 px-3 text-base font-medium">Notifications</a>
                <a href="{{route('missions')}}" class="{{ Route::is('missions') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }} block rounded-md py-2 px-3 text-base font-medium">Missions</a>
                <a href="{{route('settings')}}" class="{{ Route::is('settings') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }} block rounded-md py-2 px-3 text-base font-medium">Paramètres</a>

{{--                <a href="{{route('dashboard')}}" aria-current="page" class=" bg-gray-100 text-gray-900 block rounded-md py-2 px-3 text-base font-medium">Dashboard</a>--}}
{{--                <a href="#" class="block rounded-md py-2 px-3 text-base font-medium">Calendar</a>--}}
{{--                <a href="#" class="block rounded-md py-2 px-3 text-base font-medium">Teams</a>--}}
{{--                <a href="#" class="block rounded-md py-2 px-3 text-base font-medium">Directory</a>--}}
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
                    <a href="{{route('dashboard')}}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-900">Profil</a>
                    <a href="{{route('settings')}}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-900">Paramètres</a>
                    <button wire:click="logout" class="block rounded-md px-3 py-2 text-base font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-900">Se déconnecter</button>
                </div>
            </div>
        </nav>
    </div>
</header>
