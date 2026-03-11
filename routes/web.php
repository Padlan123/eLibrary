<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('welcome');
});
Route::prefix('/Readify')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/', function () {
            return redirect()->route('welcome');
        });
        Route::get('/home', function () {
            return view('welcome');
        })->name('welcome');
        Route::livewire('/login', 'pages::auth.login')->name('login');
        Route::livewire('/register', 'pages::auth.register')->name('register');
    });

    Route::middleware('auth')->group(function () {
        Route::prefix('anggota')->middleware('role:anggota')->name('anggota.')->group(function () {
            Route::get('/', function () {
                return redirect()->route('anggota.home');
            });
            Route::livewire('/home', 'pages::anggota.home')->name('home');
            Route::livewire('/langganan', 'pages::anggota.form-berlangganan')->name('subscriptions');
            Route::livewire('profil', 'pages::anggota.profil')->name('profil');
        });
        Route::prefix('admin')->middleware('role:admin')->name('admin.')->group(function () {
            Route::get('/', function () {
                return redirect()->route('admin.dashboard');
            });
            Route::livewire('/dashboard', 'pages::admin.dashboard')->name('dashboard');
            Route::livewire('/ebook', 'pages::admin.books')->name('books');
            Route::livewire('/pengguna', 'pages::admin.users')->name('users');
            Route::livewire('/langganan', 'pages::admin.subscriptions')->name('subscriptions');
            Route::get('/laporan/penjualan/download', [ReportController::class, 'download'])
                ->name('report.transactions.download');
        });
    });
});
