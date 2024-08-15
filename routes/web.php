<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;



Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    } else {
        return redirect('/');
    }
});

Volt::route('/realtime', 'pages.realtime')
    ->middleware('auth')
    ->name('realtime');

Volt::route('/welcome', 'pages.home')
    ->middleware('guest')
    ->name('home');



Route::middleware('auth')->group(function () {
    //testing things here
    Volt::route('/dashboard', 'pages.dashboard')
        ->name('dashboard');

    Volt::route('/member', 'pages.dashboard.member')
        ->name('member');

    Volt::route('/profil-creation/general-info', 'pages.profil-creation.general-info')
    ->name('pages.profil-creation.general-info');

    Volt::route('/profil-creation/additional-info', 'pages.profil-creation.additional-info')
        ->name('pages.profil-creation.additional-info');

    Volt::route('/profil-creation/account-type', 'pages.profil-creation.account-type')
        ->name('pages.profil-creation.account-type');

    Volt::route('/message', 'pages.message')
        ->name('message');

    Route::view('profile', 'profile')
        ->middleware(['auth'])
        ->name('profile');

    Volt::route('paramètre', 'pages.paramètre')
        ->name('paramètre');
});

require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {
Volt::route('/{user:username}', 'pages.user')
    ->name('user');
});
