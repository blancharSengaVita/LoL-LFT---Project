<?php

use App\Livewire\Actions\Logout;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use  \Illuminate\Support\Facades\Route;
use function Livewire\Volt\{
    state,
    mount,
};

state([
    'users',
]);

mount(function () {
    $this->users = User::whereNot('id', Auth::user()->id)
        ->whereNotNull('username')
        ->where('setup_completed', true)
        ->inRandomOrder()
        ->limit(3)
        ->get();

    foreach ($this->users as $user) {
        if ($user->profil_picture) {
            $user['src'] = '/storage/images/1024/' . $user->profil_picture;
        } else {
            $user['src'] = 'https://ui-avatars.com/api/?length=1&name=' . $user->game_name;
        }
    }
});
?>

<aside class="fixed inset-y-0 right-0 hidden w-96 overflow-y-auto border-l border-gray-200 px-2 py-3 sm:px-2 lg:px-4 xl:block bg-white">
    <h2 class="sr-only"> Informations Complémentaires </h2>
    <!--  Secondary column (hidden on smaller screens) -->
    <div class="rounded-md font-medium pt-3 flex grow flex-col gap-y-5 overflow-y-auto border border-gray-200 bg-white">
        <span class="text-gray-900 px-3"> Recommandation aléatoire </span>
        <ul role="list" class="divide-y divide-gray-100">
            @foreach($users as $user)
                <li class="flex items-center justify-between hover:bg-gray-50 w-full">
                    <a href="{{route('user', ['user' => $user->username])}}"
                       wire:navigate
                       title="aller vers la page de {{$user->game_name}}"
                       class="hover:bg-gray-50 cursor-pointer w-full">
                    <div class="flex min-w-0 gap-x-4 px-3 py-5">
                        @if($user->profil_picture)
                            <img class="h-12 w-12 rounded-full"
                                 src="/storage/images/150/{{$user->profil_picture}}"
                                 alt="Photo de profi de {{$user->game_name}}"
                                 sizes="(max-width: 640px) 150px, (max-width: 1024px) 200px, (max-width: 1280px) 400px, 1024px">
                        @else
                            <div class="h-12 w-12 flex-none rounded-full bg-gray-400 flex justify-center items-center">
                                <p class="text-lg text-center text-gray-950">{{ ucfirst(substr($user->game_name, 0, 1)) }}</p>
                            </div>
                        @endif
                        <div class="min-w-0 flex-auto">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{$user->game_name}}</p>
                            <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{$user->username}}</p>
                        </div>
                    </div>
{{--                    <p--}}
{{--                        wire:navigate--}}
{{--                        href="{{route('user', ['user' => $user->username])}}"--}}
{{--                        title="aller vers la page de {{$user->game_name}}"--}}
{{--                        class="rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">View</p>--}}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</aside>
