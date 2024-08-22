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
    //PROFILE CREATION
    Volt::route('/profil-creation/general-info', 'pages.profil-creation.general-info')
        ->name('pages.profil-creation.general-info');
    Volt::route('/profil-creation/additional-info', 'pages.profil-creation.additional-info')
        ->name('pages.profil-creation.additional-info');
    Volt::route('/profil-creation/account-type', 'pages.profil-creation.account-type')
        ->name('pages.profil-creation.account-type');

    //PROFILE DASHBOARD
    Volt::route('/dashboard', 'pages.dashboard')
        ->name('dashboard');
    Volt::route('/members', 'pages.dashboard.members')
        ->name('members');
    Volt::route('/match-history', 'pages.dashboard.matchHistory')
        ->name('match-history');
    Volt::route('/stats', 'pages.dashboard.stats')
        ->name('stats');
    Volt::route('/guestbook', 'pages.dashboard.guestbook')
        ->name('guestbook');
    Volt::route('/mates', 'pages.dashboard.mates')
        ->name('mates');


    //ASIDE LINKS
    Volt::route('/find-teammate', 'pages.find-teammate')
        ->name('find-teammate');
    Volt::route('/messages', 'pages.message')
        ->name('messages');
    Volt::route('/messages/{conversation}', 'pages.conversation')
        ->name('conversation');
    Volt::route('/notifications', 'pages.notifications')
        ->name('notifications');
    Volt::route('/missions', 'pages.missions')
        ->name('missions');
    Volt::route('/settings', 'pages.settings')
        ->name('settings');
    Route::view('profile', 'profile')
        ->middleware(['auth'])
        ->name('profile');
});

require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {
Volt::route('/{user:username}', 'pages.user')
    ->name('user');
});

Route::middleware('auth')->group(function () {
    Volt::route('/{user:username}/members', 'pages.user.members')
        ->name('user-members');
    Volt::route('/{user:username}/guestbook', 'pages.user.guestbook')
        ->name('user-guestbook');
    Volt::route('/{user:username}/mates', 'pages.user.mates')
        ->name('user-mates');
    Volt::route('/{user:username}/stats', 'pages.user.stats')
        ->name('user-stats');
    Volt::route('/{user:username}/match-history', 'pages.user.matchHistory')
        ->name('user-match-history');
//    Volt::route('/{user:username}/salut', 'pages.user.')
//        ->name('user-');

});
