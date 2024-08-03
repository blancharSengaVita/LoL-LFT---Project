<?php


use Illuminate\Support\Facades\Auth;
use function Livewire\Volt\layout;
use function Livewire\Volt\{
    state,
    mount,
};

state([
    'user',
    'displayed_informations'
]);


mount(function () {
    $this->user = Auth::user();
    $this->displayed_informations = $this->user->displayedInformation()->first();
});

?>

<nav class="bg-white shadow border-b">
    <h3 class="sr-only">
        Menu de navigation du profil
    </h3>
    <div class="mx-auto max-w-7xl">
        <div class="flex h-16 justify-between">
            <div class="flex">
                <div class="space-x-6 space-y-1 sm:space-y-0 sm:ml-6  ml-4 sm:flex flex-wrap sm:space-x-8">
                    <!-- Current: "border-indigo-500 text-gray-900", Default: "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700" -->
                    <a href="{{route('dashboard')}}" class="{{ Route::is('dashboard') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium">Profile</a>

                    @if($user->account_type === 'team')
                        <a href="{{route('member')}}" class="{{ Route::is('member') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center border-b-2  px-1 pt-1 text-sm font-medium text-gray-500">Membres</a>
                    @endif
                    @if($user->account_type !== 'team')
                        <a href="#" class="{{ Route::is('') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center border-b-2  px-1 pt-1 text-sm font-medium text-gray-500">Historiques
                            des matchs</a>
                        <a href="#" class="{{ Route::is('') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center border-b-2  px-1 pt-1 text-sm font-medium text-gray-500">Stats</a>
                    @endif
                    <a href="#" class="{{ Route::is('') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center border-b-2  px-1 pt-1 text-sm font-medium text-gray-500">Livres
                        d'or</a>
                    <a href="#" class="{{ Route::is('') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center border-b-2  px-1 pt-1 text-sm font-medium text-gray-500">Amis</a>
                </div>
            </div>
        </div>
    </div>
</nav>
