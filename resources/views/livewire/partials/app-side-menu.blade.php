<?php

use function Livewire\Volt\{state, mount, on};
use Illuminate\Support\Facades\Auth;
use \App\Models\Notification;
use \App\Models\Message;
use \Illuminate\Support\Facades\Route;
use \App\Models\Conversation;

state([
	'unSeen',
	'unSeenMessage',
]);

$newNotification = function () {
        $this->unSeen = Notification::where('to', Auth::id())->whereNull('read_at')->exists();
};


$newMessage = function () {
	$conversations = Conversation::where('user_one_id', Auth::id())
		->orWhere('user_two_id', Auth::id())
		->get();
		$this->unSeenMessage = Message::whereHas('conversation', function ($query) {
			$query->where('user_one_id', Auth::id())
				->orWhere('user_one_id', Auth::id());
		})
			->whereNot('user_id', Auth::id())
            ->whereNull('read_at')
			->exists();
};

mount(function () {
	$this->newNotification();
	$this->newMessage();
});

on(['echo:notification-send,NotificationEvent' => function () {
		$this->newNotification();
}]);

on(['echo:our-channel,MessageEvent' => function ($data) {
	$this->newMessage();
}]);
?>

<aside class="hidden lg:fixed lg:inset-y-0 lg:z-40 lg:flex lg:w-72 lg:flex-col">
    <!-- Sidebar component, swap this element with another sidebar if you like -->
    <h2 class="sr-only">Menu latéral</h2>
    <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6">
        <div class="flex h-16 shrink-0 items-center text-indigo-500">
            <div class="flex h-16 shrink-0 items-center text-indigo-600">
                <svg class="fill-current" width="37" height="37" viewBox="0 0 37 37" xmlns="http://www.w3.org/2000/svg">
                    <rect x="35.1433" y="17.14" width="6" height="24" transform="rotate(130.61 35.1433 17.14)" />
                    <path d="M16.9235 1.51831L21.4784 5.42372L11.064 17.5703L8.46173 11.3874L16.9235 1.51831Z" />
                    <path d="M19.2009 3.47102L16.9235 1.51831L18.2253 -8.88109e-06L19.2009 3.47102Z" />
                    <path d="M32.8659 15.1873L35.1433 17.14L36.4451 15.6216L32.8659 15.1873Z" />
                    <rect x="1.3018" y="19.7382" width="6" height="24" transform="rotate(-49.3903 1.3018 19.7382)" />
                    <path d="M19.5217 35.3598L14.9667 31.4544L25.3811 19.3079L27.9834 25.4907L19.5217 35.3598Z" />
                    <path d="M17.2442 33.4071L19.5217 35.3598L18.2199 36.8782L17.2442 33.4071Z" />
                    <path d="M3.57929 21.6909L1.3018 19.7382L-3.51667e-06 21.2565L3.57929 21.6909Z" />
                    <rect x="22.4451" y="18.3232" width="6" height="6" transform="rotate(130.61 22.4451 18.3232)" />
                </svg>
            </div>
        </div>
        <nav class="flex flex-1 flex-col">
            <h3 class="sr-only"> Menu de navigation principal </h3>
            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                <li>
                    <ul role="list" class="-mx-2 space-y-1">
                        <li>
                            <!-- Current: "bg-gray-50 text-indigo-600", Default: "text-gray-700 hover:text-indigo-600 hover:bg-gray-50" -->
                            <a wire:navigate href="{{route('dashboard')}}" title="Vers la page dashboard"
                               class="{{ Route::is('dashboard') ? 'bg-gray-50 text-indigo-600' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <svg class="{{ Route::is('dashboard') ? 'text-indigo-600' : 'text-gray-400' }} h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a wire:navigate href="{{route('find-teammate')}}" title="Vers la page recherche de partenaires"
                               class="{{ Route::is('find-teammate') ? 'bg-gray-50 text-indigo-600' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50' }}  group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <svg class="{{ Route::is('find-teammate') ? 'text-indigo-600' : 'text-gray-400' }} h-6 w-6 shrink-0  group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                                </svg>
                                Recherche de partenaires
                            </a>
                        </li>
                        <li>
                            <a wire:navigate href="{{route('messages')}}" title="Vers la page messages" class="{{ Route::is('messages') || Route::is('conversation') ? 'bg-gray-50 text-indigo-600' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold items-center">
                                <svg class="{{ Route::is('messages') ? 'text-indigo-600' : 'text-gray-400' }} h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
                                </svg>
                                Messages
                                @if($this->unSeenMessage)
                                    <svg class="text-indigo-600 h-2 w-2 group-hover:text-indigo-600" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <circle cx="12" cy="12" r="10"/>
                                    </svg>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a wire:navigate href="{{route('notifications')}}" title="Vers la page notification"
                               class="{{ Route::is('notifications') ? 'bg-gray-50 text-indigo-600' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold items-center ">
                                <svg class="{{ Route::is('notifications') ? 'text-indigo-600' : 'text-gray-400' }} h-6 w-6 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                                </svg>
                                Notifications
                                @if($this->unSeen)
                                    <svg class="text-indigo-600 h-2 w-2 group-hover:text-indigo-600" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <circle cx="12" cy="12" r="10"/>
                                    </svg>
                                @endif
                            </a>
                        </li>

                        <li>
                            <a wire:navigate href="{{route('missions')}}" title="Vers la page missions"
                                class="{{ Route::is('missions') ? 'bg-gray-50 text-indigo-600' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <svg {{ Route::is('missions') ? 'text-indigo-600' : 'text-gray-400' }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-gray-400 group-hover:text-indigo-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/>
                                </svg>
                                Missions
                            </a>
                        </li>


                        <li>
                            <a wire:navigate href="{{route('settings')}}" title="Vers la page paramêtres" class="{{ Route::is('settings') ? 'bg-gray-50 text-indigo-600' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ Route::is('settings') ? 'text-indigo-600' : 'text-gray-400' }} w-6 h-6 shrink-0 text-gray-400 group-hover:text-indigo-600" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                Paramètres
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
