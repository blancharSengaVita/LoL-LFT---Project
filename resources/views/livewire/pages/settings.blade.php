<?php

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Validation\Rules;
use App\Rules\StartsWithAt;
use \App\Models\UserMission;
use \App\Models\OnboardingMission;
use Intervention\Image\ImageManagerStatic as Image;
use Masmerise\Toaster\Toaster;
use function Livewire\Volt\{
    state,
    mount,
    layout,
    rules,
    computed,
    usesFileUploads,
};

layout('layouts.dashboard');
usesFileUploads();
ini_set('memory_limit', '256M');

state([
    'user',
    'displayed_informations',
    'game_name',
    'username',
    'birthday',
    'nationalities',
    'nationality',
    'type',
    'pseudoInput' => '',
    'pseudoDescription' => '',
    'gameNameInput' => '',
    'gameNameDescription' => '',
    'jobs' => [],
    'regions' => [],
    'levels' => [],
    'profilPictureLabel' => 'importer une photo de profil',
    'profilPictureFilename' => 'Aucun fichier sélectionné',
    'profilPicture',
    'bio' => '',
    'job' => '',
    'region' => '',
    'level' => '',
    'messageJob' => '',
]);

mount(function () {
    $this->user = Auth::user();
    $this->displayed_informations = $this->user->displayedInformation()->first();

    $this->nationalities = require __DIR__ . '/../../../../app/enum/nationalities.php';
    $this->levels = require __DIR__ . '/../../../../app/enum/levels.php';
    $this->game_name = $this->user->game_name ?? '';
    $this->username = $this->user->username ?? '@';
    $this->birthday = $this->user->birthday ?? '';
    $this->level = $this->user->level ?? '';
    $this->nationality = ucfirst($this->user->nationality) ?? '';
    $this->type = $this->user->account_type ?? '';


    if ($this->type === 'team') {
        $this->pseudoInput = 'Nom d\'équipe';
        $this->pseudoDescription = 'Mettez sera votre pseudo affiché';
        $this->gameNameInput = 'Nom du compte';
        $this->gameNameDescription = 'Celui-ci sera Le nom de votre compte';

    } else {
        $this->pseudoInput = 'Pseudo';
        $this->pseudoDescription = 'Celui-ci sera le nom d\'équipe affiché';
        $this->gameNameInput = 'Nom d\'utilisateur';
        $this->gameNameDescription = 'Celui-ci sera Le nom de votre compte';
    }

    $this->jobs = require __DIR__ . '/../../../../app/enum/jobs.php';
    $this->regions = require __DIR__ . '/../../../../app/enum/regions.php';

    if ($this->user->account_type === 'staff'){
        $this->jobs = $this->jobs['staff'];
        $this->messageJob = 'Choisissez votre niveau jeu ou dans votre profession';
    }

    if($this->user->account_type === 'player'){
        $this->jobs = $this->jobs['player'];
        $this->messageJob = 'Choisissez votre niveau sur le jeu';
    }

    if($this->user->account_type === 'team'){
        $this->jobs = $this->jobs['player'];
        $this->messageJob = 'Choisissez le niveau moyen de l\'équipe';
    }

    $this->job = $this->user->job ?? '';
    $this->type = $this->user->account_type ?? '';
    $this->region = $this->user->region ?? '';
    $this->bio = $this->user->bio ?? '';

    if ($this->type === 'team') {
        $this->job = 'team';
        rules(fn () => [
            'job' => 'required',
        ]);
    }

    if ($this->type !== 'team') {
        rules(fn() => [
            'nationality' => 'required',
            'birthday' => 'required|date',
        ]);
    }
});

