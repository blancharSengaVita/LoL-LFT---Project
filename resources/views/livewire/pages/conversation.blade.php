<?php

use \App\Events\MessageEvent;
use \App\Models\Message;
use \App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use \App\Models\User;
use function Livewire\Volt\layout;
use function Livewire\Volt\{
	state,
	mount,
	on,
	computed,
	updated,
};

layout('layouts.message');

state([
	'message',
	'messages',
	'conversation',
	'convos' => [],
	'user_two',
]);

mount(function (Conversation $conversation) {
	$this->conversation = $conversation;

	if ($conversation->user_one_id === Auth::id()) {
		$this->user_two = User::find($conversation->user_two_id);
	} else {
		$this->user_two = User::find($conversation->user_one_id);
	}

	$this->messages = Message::where('conversation_id', $this->conversation->id)->get();
	foreach ($this->messages as $message) {

		if ($message->color === 'green') {
			if ($message->user_id === Auth::id() && $message->color === 'green') {
				$this->convos[] = '<div class="flex justify-center my-8">
                                <div class="py-4 px-6 bg-green-600 rounded-lg text-white text-md">
                                    ' . htmlspecialchars($message->message) . '
                                </div>
                            </div>';
			} else {
				$this->convos[] = '<div class="flex justify-center my-8">
                        <div
                            class="py-4 px-6 border-gray-50 bg-green-600 border rounded-lg text-gray-900 text-white text-md max-w-96"
                        >'
					. htmlspecialchars($message->message) .
					'</div>
                    </div>';
			}
		} else {
			if ($message->user_id === Auth::id()) {
				$this->convos[] = '<div class="flex justify-end mb-4">
                                <div class="mr-2 py-2 px-3 bg-indigo-500 rounded-bl-lg rounded-tl-lg rounded-tr-lg text-white text-sm">
                                    ' . htmlspecialchars($message->message) . '
                                </div>
                            </div>';
			} else {
				$this->convos[] = '<div class="flex justify-start mb-4">
                        <div
                            class="ml-2 py-2 px-3 border-gray-50 bg-gray-200 border rounded-br-lg rounded-tr-lg rounded-tl-lg text-gray-900 text-sm max-w-96"
                        >'
					. htmlspecialchars($message->message) .
					'</div>
                    </div>';
			}
		}
	}
//	$this->markAsRead();
});

$submitMessage = function () {
	MessageEvent::dispatch(Auth::user()->id, $this->message, $this->conversation->id);
	$this->message = "";
};

$markAsRead = function () {
	$conversationId = $this->conversation->id;
	Message::where('conversation_id', $conversationId)
		->whereNot('user_id', Auth::id())
		->update(['read_at' => now()]);
	$this->renderChange();
};

on(['echo:our-channel,MessageEvent' => function ($data) {
	$this->listenForMessage($data);
}]);

on(['echo:Accept-lft-invitation,AcceptLftPostEvent' => function ($data) {
	$this->listenForLftInvitation($data);
}]);

$listenForLftInvitation = function ($data) {
	if ($data['user_id'] === Auth::id()) {
		$this->convos[] = '<div class="message flex justify-end mb-4">
                                <div class="mr-2 py-4 px-6 bg-green-500 rounded-bl-lg rounded-tl-lg rounded-tr-lg text-white text-sm">
                                    ' . htmlspecialchars($data['message']) . '
                                </div>
                            </div>';
	} else {
		$this->convos[] = '<div class="message flex justify-start mb-4">
                        <div
                            class="ml-2 py-2 px-3 border-gray-50 bg-gray-200 border rounded-br-lg rounded-tr-lg rounded-tl-lg text-gray-900 text-sm max-w-96"
                        >'
			. htmlspecialchars($data['message']) .
			'</div>
                    </div>';
	}
	$this->dispatch('message-sent')->self();
};


$listenForMessage = function ($data) {
	if ($data['user_id'] === Auth::id()) {
		$this->convos[] = '<div class="message flex justify-end mb-4">
                                <div class="mr-2 py-2 px-3 bg-indigo-500 rounded-bl-lg rounded-tl-lg rounded-tr-lg text-white text-sm">
                                    ' . htmlspecialchars($data['message']) . '
                                </div>
                            </div>';
	} else {
		$this->convos[] = '<div class="message flex justify-start mb-4">
                        <div
                            class="ml-2 py-2 px-3 border-gray-50 bg-gray-200 border rounded-br-lg rounded-tr-lg rounded-tl-lg text-gray-900 text-sm max-w-96"
                        >'
			. htmlspecialchars($data['message']) .
			'</div>
                    </div>';
	}
	$this->dispatch('message-sent')->self();
};
?>


<main class="lg:pl-72 h-full">
    <x-slot name="h1">
        {{ 'salut' }}
    </x-slot>
    <!-- This is an example component -->
    <div class="mx-auto shadow-lg rounded-lg min-h-screen flex flex-col">
        <livewire:partials.app-header :title="'Messages'"/>
        <!-- end header -->
        <!-- Chatting -->
        <div class="h-full flex flex-row flex-grow justify-between bg-white border-t">
            <!-- chat list -->
            <div class="flex flex-col w-2/5 border-r bg-white">
                <livewire:pages.message-list/>
            </div>
            <!-- messages -->
            <div class="w-full flex flex-col justify-between">
                <div class="flex items-center px-6 py-4 border-b">
                    <p class="text-lg font-medium"> {{ $user_two->game_name }} </p>
                </div>
                <div
                        id="messagesContainer" class="flex flex-col
                        py-2
                        px-2
                flex-grow
                overflow-scroll
                h-10
                ">
                    @foreach($convos as $convo)
                        {!! $convo !!}
                    @endforeach
                </div>
                <form wire:submit.prevent="submitMessage" class="pb-5 px-3 pt-2 flex">
                    <input
                        wire:model="message"
                        class="text-sm w-full bg-white py-2 px-3 rounded-lg border-gray-300
                         focus:ring-indigo-500"
                        type="text"
                        placeholder="type your message here..."
                    />
                    <button class="ml-2 text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md py-2 text-sm leading-6 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-gray-400 group-hover:text-indigo-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

@script
<script>
    const container = document.getElementById('messagesContainer');
    scrollToBottom();

    function scrollToBottom () {
        container.scrollTop = container.scrollHeight;
    }

    document.addEventListener('DOMContentLoaded', function () {
        scrollToBottom(); // Scroll initial au chargement de la page
    });

    $wire.on('message-sent', () => {
        setTimeout(() => scrollToBottom(), 1);
    });
</script>
@endscript
