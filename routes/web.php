<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::landing_page.welcome')->name('welcome');
Route::livewire('/fitur', 'pages::landing_page.fitur')->name('fitur');
Route::livewire('/beranda', 'pages::pelanggan.beranda');
