<?php

use Livewire\Component;

new class extends Component {
    public function logout()
    {
        Auth::logout();
        return redirect()->route('welcome');
    }
};
?>

<div>
    <header
        class="fixed top-0 w-full bg-linear-to-l from-blue-500/95 to-blue-600/95 backdrop-blur-md shadow-md z-100 py-1">
        <nav class="max-w-7xl mx-auto px-4 md:px-6 py-3 flex items-center justify-between" aria-label="navigasi utama">
            <a wire:click="logout" href="/" aria-label="Beranda Readify"
                class="flex items-center justify-center gap-4">
                <img src="{{ asset('img/logo-Readify.webp') }}" alt="" class="size-12 bg-white rounded-lg">

                <span class="text-xl text-white font-semibold tracking-wide">
                    READIFY
                </span>
            </a>

            <div class="flex items-center gap-4">
                <form role="search" class="flex">
                    <label for="search" class="sr-only"> Cari buku </label>

                    <input id="search" type="search" placeholder="Cari judul atau kategori"
                        class="w-40 md:w-56 lg:w-72 px-3 py-1 md:py-1.5 text-sm bg-gray-100 focus:bg-white rounded-l-lg outline-none border-none transition" />

                    <button type="submit" aria-label="Cari buku"
                        class="px-3 bg-gray-100 hover:bg-white rounded-r-lg flex items-center justify-center transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-4 text-gray-700">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>

                    </button>
                </form>

                <ul class="hidden md:flex items-center gap-4">
                    <li>
                        <a href="#"
                            class="flex items-center gap-2 px-3 py-1 rounded-lg text-gray-600 hover:text-gray-700 bg-white/90 hover:bg-white backdrop-blur-sm transition">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="size-4 text-yellow-500">
                                <path fill-rule="evenodd"
                                    d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Premium</span>
                        </a>
                    </li>

                    <li>
                        <a href="#" aria-label="profil pengguna"
                            class="p-1 rounded-full ring-1 ring-white hover:ring-blue-300 transition flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="size-6 text-white">
                                <path fill-rule="evenodd"
                                    d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
</div>