rules([
    'game_name' => 'required|string|max:20',
    'username' => ['required', 'string', Rule::unique('users')->ignore(Auth::user()->id), new StartsWithAt, 'max:20'],
    'region' => 'required',
    'profilPicture' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:5120',
    'level' =>  'required',
])->messages([
    'level.required' => 'Votre niveau est requis',
    'nationality.required' => 'Votre nationalité est requis',
    'game_name.required' => 'Votre pseudo est requis',
    'game_name.string' => 'Votre pseudo doit être composé de lettre',
    'username.required' => 'Votre nom d\'utilisateur est requis',
    'username.string' => 'Votre nom doit être composé de lettre',
    'birthday.required' => 'Votre date de naissance est requis',
    'birthday.date' => 'Votre date de naissance ne correspond pas au format',
    'bio.required' => 'Votre bio est requis',
    'job.required' => 'Votre job est requis',
    'region.required' => 'Votre region est requis',
    'profilPicture.image' => 'Le fichier n\'est pas une image',
    'profilPicture.mimes' => 'Seuls les fichiers de type jpeg, png, jpg sont autorisés.',
    'profilPicture.max' => 'La taille maximale de l’image doit être de 5MB.',
    'profilPicture.uploaded' => 'Le chargement de l\'image a échoué',
    'bio.required' => 'Votre bio est requis',
    'job.required' => 'Votre job est requis',
    'region.required' => 'Votre region est requis',
    'profilPicture.image' => 'Le fichier n\'est pas une image',
    'profilPicture.mimes' => 'Seuls les fichiers de type jpeg, png, jpg sont autorisés.',
    'profilPicture.max' => 'La taille maximale de l’image doit être de 5MB.',
    'profilPicture.uploaded' => 'Le chargement de l\'image a échoué',
]);

$saveGeneralInfo = function () {
    $validated = $this->validate();
    $user = Auth::user();
    $user->game_name = $this->game_name;
    $user->username = $this->username;
    $user->nationality = $this->nationality;
    $user->birthday = $this->birthday;
    $user->save();
    Toaster::success('Modification effectué avec succès');
};

$cancelGeneralInfo = function () {
    $this->game_name = $this->user->game_name ?? '';
    $this->username = $this->user->username ?? '@';
    $this->birthday = $this->user->birthday ?? '';
    $this->nationality = ucfirst($this->user->nationality) ?? '';
};

$saveProfilePicture = function () {
    if (isset($this->profilPicture)) {
        $tmpPath = $this->profilPicture->getPathname();
        $originalFilename = $this->profilPicture->getClientOriginalName();
        $filenameParts = explode('.', $originalFilename);
        $extension = $filenameParts[array_key_last($filenameParts)];
        $newFilename = sha1_file($tmpPath) . '.' . $extension;
        $image = Image::make($this->profilPicture);
        $width = $image->width();
        $height = $image->height();
        $ratio = $width / $height;


        $sizes = ['1024', '512', '400', '200', '150'];

        foreach ($sizes as $size) {
            if (!file_exists(storage_path('app/public/images/' . $size))) {
                mkdir(storage_path('app/public/images/' . $size));
            }

            $image->resize($size, null, function ($constraint) {
                $constraint->aspectRatio(); // Maintient le ratio original
                $constraint->upsize(); // Empêche l'agrandissement
            });

            $image->save(storage_path('app/public/images/' . $size . '/') . $newFilename, 100);
            $image->destroy();
        }

        Auth::user()->profil_picture = $newFilename;
    }

    $validated = $this->validate();
    $this->user = Auth::user();

    $this->user->job = $this->job;
    $this->user->region = $this->region;
    $this->user->level = $this->level;
    $this->user->setup_completed = true;
    $this->user->save();
    Toaster::success('Modification effectué avec succès');
};

$cancelProfilePicture = function () {
    $this->game_name = $this->user->game_name ?? '';
    $this->job = $this->user->job ?? '@';
    $this->region = $this->user->region ?? '';
	$this->level = $this->user->level ?? '';
};
?>

