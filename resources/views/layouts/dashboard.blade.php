<?php

use function Livewire\Volt\{state};

?>

    <!DOCTYPE html>
<html class="h-full bg-gray-50" lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full">
<x-toaster-hub />
<h1 class="sr-only">{{ $h1 }}</h1>
<livewire:partials.app-side-menu/>
{{ $slot }}
<livewire:partials.app-aside/>
@livewireScripts
</body>
</html>
