<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/e-book');
});
Route::livewire('/e-book', 'pages::landing_page.welcome')->name('welcome');
Route::livewire('/beranda', 'pages::pelanggan.beranda');
