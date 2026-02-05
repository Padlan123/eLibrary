<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/e-book');
});
Route::livewire('/e-book', 'pages::landing_page.welcome')->name('welcome');
Route::livewire('/login', 'pages::auth.login')->name('login');
Route::livewire('/register', 'pages::auth.register')->name('register');
