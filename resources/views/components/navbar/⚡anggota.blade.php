<?php

use Livewire\Component;

new class extends Component {
    public $initials = '';

    public function mount()
    {
        $words = explode(' ', auth()->user()->username);
        $initial = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));

        $this->initials = $initial;
    }

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
        <nav class="max-w-7xl mx-auto px-6 md:px-6 py-3 flex items-center gap-4 justify-between"
            aria-label="navigasi utama">
            @unless (request()->routeIs('anggota.subscriptions'))
                <div class="flex items-center justify-center gap-4">
                    <img src="{{ asset('img/logo-Readify.webp') }}" alt="" class="size-10 bg-white rounded-lg">

                    <span class="text-xl text-white font-semibold tracking-wide hidden md:inline">
                        READIFY
                    </span>
                </div>
            @else
                <div class="flex items-center justify-center gap-4">
                    <span class="text-xl text-white font-semibold tracking-wide">
                        READIFY STORE
                    </span>
                </div>
            @endunless
            <div class="flex items-center gap-4">
                @unless (request()->routeIs('anggota.subscriptions') ||
                        request()->routeIs('anggota.profil') ||
                        request()->routeIs('anggota.transaction.history') ||
                        request()->routeIs('anggota.invoice'))
                    <form role="search" class="flex">
                        <label for="search" class="sr-only"> Cari buku </label>

                        <input id="search" type="search" placeholder="Cari judul atau kategori"
                            class="w-56 md:w-56 lg:w-72 px-3 py-1 md:py-1.5 text-sm bg-gray-100 focus:bg-white rounded-l-lg outline-none border-none transition" />

                        <button type="submit" aria-label="Cari buku"
                            class="px-3 bg-gray-100 hover:bg-white rounded-r-lg flex items-center justify-center transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-4 text-gray-700">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </button>
                    </form>
                @endunless

                <ul class="md:flex items-center gap-4">
                    @unless (request()->routeIs('anggota.subscriptions') ||
                            request()->routeIs('anggota.profil') ||
                            request()->routeIs('anggota.transaction.history') ||
                            request()->routeIs('anggota.invoice'))
                        <li class="hidden md:flex">
                            <a href="{{ route('anggota.subscriptions') }}"
                                class="flex items-center gap-2 px-3 py-1 rounded-lg text-gray-600 hover:text-gray-700 bg-white/90 hover:bg-white backdrop-blur-sm transition">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-4 text-yellow-500">
                                    <path fill-rule="evenodd"
                                        d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>Berlangganan</span>
                            </a>
                        </li>
                    @endunless

                    <li x-data="{ open: false }" @click.outside="open = false">
                        <a @click="open = !open" aria-label="profil pengguna"
                            class="size-10 rounded-full ring-1 ring-white hover:ring-blue-300 transition flex items-center justify-center bg-white/20 cursor-pointer">
                            <span class="text-white text-lg font-bold">
                                {{ $initials }}
                            </span>
                        </a>
                        <div x-show="open" x-cloak
                            class="z-10 fixed right-7 top-18 bg-neutral-primary-medium border border-default-medium rounded-base divide-y divide-default-medium shadow-lg w-44">
                            <ul class="p-2 text-sm text-body font-medium" aria-labelledby="dropdownDividerButton">

                                <li>
                                    <a href="{{ route('anggota.home') }}"
                                        class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">Beranda</a>
                                </li>
                                <li>
                                    <a href="{{ route('anggota.subscriptions') }}"
                                        class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">Berlangganan</a>
                                </li>
                                <li>
                                    <a href="{{ route('anggota.profil') }}"
                                        class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">Profil</a>
                                </li>
                            </ul>
                            <div class="p-2 text-sm text-body font-medium">
                                <a wire:click="logout" wire:confirm="apakah yakin ingin logout?"
                                    class="inline-flex items-center w-full p-2 gap-2 hover:bg-red-500/80 hover:text-slate-200 rounded"><svg
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                                    </svg>
                                    Logout</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
</div>
