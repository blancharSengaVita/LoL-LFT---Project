<?php

use function Livewire\Volt\{state};
use function Livewire\Volt\layout;
use function Livewire\Volt\{usesFileUploads};

usesFileUploads();

state([
    'profilPictureLabel' => 'importer une photo de profil',
    'image' => '',
    'nationalities' => require __DIR__ . '/../../../../../app/enum/nationalities.php',
    'regions' => require __DIR__ . '/../../../../../app/enum/regions.php',
    'roles' => require __DIR__ . '/../../../../../app/enum/roles.php',
]);

layout('layouts.auth');

?>

<div class="flex min-h-full flex-col items-center py-16 sm:px-6 lg:px-8">
    <x-slot name="h1">
        Choix du type de compte
    </x-slot>
    <livewire:partials.auth-header/>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <nav aria-label="Progress">
                <ol role="list" class="flex justify-center items-center">
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
                            <label for="location" class="block text-sm font-medium leading-6 text-gray-900">Nom affiché</label>
                            <select id="location" name="location" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </select>
                        </div>

                        <div class="col-span-3">
                            <label for="nationality" class="block text-sm font-medium leading-6 text-gray-900">Nationalité</label>
                            <select  id="nationality" name="nationality" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option value="">-- choisissez votre nationalité --</option>
                                @foreach($nationalities as $nationality)
                                    <option value="{{ $nationality }}">{{ __('nationalities.'.$nationality) }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="col-span-3">
                        <label for="profil-picture" class="block text-sm font-medium leading-6 text-gray-900">
                            Photo de profil</label>
                        <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-4">
                            <div class="text-center">
                                <div class="profil-picture flex justify-center"></div>
                                <svg class="mx-auto h-12 w-12 text-gray-300 profil-picture-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd"/>
                                </svg>
                                <div class="mt-4 justify-center flex text-sm leading-6 text-gray-600">
                                    <label for="file-upload" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                                        <span>{{ $profilPictureLabel }}</span>
                                        <input id="file-upload" name="file-upload" type="file" class="sr-only">
                                    </label>
                                </div>
                                <p class="text-xs leading-5 text-gray-600">PNG, JPG, GIF en-dessous de 10MB</p>
                            </div>
                        </div>
                    </div>


                    <div class="col-span-full">
                        <label for="about" class="block text-sm font-medium leading-6 text-gray-900">About</label>
                        <div class="mt-2">
                            <textarea id="about" name="about" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                        </div>
                        <p class="mt-3 text-sm leading-6 text-gray-600">Write a few sentences about yourself.</p>
                    </div>

                    <div class="col-span-3">
                        <label for="role" class="block text-sm font-medium leading-6 text-gray-900">Rôle</label>
                        <select id="role" name="role" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option value="">-- choisissez votre rôle --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}">{{ __('roles.'.$role) }}</option>
                            @endforeach
                        </select>
                    </div>

{{--                    <div class="col-span-3">--}}
{{--                        <label for="ambition" class="block text-sm font-medium leading-6 text-gray-900">Ambition</label>--}}
{{--                        <select id="ambition" name="ambition" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">--}}
{{--                            <option>United States</option>--}}
{{--                            <option selected>Canada</option>--}}
{{--                            <option>Mexico</option>--}}
{{--                        </select>--}}
{{--                    </div>--}}

                    <div class="col-span-3 mb-8">
                        <label for="region" class="block text-sm font-medium leading-6 text-gray-900">Région</label>
                        <select id="region" name="region" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option value="" >-- choisissez votre region --</option>
                            @foreach($regions as $region)
                                <option value="{{ $region }}">{{ $region }}</option>
                            @endforeach
                        </select>
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

    profilPictureInput.addEventListener('change', () => {
        const file = profilPictureInput.files[0];
        console.log(file);
        if (validFileType(file)) {
            $wire.profilPictureLabel = `${file.name}`;

            const div = document.createElement('div');
            div.className = 'mx-auto h-12 w-12 flex justify-center';

            const image = document.createElement('img');
            image.src = URL.createObjectURL(file);
            image.alt = 'l\'image importé';
            image.width = 50;
            image.height = 50;
            image.className = 'flex justify-center';

            profilPictureIcon.remove();
            profilPicture.insertAdjacentElement('afterbegin', image);
            console.log(image);
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

    function returnFileSize (number) {
        if (number < 1024) {
            return `${number} bytes`;
        } else if (number >= 1024 && number < 1048576) {
            return `${(number / 1024).toFixed(1)} KB`;
        } else if (number >= 1048576) {
            return `${(number / 1048576).toFixed(1)} MB`;
        }
    }

</script>
@endscript
