<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/e-book');
});
Route::livewire('/e-book', 'pages::landing_page.welcome')->name('welcome');
Route::livewire('/login', 'pages::auth.login')->name('login');
Route::livewire('/register', 'pages::auth.register')->name('register');

Route::prefix('anggota')->middleware(['auth', 'role:anggota'])->name('anggota.')->group(function () {
    Route::livewire('/home', 'pages::anggota.home')->name('home');
    Route::livewire('/langganan', 'pages::anggota.form-berlangganan')->name('berlangganan');
});
