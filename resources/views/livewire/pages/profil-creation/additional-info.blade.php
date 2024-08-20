<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Intervention\Image\ImageManagerStatic as Image;
use function Livewire\Volt\{
    state,
    rules,
    computed,
    layout,
    usesFileUploads,
    mount,
    updated,
};

usesFileUploads();
ini_set('memory_limit', '256M');

state([
    'user',
    'jobs' => [],
    'regions' => [],
    'levels' => [],
    'profilPictureLabel' => 'importer une photo de profil',
    'profilPictureFilename' => 'Aucun fichier sélectionné',
    'profilPicture',
    'level' => '',
    'bio' => '',
    'job' => '',
    'region' => '',
    'type' => '',
]);

mount(function () {
    $this->user = Auth::user();
    $this->jobs = require __DIR__ . '/../../../../../app/enum/jobs.php';
    $this->regions = require __DIR__ . '/../../../../../app/enum/regions.php';
    $this->levels = require __DIR__ . '/../../../../../app/enum/levels.php';

    if ($this->user->account_type === 'staff') {
        $this->jobs = $this->jobs['staff'];
    }

    if ($this->user->account_type === 'player') {
        $this->jobs = $this->jobs['player'];
    }

    if ($this->user->account_type === 'team') {
        $this->jobs = $this->jobs['player'];
    }

    $this->job = $this->user->job ?? '';
    $this->type = $this->user->account_type ?? '';
    $this->region = $this->user->region ?? '';
    $this->bio = $this->user->bio ?? '';
    $this->level = $this->user->level ?? '';

    if ($this->type === 'team') {
        $this->job = 'Team';
    }
});

rules([
    'job' => Auth::user()->account_type !== 'team' ? 'required' : 'nullable',
    'level' => Auth::user()->account_type !== 'staff' ? 'required' : 'nullable',
    'region' => 'required',
    'profilPicture' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:5120',
])->messages([
    'bio.required' => 'Votre bio est requis',
    'job.required' => 'Votre job est requis',
    'level.required' => 'Votre niveau est requis',
    'region.required' => 'Votre region est requis',
    'profilPicture.image' => 'Le fichier n\'est pas une image',
    'profilPicture.mimes' => 'Seuls les fichiers de type jpeg, png, jpg sont autorisés.',
    'profilPicture.max' => 'La taille maximale de l’image doit être de 5MB.',
    'profilPicture.uploaded' => 'Le chargement de l\'image a échoué',
]);


updated(['profilPictureFilename' => fn() => $this->profilPictureFilename]);
layout('layouts.auth');

$save = function () {

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
    $this->user->bio = $this->bio;
    if ($this->bio !== '') {
        $user = Auth::user()->displayedInformationsOnce()->bio = true;
    }

    $this->user->job = $this->job;
    $this->user->region = $this->region;
    $this->user->level = $this->level;
    $this->user->setup_completed = true;
    $this->user->save();

    $this->redirect(route('dashboard', absolute: false), navigate: true);
};

