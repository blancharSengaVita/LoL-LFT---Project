<?php


use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Notification;
use \App\Models\User;
use App\Models\Message;
use \App\Events\MessageEvent;
use \App\Models\Conversation;
use App\Events\AcceptLftPostEvent;
use App\Models\TeamMember;
use Masmerise\Toaster\Toaster;

use function Livewire\Volt\layout;
use function Livewire\Volt\{
	state,
	mount,
	on,
};

layout('layouts.dashboard');


state([
	'user',
	'displayed_informations',
	'conversations',
	'notifications',
	'message',
]);

$renderChange = function (){
    $this->notifications = Notification::where('to', Auth::id())->get() ?? 0;
    foreach ($this->notifications as $notification) {
        $from = User::find($notification->from);
        $notification['receiver'] = $from;

        if ($notification->receiver->profil_picture) {
            $notification->receiver['src'] = '/storage/images/1024/' . $notification->receiver->profil_picture;
        } else {
            $notification->receiver['src'] = 'https://ui-avatars.com/api/?length=1&name=' . $notification->receiver->game_name;
        }
    }
};

$markAsRead = function () {
    Notification::where('to', Auth::id())->update(['read_at' => now()]);
    $this->renderChange();
};

mount(function () {
	$this->user = Auth::user();
    $this->renderChange();
    $this->markAsRead();
});

on(['echo:notification-send,NotificationEvent' => function () {
    $this->renderChange();
}]);

$deleteNotification = function (Notification $notification){
	$notification->delete();
    $this->renderChange();
};

$acceptNotification = function (Notification $notification){
	$userId = $notification->from;
    $this->conversation = Conversation::where(function ($query) use ($userId) {
        $query->where('user_one_id', Auth::id())
            ->where('user_two_id', $userId);
    })->orWhere(function ($query) use ($userId) {
        $query->where('user_one_id', $userId)
            ->where('user_two_id', Auth::id());
    })->first();

    if(!$this->conversation) {
		$this->conversation = Conversation::create([
			'user_one_id' => Auth::id(),
			'user_two_id' => $this->user->id,
		])->get();
	}


	$user = User::find($notification->from);
	$this->message = 'Demande LFT accepté !';
    AcceptLftPostEvent::dispatch(Auth::user()->id, $this->message, $this->conversation->id);
    $this->message = '';
	$notification->delete();
    $this->renderChange();
};

$acceptNotificationTeam = function (Notification $notification){
    $userId = $notification->from;
    $this->conversation = Conversation::where(function ($query) use ($userId) {
        $query->where('user_one_id', Auth::id())
            ->where('user_two_id', $userId);
    })->orWhere(function ($query) use ($userId) {
        $query->where('user_one_id', $userId)
            ->where('user_two_id', Auth::id());
    })->first();

    if(!$this->conversation) {
        $this->conversation = Conversation::create([
            'user_one_id' => Auth::id(),
            'user_two_id' => $this->user->id,
        ])->get();
    }

    TeamMember::updateOrCreate([
        'team_id' => $notification->from,
        'player_id' => Auth::id(),
    ],
        [
            'username' => $this->user->username,
            'job' => $this->user->job,
            'nationality' => $this->user->nationality,
            'entry_date' => $this->user->entry_date,
            'type' => $this->user->account_type,
            'archived' => false,
        ]);

    $this->renderChange();
    $this->dispatch('renderOnboarding');
    $this->dispatch('archiveMember');
//    $this->openSingleModal = false;
//    if ($this->id === 0) {
//        Toaster::success('Joueurs ajouté avec succès');
//    }

//    if ($this->id !== 0) {
        Toaster::success('Demande accepté');
//    }

    $user = User::find($notification->from);
    $this->message = 'Demande de recrutement accepté !';
    AcceptLftPostEvent::dispatch(Auth::user()->id, $this->message, $this->conversation->id);
    $this->message = '';
    $notification->delete();
	$this->renderChange();
};
?>

<main class="lg:pl-72 h-full">
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
            <div class="">
                @if(!count($notifications))
                    <p class="bg-white border-t border-b p-4">Aucune notification</p>
                @endif
                <ul>
                    @foreach($notifications as $notification)
                        <div class="pointer-events-auto w-full bg-white border-t border-b">
                            <div class="p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 pt-0.5">
                                        <img class="h-10 w-10 rounded-full" src="{{$notification->receiver->src}}" alt="">
                                    </div>
                                    <div class="ml-3 w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{$notification->receiver->game_name}}</p>
                                        <p class="mt-1 text-sm text-gray-500">{{$notification->description}}</p>
                                        <div class="mt-4 flex">
                                            @if($notification->receiver->account_type === 'team')
                                                <button wire:click="acceptNotificationTeam({{$notification}})" type="button" class="inline-flex items-center rounded-md bg-indigo-600 px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                                    Accepter
                                                </button>
                                            @else
                                                <button wire:click="acceptNotification({{$notification}})" type="button" class="inline-flex items-center rounded-md bg-indigo-600 px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                                    Accepter
                                                </button>
                                            @endif
                                                <button wire:click="deleteNotification({{$notification}})" type="button" class="ml-3 inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                                    Refuser
                                                </button>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex flex-shrink-0">
                                        <button wire:click="deleteNotification({{$notification}})" type="button" class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                            <span class="sr-only">Close</span>
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
</main>