<main class="lg:pl-72 h-full">
    <x-slot name="h1">
        {{ $user->game_name }}
    </x-slot>
    <section class="h-full">
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
            {{--            <livewire:partials.dashboard-header/>--}}
            <livewire:partials.app-header :title="'Paramètre'"/>
            {{--            créer un settings header--}}
            {{--            <div class="h-full flex items-center mt-20 flex-col" >--}}
            {{--                    <p class="text-xl font-bold text-gray-900 sm:text-2xl">Cooming soon</p>--}}
            {{--                    <p class="mt-2 text-sm text-gray-900" >Page en construction</p>--}}
            {{--            </div>--}}
            {{--            </div>--}}
            <form wire:submit.prevent="saveGeneralInfo" class="
            bg-white border-t border-gray-200
{{--            bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2--}}
            ">
                <div
                    class="
{{--                    grid--}}
{{--                    max-w-2xl--}}
{{--                    grid-cols-1--}}
{{--                    gap-x-6--}}
{{--                    gap-y-8--}}
{{--                    sm:grid-cols-6--}}
                    "
                >
                    <div class="px-4 py-6 p-8 lg:w-1/2">
                        <p class="text-base font-semibold leading-7 text-gray-900 mb-4">Information principale</p>
                        <div class="mb-4">
                            <label for="game_name" class="block text-sm font-medium leading-6 text-gray-900">{{ $this->pseudoInput  }}</label>
                            <div class="mt-2">
                                <input wire:model="game_name" type="text" name="game_name" id="game_name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Lee">
                            </div>
                            @error('game_name')
                            <p class="text-sm text-red-600 space-y-1 mt-2 mb-4"> {{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500" id="password-description">Celui-ci sera votre pseudo
                                affiché</p>
                        </div>

                        <div class="mb-4">
                            <label for="surname" class="block text-sm font-medium leading-6 text-gray-900">Nom
                                d'utilisateur</label>
                            <div class="mt-2">
                                <input wire:model="username" type="text" name="surname" id="surname" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Sang-hyeo">
                            </div>
                            @error('username')
                            <p class="text-sm text-red-600 space-y-1 mt-2 mb-4"> {{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500" id="password-description">Celui-ci sera votre pseudo
                                de
                                scène,
                                choisissez le bien</p>
                        </div>

                        @if($type !== 'team')
                            <div class="mb-4">
                                <label for="nationality" class="block text-sm font-medium leading-6 text-gray-900">Nationalité</label>
                                <select wire:model="nationality" id="nationality" name="nationality" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">-- choisissez votre nationalité --</option>
                                    @foreach($nationalities as $nationality)
                                        <option value="{{ $nationality }}">{{ __('nationalities.'.$nationality) }}</option>
                                    @endforeach
                                </select>
                                @error('nationality')
                                <p class="text-sm text-red-600 space-y-1 mt-2 mb-4"> {{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="birthday" class="block text-sm font-medium leading-6 text-gray-900">Date de
                                    naissance</label>
                                <div class="mt-2">
                                    <input wire:model="birthday" type="date" name="birthday" id="birthday" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="">
                                </div>
                                @error('birthday')
                                <p class="text-sm text-red-600 space-y-1 mt-2 mb-4"> {{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex items-center justify-end gap-x-6 px-4 py-4 sm:px-8">
                    <button  wire:click="cancelGeneralInfo" type="button" class="text-sm font-semibold leading-6 text-gray-900">Annuler</button>
                    <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Sauvegarder
                    </button>
                </div>
            </form>
            <form wire:submit.prevent="saveProfilePicture" class="
            bg-white border-t border-gray-200
            ">
{{--                <p class="mt-1 text-sm leading-6 text-gray-600">Use a permanent address where you can receive mail.</p>--}}
                <div class="px-4 py-6 p-8 lg:w-1/2">
                    <p class="text-base font-semibold leading-7 text-gray-900 mb-4">Information secondaire</p>
                    <div class="col-span-3 mb-4">
                        <label for="profil-picture" class="block text-sm font-medium leading-6 text-gray-900">
                            Photo de profil</label>
                        <div class="mt-2 flex flex-col items-center  rounded-lg border border-dashed border-gray-900/25 px-6 py-4">
                            <div class="text-center">
                                <div class="profil-picture flex items-center justify-center"></div>
                                <p class="flex justify-center items-center">{{ $profilPictureFilename }}</p>
                                {{--                                <svg class="mx-auto h-12 w-12 text-gray-300 profil-picture-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">--}}
                                {{--                                    <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd"/>--}}
                                {{--                                </svg>--}}
                                <div class="mt-2 justify-center flex text-sm leading-6 text-gray-600">
                                    <label for="profilePicture" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                                        <span>{{ $profilPictureLabel }}</span>
                                        <input wire:model.live="profilPicture" id="profilePicture" name="profilePicture" type="file" class="sr-only">
                                    </label>
                                </div>
                                <p class="text-xs leading-5 text-gray-600">PNG, JPG, GIF en-dessous de 10MB</p>
                            </div>
                        </div>
                        @error('profilPicture')
                        <p class="text-sm text-red-600 space-y-1 mt-2 mb-4"> {{ $message }}</p>
                        @enderror
                    </div>
                    {{--                    @if ($profilPicture->getClientOriginalExtension())--}}
                    {{--                        @if (('profilPicture'))--}}
                    {{--                            <img alt="aezr" src="{{ $profilPicture->temporaryUrl() }}">--}}
                    {{--                        @endif--}}
                    {{--                    @endif--}}


                        @if($type !== 'team')
                            <div class="col-span-3 mb-4">
                                <label for="job" class="block text-sm font-medium leading-6 text-gray-900">Poste<span class="text-red-500">*</span></label>
                                <select wire:model="job" id="job" name="job" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">-- choisissez votre poste --</option>
                                    @foreach($jobs as $job)
                                        <option value="{{ $job }}">{{ __('jobs.'.$job) }}</option>
                                    @endforeach
                                </select>
                                @error('job')
                                <p class="text-sm text-red-600 space-y-1 mt-2 mb-4"> {{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <div class="col-span-3">
                            <label for="region" class="block text-sm font-medium leading-6 text-gray-900">Région<span class="text-red-500">*</span></label>
                            <select wire:model="region" id="region" name="region" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option value="">-- choisissez votre region --</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region }}">{{ $region }}</option>
                                @endforeach
                            </select>
                            @error('region')
                            <p class="text-sm text-red-600 space-y-1 mt-2 mb-4"> {{ $message }}</p>
                            @enderror
                        </div>

                    <div class="col-span-3">
                        <label for="levels" class="block text-sm font-medium leading-6 text-gray-900">Niveau<span class="text-red-500">*</span></label>
                        <select wire:model="level" id="levels" name="levels" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option value="">-- choisissez votre niveau --</option>
                            @foreach($levels as $level)
                                <option value="{{ $level }}">{{ __('levels.'.$level) }}</option>
                            @endforeach
                        </select>
                        @error('level')
                        <p class="text-sm text-red-600 space-y-1 mt-2 mb-2"> {{ $message }}</p>
                        @enderror
                        {{--                                @if()--}}
                        <p class="mt-1 text-sm text-gray-500" id="password-description">
                            {{ $messageJob }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-x-6 px-4 py-4 sm:px-8">
                    <button wire:click="cancelProfilePicture" type="button" class="text-sm font-semibold leading-6 text-gray-900">Annuler</button>
                    <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Sauvegarder
                    </button>
                </div>
            </form>
        </div>
    </section>
</main>
@script
<script>
    const profilPictureInput = document.querySelector('input[type=file]');
    const profilPictureIcon = document.querySelector('.profil-picture-icon');
    const profilPicture = document.querySelector('.profil-picture');

    profilPictureInput.addEventListener('change', (e) => {
        const file = profilPictureInput.files[0];
        if (validFileType(file)) {

            const maxSize = 10 * 1024 * 1024; // 10 MB
            if (file.size > maxSize) {
                alert('La taille du fichier doit être inférieure à 10 MB.');
                e.currentTarget.value = ''; // Réinitialiser le champ de fichier
                return
            }

            const image = document.createElement('img');
            image.src = URL.createObjectURL(file);
            image.alt = 'l\'image importé';
            image.width = 100;
            image.height = 100;
            image.className = 'flex justify-center';

            $wire.profilPictureFilename = `${file.name}`;

            profilPictureIcon.remove();
            profilPicture.insertAdjacentElement('afterbegin', image);
        }
    });

    const fileTypes = [
        'image/jpg',
        'image/jpeg',
        'image/png',
    ];

    function validFileType (file) {
        return fileTypes.includes(file.type);
    }
</script>
@endscript





