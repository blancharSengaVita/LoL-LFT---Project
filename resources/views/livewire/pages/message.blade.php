<?php

use \App\Events\MessageEvent;
use \App\Models\Message;
use \App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use function Livewire\Volt\layout;
use \App\Models\User;
use function Livewire\Volt\{
    state,
    mount,
    on,
    computed,
    hydrate,
};

layout('layouts.message');

state([
    'message',
    'messages',
    'conversation',
    'conversations' => [],
    'listeners' => ['echo:messages,MessageSent' => 'onMessageSent'],
    'convos' => [],
    'search' => '',
]);

mount(function () {
    $this->conversations = Conversation::where('user_one_id', Auth::id())
        ->orWhere('user_two_id', Auth::id())->get();

    foreach ($this->conversations as $conversation) {
        if ($conversation->user_one_id === Auth::id()) {
            $userId = $conversation->user_two_id;
        }
        if ($conversation->user_two_id === Auth::id()) {
            $userId = $conversation->user_one_id;
        }

        $user = User::find($userId);

        $conversation['game_name'] = $user->game_name;
        $conversation['username'] = $user->username;

        if ($user->profil_picture) {
            $conversation['src'] = '/storage/images/1024/' . $user->profil_picture;
        } else {
            $conversation['src'] = 'https://ui-avatars.com/api/?length=1&name=' . $user->game_name;
        }
    }
});

$filteredUser = computed(function () {
    $results = User::where(function($query) {
        $query->where('username', 'like', '%' . $this->search . '%')
            ->orWhere('game_name', 'like', '%' . $this->search . '%');
    })
        ->where('id', '!=', Auth::id())
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

$newConversation = function ($userId) {
    $this->conversation = Conversation::where(function ($query) use ($userId) {
        $query->where('user_one_id', Auth::id())
            ->where('user_two_id', $userId);
    })->orWhere(function ($query) use ($userId) {
        $query->where('user_one_id', $userId)
            ->where('user_two_id', Auth::id());
    })->first();

    if(!$this->conversation){
        $this->conversation = Conversation::create([
            'user_one_id' => Auth::id(),
            'user_two_id' => $userId,
        ]);
    }

    $this->redirect(route('conversation', ['conversation' => $this->conversation->id], absolute: false), navigate: true);
};
?>


<main class="lg:pl-72 h-full">
    <x-slot name="h1">
        {{ 'salut' }}
    </x-slot>
    <!-- This is an example component -->
    <div class="container mx-auto shadow-lg rounded-lg min-h-screen max-h-min flex flex-col">
        <livewire:partials.app-header :title="'Messages'"/>
        <!-- end header -->
        <!-- Chatting -->
        <div class="h-full flex flex-row flex-grow justify-between bg-white border-t">
            <!-- chat list -->
            <div class="flex flex-col w-2/5 border-r bg-gray">
                <!-- user list -->
                <livewire:pages.message-list/>
                <!-- end user list -->
            </div>
            <!-- end chat list -->
            <!-- message -->
            <div class="w-full px-5 flex flex-col justify-between">
                <div class="flex flex-col mt-5 flex-grow">
                    <div>
{{--                        <p class="text-lg font-medium text-gray-900 sm:text-xl">Sélectionnez un message.</p>--}}
                        <p class="text-base font-semibold leading-6 text-gray-900">Sélectionnez un message.</p>
{{--                        <p class="text-xl font-bold text-gray-900 sm:text-2xl">Sélectionnez un message</p>--}}
                        <p class="text-base"> Faites un choix dans vos conversations existantes ou commencez-en une nouvelle.
                        </p>
                        <div class="mt-4">
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
                                    x-cloak
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
                                            {{--                                                            wire:click="sendNotification"--}}
{{--                                            @click="$wire.sendNotification"--}}
                                            x-data="{ isHovered: false }"
                                            @mouseenter="isHovered = true"
                                            @mouseleave="isHovered = false"
                                            :class="isHovered ? 'text-white bg-indigo-600' : 'text-gray-900'"
                                            class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900" id="option-0" role="option" tabindex="-1"
                                        >
                                            <a class="flex items-center"
{{--                                               href="{{route('user', ['user' => $player->username])}}" title="Discuter avec {{$player->game_name}}"--}}
                                                wire:click="newConversation({{$player->id}})"
                                            >
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
            </div>
        </div>
        <!-- end message -->
    </div>
</main>
