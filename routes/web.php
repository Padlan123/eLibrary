<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('welcome');
});
Route::prefix('/Readify')->group(function () {

    Route::get('/', function () {
        return redirect()->route('welcome');
    });
    Route::livewire('/home', 'pages::landing_page.welcome')->name('welcome');
    Route::livewire('/login', 'pages::auth.login')->name('login');
    Route::livewire('/register', 'pages::auth.register')->name('register');

    Route::middleware('auth')->group(function () {
        Route::prefix('anggota')->middleware('role:anggota')->name('anggota.')->group(function () {
            Route::get('/', function () {
                return redirect()->route('anggota.home');
            });
            Route::livewire('/home', 'pages::anggota.home')->name('home');
            Route::livewire('/langganan', 'pages::anggota.form-berlangganan')->name('berlangganan');
            Route::livewire('profil', 'pages::anggota.profil')->name('profil');
        });
        Route::prefix('admin')->middleware('role:admin')->name('admin.')->group(function () {
            Route::get('/', function () {
                return redirect()->route('admin.dashboard');
            });
            Route::livewire('/dashboard', 'pages::admin.dashboard')->name('dashboard');
            Route::livewire('/ebook', 'pages::admin.books')->name('books');
            Route::livewire('/pengguna', 'pages::admin.pengguna')->name('pengguna');
        });
    });
});
