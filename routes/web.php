<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/e-book');
});
Route::livewire('/e-book', 'pages::landing_page.welcome')->name('welcome');
Route::livewire('/login', 'pages::auth.login')->name('login');
Route::livewire('/register', 'pages::auth.register')->name('register');

Route::prefix('anggota')->middleware(['auth', 'role:anggota'])->group(function () {
    Route::livewire('/home', 'pages::anggota.home')->name('home.anggota');
});
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::livewire('/dashboard', 'pages::admin.dashboard')->name('admin.dashboard');
    Route::livewire('/ebook', 'pages::admin.ebook')->name('admin.ebook');
    Route::livewire('/pengguna', 'pages::admin.pengguna')->name('admin.pengguna');
});
