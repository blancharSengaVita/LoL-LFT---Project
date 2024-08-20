<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\DisplayedInformation;
use App\Models\DisplayedInformationsOnce;
use App\Models\Award;
use App\Models\User;
use Carbon\Carbon;
use Masmerise\Toaster\Toaster;
use App\Models\TeamMember;

use function Livewire\Volt\layout;
use function Livewire\Volt\{
    state,
    on,
    mount,
    rules,
    computed,
};

layout('layouts.dashboard');

state([
    'user',
    'models',
]);

$renderChange = function () {
    $this->models = $this->user->players()->where('type', 'player')->where('archived', false)->get();

    foreach ($this->models as $model) {
        $model->entry_date = Carbon::parse($model->entry_date)->locale('fr_FR')->isoFormat('D MMMM YYYY');
    }
//	dd($this->models);
};

mount(function (User $user) {
    $this->user = $user;

    $this->renderChange();
});
?>

<article>

    <div x-cloack class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
        <div class="flex justify-between gap-x-4 pb-1 items-center sm:flex-nowrap">
            <h3 class="text-base font-semibold leading-6 text-gray-900">{{'Joueurs'}}</h3>
        </div>
        <div class=" sm:w-12/12">
            <ul role="list" class="divide-y divide-gray-100">
                @if(count($models)===0 || null)
                    <p class="mt-1 mb-1 flex justify-center">Il n'y a aucun joueur dans cette équipe</p>
                @else
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                        <tr class="flex">
                            <th scope="col" class="flex-1 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                Nom
                            </th>
                            <th scope="col" class="flex-1  px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                Poste
                            </th>
                            <th scope="col" class="flex-1  hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">
                                Nationalité
                            </th>
                            <th scope="col" class="flex-1  hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 md:table-cell">
                                Date d'entrée
                            </th>
                            <th scope="col" class="flex-1  relative py-3.5 pl-3 pr-4 sm:pr-0">
                                <span class="sr-only">actions</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @foreach($models as $player)
                            <tr class="flex">
                                <td class="truncate flex-1 whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{ $player->username }}</td>
                                <td class="flex-1 whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{  __('jobs.'.$player->job)  }}</td>
                                <td class="flex-1 hidden whitespace-nowrap px-3 py-4 text-sm text-gray-500 sm:table-cell">{{ __('nationalities.'.$player->nationality) }}</td>
                                <td class="flex-1 hidden whitespace-nowrap px-3 py-4 text-sm text-gray-500 md:table-cell">{{ $player->entry_date }}</td>
                                <td class="flex-1 relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif

            </ul>
        </div>
    </div>
</article>
