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
	mount
};

usesFileUploads();
ini_set('memory_limit', '256M');
//post_max_size = 20M
//upload_max_filesize = 20M

state([
	'displayNames' => [],
	'nationalities' => [],
	'jobs' => [],
	'regions' => [],
	'displayName' => '',
	'profilPictureLabel' => 'importer une photo de profil',
	'profilPictureFilename' => 'Aucun fichier sélectionné',
	'profilPicture',
	'nationality' => '',
	'bio' => '',
	'job' => '',
	'region' => '',
]);

mount(function () {
	$user = Auth::user();
	$this->displayNames = [$user->game_name, $user->firstname . ' ' . $user->lastname, $user->firstname . ' "' . $user->game_name . '" ' . $user->lastname];
	$this->nationalities = require __DIR__ . '/../../../../../app/enum/nationalities.php';
	$this->jobs = require __DIR__ . '/../../../../../app/enum/jobs.php';
	$this->regions = require __DIR__ . '/../../../../../app/enum/regions.php';
});

rules([
	'displayName' => 'required',
	'nationality' => 'required',
	'bio' => 'required',
	'job' => 'required',
	'region' => 'required',
	'profilPicture' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:5120',
])->messages([
	'displayName.required' => 'Votre nom affiché est requis',
	'nationality.required' => 'Votre nationalité est requis',
	'bio.required' => 'Votre bio est requis',
	'job.required' => 'Votre job est requis',
	'region.required' => 'Votre region est requis',
	'profilPicture.image' => 'Le fichier n\'est pas une image',
	'profilPicture.mimes' => 'Seuls les fichiers de type jpeg, png, jpg sont autorisés.',
	'profilPicture.max' => 'La taille maximale de l’image doit être de 5MB.',
	'profilPicture.uploaded' => 'Le chargement de l\'image a échoué',
]);

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
	$user = Auth::user();
	$user->displayed_name = $this->displayName;
	$user->nationality = $this->nationality;
	$user->bio = $this->bio;
	$user->job = $this->job;
	$user->region = $this->region;
	$user->save();

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
                    <a href="#" class="relative flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 hover:bg-indigo-900">
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
                    <a href="#" class="relative flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 hover:bg-indigo-900">
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
                        <div class="col-span-3 mb-8">
                            <label for="displayName" class="block text-sm font-medium leading-6 text-gray-900">Nom
                                affiché </label>
                            <select wire:model="displayName" id="displayName" name="displayName" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option value="">-- choisissez votre nom affiché --</option>
                                @foreach($this->displayNames as $name)
                                    <option value="{{ $name }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('displayName')
                            <p class="text-sm text-red-600 space-y-1 mt-2 mb-4"> {{ $message }}</p>
                            @enderror
                        </div>


                        <div class="col-span-3">
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

                    </div>

                    <div class="col-span-3">
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

                    <div class="col-span-3">
                        <label for="job" class="block text-sm font-medium leading-6 text-gray-900">Rôle</label>
                        <select wire:model="job" id="job" name="job" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option value="">-- choisissez votre rôle --</option>
                            @foreach($jobs['player'] as $job)
                                <option value="{{ $job }}">{{ __($job) }}</option>
                            @endforeach
                        </select>
                        @error('job')
                        <p class="text-sm text-red-600 space-y-1 mt-2 mb-4"> {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-3 mb-8">
                        <label for="region" class="block text-sm font-medium leading-6 text-gray-900">Région</label>
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

        <p class="mt-10 text-center text-sm text-gray-500">
            <a href="{{ route('dashboard') }}" wire:navigate class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Ignorez</a>
        </p>
    </div>
</div>

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