?>
<div class="flex min-h-full flex-col items-center py-16 sm:px-6 lg:px-8">
    <x-slot name="h1">
        Choix du type de compte
    </x-slot>
    <livewire:partials.auth-header/>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <nav aria-label="Progress">
            <ol job="list" class="flex justify-center items-center">
                <li class="relative pr-8 sm:pr-20">
                    <!-- Completed Step -->
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="h-0.5 w-full bg-indigo-600"></div>
                    </div>
                    <a href="{{route('pages.profil-creation.account-type')}}" class="relative flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 hover:bg-indigo-900">
                        <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                        </svg>
                        <span class="sr-only">Étape 1</span>
                    </a>
                </li>
                <li class="relative pr-8 sm:pr-20">
                    <!-- Completed Step -->
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="h-0.5 w-full bg-indigo-600"></div>
                    </div>
                    <a href="{{route('pages.profil-creation.general-info')}}" class="relative flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 hover:bg-indigo-900">
                        <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                        </svg>
                        <span class="sr-only">Étape 2</span>
                    </a>
                </li>
                <li class="relative">
                    <!-- Current Step -->
                    <div class="absolute flex items-center" aria-hidden="true">
                        <div class="h-0.5 w-full bg-gray-200"></div>
                    </div>
                    <a href="#" class="relative flex h-8 w-8 items-center justify-center rounded-full border-2 border-indigo-600 bg-white" aria-current="step">
                        <span class="h-2.5 w-2.5 rounded-full bg-indigo-600" aria-hidden="true"></span>
                        <span class="sr-only">Étape 3</span>
                    </a>
                </li>
            </ol>
        </nav>
        <p class="mt-6 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Etape 3 : Dites-moi en
            plus sur vous</p>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[720px]">
        <div class="bg-white px-6 py-12 shadow sm:rounded-lg sm:px-12">
            <form wire:submit="save" class="space-y-6">
                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="col-span-3">
                        <label for="profil-picture" class="block text-sm font-medium leading-6 text-gray-900">
                            Photo de profil</label>
                        <div class="mt-2 flex flex-col items-center  rounded-lg border border-dashed border-gray-900/25 px-6 py-4">
                            <div class="text-center">
                                <div class="profil-picture flex items-center justify-center"></div>
                                <p class="flex justify-center items-center">{{ $profilPictureFilename }}</p>
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


                    <div class="col-span-3">
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
                        @endif()


                        <div class="col-span-3 mb-4">
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
                        @if($type !== 'staff')
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
                                    {{ $user->account_type === 'team' ? 'Choisissez le niveau moyen de l\'équipe': 'Choisissez votre niveau moyen'}}</p>
                                {{--                                @endif--}}
                            </div>
                        @endif()

                        {{--                        <div class="col-span-3">--}}
                        {{--                            <label for="nationality" class="block text-sm font-medium leading-6 text-gray-900">Nationalité</label>--}}
                        {{--                            <select wire:model="nationality" id="nationality" name="nationality" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">--}}
                        {{--                                <option value="">-- choisissez votre nationalité --</option>--}}
                        {{--                                @foreach($nationalities as $nationality)--}}
                        {{--                                    <option value="{{ $nationality }}">{{ __('nationalities.'.$nationality) }}</option>--}}
                        {{--                                @endforeach--}}
                        {{--                            </select>--}}
                        {{--                            @error('nationality')--}}
                        {{--                            <p class="text-sm text-red-600 space-y-1 mt-2 mb-4"> {{ $message }}</p>--}}
                        {{--                            @enderror--}}
                        {{--                        </div>--}}

                    </div>


                    <div class="col-span-full">
                        <label for="bio" class="block text-sm font-medium leading-6 text-gray-900">Bio</label>
                        <div class="mt-2">
                            <textarea wire:model="bio" id="bio" name="bio" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                        </div>
                        @error('bio')
                        <p class="text-sm text-red-600 space-y-1 mt-2"> {{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm leading-6 text-gray-600">Write a few sentences about yourself.</p>
                    </div>


                </div>

                <div class="justify-center flex">
                    <button type="submit" class="flex justify-center rounded-md bg-rose-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Ajouter un compte riot
                    </button>
                </div>

                <div class="justify-center flex">
                    <button type="submit" class="flex justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Suivant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@script
<script>
    const profilPictureInput = document.querySelector('input[type=file]');
    const profilPictureIcon = document.querySelector('.profil-picture-icon');
    const profilPicture = document.querySelector('.profil-picture');

    profilPictureInput.addEventListener('change', (e) => {
        const file = profilPictureInput.files[0];
        if (!validFileType(file)) {
            $wire.profilPictureFilename = `Aucun fichier sélectionné`;
            alert('Le format du fichier n\'est pas valide');
            e.currentTarget.value = ''; // Réinitialiser le champ de fichier
        }

        const maxSize = 10 * 1024 * 1024; // 10 MB
        if (file.size > maxSize) {
            alert('La taille du fichier doit être inférieure à 10 MB.');
            e.currentTarget.value = ''; // Réinitialiser le champ de fichier
            return;
        }
        const image = document.createElement('img');
        image.src = URL.createObjectURL(file);
        image.alt = 'l\'image importé';
        image.width = 100;
        image.height = 100;
        image.className = 'flex justify-center';

        $wire.profilPictureFilename = `${file.name}`;

        // profilPictureIcon.remove();
        // profilPicture.insertAdjacentElement('afterbegin', image);
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
