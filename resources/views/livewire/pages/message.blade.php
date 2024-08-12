<?php

use function Livewire\Volt\layout;
use function Livewire\Volt\{
    state,
};

layout('layouts.message');

?>
<main class="lg:pl-72 h-full">
    <x-slot name="h1">
        {{ 'salut' }}
    </x-slot>
    <!-- This is an example component -->
    <div class="container mx-auto shadow-lg rounded-lg min-h-screen flex flex-col">
        <!-- headaer -->
        <header class="px-5 py-5 flex justify-between items-center bg-white border-b border-gray-200">
            <div class="font-medium text-xl">Message</div>
        </header>
        <!-- end header -->
        <!-- Chatting -->
        <div class="h-full flex flex-row flex-grow justify-between bg-white">
            <!-- chat list -->
            <div class="flex flex-col w-2/5 border-r overflow-y-auto">
                <!-- search compt -->
                {{--                <div class="h-max border-b py-4 px-2">--}}
                {{--                    <input--}}
                {{--                        type="text"--}}
                {{--                        placeholder="search chatting"--}}
                {{--                        class="--}}
                {{--                        ring--}}
                {{--                        ring-inset--}}
                {{--                        ring-gray-300 --}}
                {{--                        focus:ring--}}
                {{--                        focus:ring-inset--}}
                {{--                         focus:ring-indigo-500--}}
                {{--                        text-sm py-2 px-2 border border-gray-200 rounded w-full"--}}
                {{--                    />--}}
                {{--                </div>--}}
                <!-- end search compt -->
                <!-- user list -->
                <ul class="flex flex-col">
                    <li class="flex flex-row py-3 px-2 items-center border-l-4 bg border-indigo-600 bg-gray-50">
                        <div class="w-1/4">
                            <img src="https://ui-avatars.com/api/?length=1&name=SS" class="object-cover h-10 w-10 rounded-full" alt=""
                            />
                        </div>
                        <div class="w-full">
                            <p class="text-sm font-semibold">MERN Stack</p>
                            <p class="text-sm text-gray-500 truncate">Lusi : Thanks Everyone</p>
                        </div>
                    </li>
                    <li class="flex flex-row py-3 px-2 items-center border-indigo-600">
                        <div class="w-1/4">
                            <img src="https://ui-avatars.com/api/?length=1&name=SS" class="object-cover h-10 w-10 rounded-full" alt=""
                            />
                        </div>
                        <div class="w-full">
                            <p class="text-sm font-semibold">Luis</p>
                            <p class="text-sm text-gray-500 truncate">Salut comment ça va bien ?</p>
                        </div>
                    </li>
                    <li class="flex flex-row py-3 px-2 items-center border-indigo-600">
                        <div class="w-1/4">
                            <img src="https://ui-avatars.com/api/?length=1&name=SS" class="object-cover h-10 w-10 rounded-full" alt=""
                            />
                        </div>
                        <div class="w-full">
                            <p class="text-sm font-semibold">Luis</p>
                            <p class="text-sm text-gray-500 truncate">Salut comment ça va bien ?</p>
                        </div>
                    </li>
                    <li class="flex flex-row py-3 px-2 items-center border-indigo-600">
                        <div class="w-1/4">
                            <img src="https://ui-avatars.com/api/?length=1&name=SS" class="object-cover h-10 w-10 rounded-full" alt=""
                            />
                        </div>
                        <div class="w-full">
                            <p class="text-sm font-semibold">Luis</p>
                            <p class="text-sm text-gray-500 truncate">Salut comment ça va bien ?</p>
                        </div>
                    </li>
                    <li class="flex flex-row py-3 px-2 items-center border-indigo-600">
                        <div class="w-1/4">
                            <img src="https://ui-avatars.com/api/?length=1&name=SS" class="object-cover h-10 w-10 rounded-full" alt=""
                            />
                        </div>
                        <div class="w-full">
                            <p class="text-sm font-semibold">Luis</p>
                            <p class="text-sm text-gray-500 truncate">Salut comment ça va bien ?</p>
                        </div>
                    </li>
                    <li class="flex flex-row py-3 px-2 items-center border-indigo-600">
                        <div class="w-1/4">
                            <img src="https://ui-avatars.com/api/?length=1&name=SS" class="object-cover h-10 w-10 rounded-full" alt=""
                            />
                        </div>
                        <div class="w-full">
                            <p class="text-sm font-semibold">Luis</p>
                            <p class="text-sm text-gray-500 truncate">Salut comment ça va bien ?</p>
                        </div>
                    </li>
                    <li class="flex flex-row py-3 px-2 items-center border-indigo-600">
                        <div class="w-1/4">
                            <img src="https://ui-avatars.com/api/?length=1&name=SS" class="object-cover h-10 w-10 rounded-full" alt=""
                            />
                        </div>
                        <div class="w-full">
                            <p class="text-sm font-semibold">Luis</p>
                            <p class="text-sm text-gray-500 truncate">Salut comment ça va bien ?</p>
                        </div>
                    </li>
                </ul>
                <!-- end user list -->
            </div>
            <!-- end chat list -->
            <!-- message -->
            <div class="w-full px-5 flex flex-col justify-between">
                <div class="flex flex-col mt-5">
                    <div class="flex justify-end mb-4">
                        <div
                            class="mr-2 py-2 px-3 bg-indigo-500 rounded-bl-lg rounded-tl-lg rounded-tr-lg text-white text-sm"
                        >
                            Welcome to group everyone !
                        </div>
                        <img
                            src="https://ui-avatars.com/api/?length=1&name=HH"
                            class="object-cover h-8 w-8 rounded-full"
                            alt=""
                        />
                    </div>
                    <div class="flex justify-start mb-4">
                        <img
                            src="https://ui-avatars.com/api/?length=1&name=SS"
                            class="object-cover h-8 w-8 rounded-full"
                            alt=""
                        />
                        <div
                            class="ml-2 py-2 px-3 border-gray-50 bg-gray-200 border rounded-br-lg rounded-tr-lg rounded-tl-lg text-gray-900 text-sm max-w-96"
                        >
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat
                            at praesentium, aut ullam delectus odio error sit rem. Architecto
                            nulla doloribus laborum illo rem enim dolor odio saepe,
                            consequatur quas?
                        </div>
                    </div>
                    <div class="flex justify-end mb-4">
                        <div>
                            <div
                                class="mr-2 py-3 px-4 bg-blue-400 rounded-bl-3xl rounded-tl-3xl rounded-tr-xl text-white"
                            >
                                Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                                Magnam, repudiandae.
                            </div>

                            <div
                                class="mt-4 mr-2 py-3 px-4 bg-blue-400 rounded-bl-3xl rounded-tl-3xl rounded-tr-xl text-white"
                            >
                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                Debitis, reiciendis!
                            </div>
                        </div>
                        <img
                            src="https://ui-avatars.com/api/?length=1&name=HH"
                            class="object-cover h-8 w-8 rounded-full"
                            alt=""
                        />
                    </div>
                    <div class="flex justify-start mb-4">
                        <img
                            src="https://ui-avatars.com/api/?length=1&name=SS"
                            class="object-cover h-8 w-8 rounded-full"
                            alt=""
                        />
                        <div
                            class="ml-2 py-3 px-4 bg-gray-400 rounded-br-3xl rounded-tr-3xl rounded-tl-xl text-white"
                        >
                            happy holiday guys!
                        </div>
                    </div>
                </div>
                <div class="py-5  flex">
                    <input
                        class="text-sm w-full bg-white py-2 px-3 rounded-lg border-gray-300
{{--                        ring--}}
{{--                        ring-inset--}}
{{--                        ring-gray-300 --}}
{{--                        focus:ring--}}
{{--                        focus:ring-inset--}}
                         focus:ring-indigo-500
                         "
                        type="text"
                        placeholder="type your message here..."
                    />
                    <button class="ml-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-indigo-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/>
                        </svg>
                    </button>
                </div>
            </div>
            <!-- end message -->
        </div>
    </div>
    </div>
</main>
