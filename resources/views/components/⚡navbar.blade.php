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
            <div class="flex items-center space-x-1">
                <ion-icon name="book-outline" class="text-xl text-gray-100"></ion-icon>
                <h1 class="text-xl font-semibold text-gray-100">READIFY</h1>
            </div>
            @guest
                <div class="flex items-center gap-6">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-white hover:text-gray-200">Masuk</a>
                    <a href="{{ route('register') }}"
                        class="bg-white text-blue-500 px-3 py-1 rounded-md text-sm font-medium hover:bg-gray-100">Daftar</a>
                </div>
            @endguest

            @auth
                <span class="flex items-center gap-6">
                    <div class="flex gap-5">
                        <a href="{{ route('home.anggota') }}"
                            class="text-sm font-medium text-white hover:text-gray-200">Beranda</a>
                        <a href="" class="text-sm font-medium text-white hover:text-gray-200">Berlangganan</a>
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
                    {{-- <button type="submit" wire:click="logout" wire:confirm="yakin kamu logout"
                        class="bg-red-500 text-white px-3 py-1 rounded-md text-sm font-medium hover:bg-red-400">
                        Logout
                    </button> --}}
                @endauth
            </span>
        </nav>
    </header>
</div>
