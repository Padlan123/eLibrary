<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public function logout()
    {
        Auth::logout();
        return redirect()->route('welcome');
    }
};
?>

<div>
    <header class="fixed top-0 w-full shadow-md bg-linear-to-r from-blue-500 via-blue-400 to-blue-400 z-50">
        <nav class="max-w-7xl mx-auto px-8 py-4 flex items-center justify-between">
            @guest
                <div>
                    <h1 class="text-xl font-bold text-white">Readify</h1>
                </div>
                <div class="flex items-center gap-6">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-white hover:text-gray-200">Masuk</a>
                    <a href="{{ route('register') }}"
                        class="bg-white text-blue-500 px-3 py-1 rounded-md text-sm font-medium hover:bg-gray-100">Daftar</a>
                </div>
            @endguest

            @auth
                <div class="flex items-center space-x-1">

                    <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                        class="inline-flex items-center justify-center text-white bg-brand box-border border border-transparent hover:bg-brand-strong shadow-xs font-medium rounded-full text-sm px-3.5 py-1.5 focus:outline-none"
                        type="button">
                        <span class="text-2xl font-bold">R</span>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="dropdown"
                        class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-44 transform translate-x-1/3 mt-2">
                        <ul class="p-2 text-sm text-body font-medium" aria-labelledby="dropdownDefaultButton">
                            <li>
                                <a href="{{ route('anggota.profil') }}"
                                    class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">Profil</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">Buku
                                    Favorit</a>
                            </li>
                            <hr class="mb-4">
                            <li>
                                <button type="submit" wire:click="logout" wire:confirm="yakin kamu logout"
                                    class="bg-red-500 text-white rounded-md w-full font-medium hover:bg-red-400 py-2 ">
                                    Logout
                                </button>
                            </li>
                        </ul>
                    </div>

                </div>
                <span class="flex items-center gap-6">
                    <div class="flex gap-5">
                        <a href="{{ route('anggota.home') }}"
                            class="text-sm font-medium text-white hover:text-gray-200">Beranda</a>
                        <a href="{{ route('anggota.berlangganan') }}"
                            class="text-sm font-medium text-white hover:text-gray-200">Berlangganan</a>
                    </div>
                    <form class="flex items-center gap-2">
                        <input type="text" placeholder="Masukan judul, pengarang, genre"
                            class="px-2 bg-gray-100 py-1 w-64 rounded-full ring-1 ring-blue-500 focus:ring-blue-300 outline-none" />
                        <button type="submit" class="bg-gray-100 p-1 rounded-full ring-1 focus:ring-blue-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                class="w-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </button>
                    </form>
                </span>
            @endauth
        </nav>
    </header>
</div>
