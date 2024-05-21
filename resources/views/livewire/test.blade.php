<?php

use App\Livewire\Actions\Logout;

use function Livewire\Volt\layout;
use function Livewire\Volt\{
    state,
    on,
};

layout('layouts.dashboard');

state([
    'openMobileMenu' => false,
]);

on(['openMobileMenu' => function () {
    $this->openMobileMenu = !$this->openMobileMenu;
}]);

$logout = function (Logout $logout) {
    $logout();

    $this->redirect('/', navigate: true);
};

?>

{{--    <x-slot name="h1">--}}
{{--        Dashboard--}}
{{--    </x-slot>--}}
<!--
    This example requires updating your template:

    ```
    <html class="h-full bg-white">

    ```
    -->
<!-- Static sidebar for desktop -->


<main class="lg:pl-72"
      x-data="{
    openMobileMenu: $wire.entangle('openMobileMenu'),
    openDropdownMenu: false,
      }">
    <div class="xl:pr-96">

        <!-- Main area -->
        <!--
          When the mobile menu is open, add `overflow-hidden` to the `body` element to prevent double scrollbars

          Open: "fixed inset-0 z-40 overflow-y-auto", Closed: ""
        -->
        <livewire:partials.dashboard-header/>
        <section class="divide-y divide-gray-200 border-b border-gray-200">
            <div class="pb-6 bg-white">
                <div class="h-24 bg-indigo-700 sm:h-20 lg:h-28"></div>
                <div class="-mt-12 flow-root px-4 sm:-mt-8 sm:flex sm:items-end sm:px-6 lg:-mt-16">
                    <div>
                        <div class="-m-1 flex">
                            <div class="inline-flex overflow-hidden rounded-lg border-4 border-white">
                                <img class="h-24 w-24 flex-shrink-0 sm:h-40 sm:w-40 lg:h-48 lg:w-48" src="https://images.unsplash.com/photo-1501031170107-cfd33f0cbdcc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=256&h=256&q=80" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 sm:ml-6 sm:flex-1">
                        <div>
                            <div class="flex items-center">
                                <p class="text-xl font-bold text-gray-900 sm:text-2xl">Ashley Porter</p>
                                <span class="ml-2.5 inline-block h-2 w-2 flex-shrink-0 rounded-full bg-green-400">
                                    <span class="sr-only">Online</span>
                                </span>
                            </div>
                            <p class="text-sm text-gray-500">@ashleyporter</p>
                        </div>
                        <div class="mt-5 flex flex-wrap space-y-3 sm:space-x-3 sm:space-y-0">
                            <button type="button" class="inline-flex w-full flex-shrink-0 items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:flex-1">
                                Message
                            </button>
                            <button type="button" class="inline-flex w-full flex-1 items-center justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                Call
                            </button>
                            <div class="ml-3 inline-flex sm:ml-0">
                                <div class="relative inline-block text-left">
                                    <button @click="openDropdownMenu = !openDropdownMenu" type="button" class="relative inline-flex items-center rounded-md bg-white p-2 text-gray-400 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50" id="options-menu-button" aria-expanded="false" aria-haspopup="true">
                                        <span class="absolute -inset-1"></span>
                                        <span class="sr-only">Open options menu</span>
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"/>
                                        </svg>
                                    </button>
                                    <!--
                                      Dropdown panel, show/hide based on dropdown state.

                                      Entering: "transition ease-out duration-100"
                                        From: "transform opacity-0 scale-95"
                                        To: "transform opacity-100 scale-100"
                                      Leaving: "transition ease-in duration-75"
                                        From: "transform opacity-100 scale-100"
                                        To: "transform opacity-0 scale-95"
                                    -->

                                    <div x-show="openDropdownMenu" @click.away="openDropdownMenu = false" class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="options-menu-button" tabindex="-1">
                                        <div class="py-1" role="none">
                                            <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                                            <a href="#" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="options-menu-item-0">View
                                                profile</a>
                                            <a href="#" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="options-menu-item-1">Copy
                                                profile link</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--                    une façon d'afficher des information--}}
            {{--                    <div class="px-4 py-5 sm:px-0 sm:py-0">--}}
            {{--                        <dl class="space-y-8 sm:space-y-0 sm:divide-y sm:divide-gray-200">--}}
            {{--                            <div class="sm:flex sm:px-6 sm:py-5">--}}
            {{--                                <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">Bio</dt>--}}
            {{--                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:ml-6 sm:mt-0">--}}
            {{--                                    <p>Enim feugiat ut ipsum, neque ut. Tristique mi id elementum praesent. Gravida in--}}
            {{--                                        tempus feugiat netus enim aliquet a, quam scelerisque. Dictumst in convallis nec--}}
            {{--                                        in bibendum aenean arcu.</p>--}}
            {{--                                </dd>--}}
            {{--                            </div>--}}
            {{--                            <div class="sm:flex sm:px-6 sm:py-5">--}}
            {{--                                <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">--}}
            {{--                                    Location--}}
            {{--                                </dt>--}}
            {{--                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:ml-6 sm:mt-0">New York, NY, USA--}}
            {{--                                </dd>--}}
            {{--                            </div>--}}
            {{--                            <div class="sm:flex sm:px-6 sm:py-5">--}}
            {{--                                <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">Website--}}
            {{--                                </dt>--}}
            {{--                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:ml-6 sm:mt-0">ashleyporter.com--}}
            {{--                                </dd>--}}
            {{--                            </div>--}}
            {{--                            <div class="sm:flex sm:px-6 sm:py-5">--}}
            {{--                                <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">--}}
            {{--                                    Birthday--}}
            {{--                                </dt>--}}
            {{--                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:ml-6 sm:mt-0">--}}
            {{--                                    <time datetime="1982-06-23">June 23, 1982</time>--}}
            {{--                                </dd>--}}
            {{--                            </div>--}}
            {{--                        </dl>--}}
            {{--                    </div>--}}
        </section>
    </div>
</main>
