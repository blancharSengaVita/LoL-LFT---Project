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
	'currentConversation' => request()->route('conversation') ?? 0,
	'unSeenMessage',
]);

$renderChange = function () {
	if ($this->currentConversation === 0) {
		$this->currentConversation = new Conversation();
		$this->currentConversation->id = 0;
	}

	$realConversation = Conversation::find($this->currentConversation->id);
	if ($realConversation) {
		Message::whereHas('conversation', function ($query) use ($realConversation) {
			$query->where('id', $realConversation->id);
		})->whereNot('user_id', Auth::id())->whereNull('read_at')
			->orderBy('created_at', 'desc')->update(['read_at' => now()]);
	}

	$this->conversations = Conversation::where('user_one_id', Auth::id())
		->orWhere('user_two_id', Auth::id())->get();

	foreach ($this->conversations as $conversation) {
		if ($conversation->user_one_id === Auth::id()) {
			$userId = $conversation->user_two_id;
		}
		if ($conversation->user_two_id === Auth::id()) {
			$userId = $conversation->user_one_id;
		}

		$conversation['unSeenMessage'] = Message::whereHas('conversation', function ($query) use ($conversation) {
			$query->where('id', $conversation->id);
		})->whereNot('user_id', Auth::id())->whereNull('read_at')
			->orderBy('created_at', 'desc')
			->exists();

		$user = User::find($userId);

		$conversation['game_name'] = $user->game_name;
		$conversation['username'] = $user->username;

		if ($user->profil_picture) {
			$conversation['src'] = '/storage/images/1024/' . $user->profil_picture;
		} else {
			$conversation['src'] = 'https://ui-avatars.com/api/?length=1&name=' . $user->game_name;
		}
	}

};

mount(function () {
	$this->renderChange();
});

on(['echo:our-channel,MessageEvent' => function ($data) {
	$this->renderChange();
}]);
?>

<ul class="flex flex-col">
    @foreach($conversations as $conversation)
        <li>
            <a type="button" title="Envoyer des messages à {{$conversation->game_name}}"  wire:navigate class="flex flex-row py-3 px-2 items-center h-full
            {{ $currentConversation->id === $conversation->id ? 'border-l-4 bg border-l-indigo-600 border-b bg-gray-50' : 'border-b hover:bg-white' }}"

               href="{{ route('conversation', ['conversation' => $conversation->id]) }}">
                <div class="w-1/4">
                    {{--                                        flex flex-row py-3 px-2 items-center border-l-4 bg border-indigo-600 bg-gray-50--}}
                    <img src="{{$conversation->src}}" class="object-cover h-10 w-10 rounded-full" alt=""
                    />
                </div>
                <div class="w-full">
                    <p class="text-sm font-semibold">{{$conversation->game_name}}</p>
                    <p class="text-sm text-gray-500 truncate">{{$conversation->username}}</p>
                </div>
                @if($conversation->unSeenMessage)
                    <svg class="text-indigo-600 h-3 w-3 group-hover:text-indigo-600" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/>
                    </svg>
                @endif
            </a>
        </li>
    @endforeach
</ul>

