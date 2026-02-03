<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});
Route::livewire('/login', 'pages::auth.login')->name('login');
Route::livewire('/register', 'pages::auth.register')->name('register');

Route::prefix('anggota')->middleware(['auth', 'role:anggota'])->group(function () {
    Route::livewire('/home', 'pages::anggota.home')->name('home.anggota');
});
