<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    <footer class="bg-gray-800 p-12">
        <div class="items-center mx-2">
            <a href="#" class="text-2xl tracking-widest text-white">READIFY</a>
        </div>
        <div class="grid lg:grid-cols-4 sm:grid-cols-2 items-start mt-4">
            <!-- //// -->
            <ul class="flex flex-col tracking-tighter text-gray-200">
                <li class="px-2 py-1 mb-2 sm:mt-2 sm:mb-1">
                    <h3 class="text-sm text-gray-500">Navigasi</h3>
                </li>
                <li class="px-2 py-1"><a href="#" class="text-base/7">Beranda</a></li>
                <li class="px-2 py-1"><a href="#" class="text-base/7">Fitur</a></li>
                <li class="px-2 py-1">
                    <a href="#" class="text-base/7">Tentang kami</a>
                </li>
                <li class="px-2 py-1">
                    <a href="#" class="text-sm">Kembali ke atas &ShortUpArrow;</a>
                </li>
            </ul>
            <!-- //// -->
            <ul class="flex flex-col tracking-tighter text-gray-200">
                <li class="px-2 py-1 mb-2 sm:mt-2 sm:mb-1">
                    <h3 class="text-sm text-gray-500">Tentang</h3>
                </li>
                <li class="px-2 py-1">
                    <a href="#" class="text-base/7">Tentang kami</a>
                </li>
                <li class="px-2 py-1">
                    <a href="#" class="text-base/7">Sosial media</a>
                </li>
                <li class="px-2 py-1">
                    <a href="#" class="text-base/7">E-Buku Kami</a>
                </li>
                <li class="px-2 py-1">
                    <a href="#" class="text-base/7">Kontak Kami</a>
                </li>
            </ul>
            <!-- //// -->
            <ul class="flex flex-col tracking-tighter text-gray-200">
                <li class="px-2 py-1 mb-2 sm:mt-2 sm:mb-1">
                    <h3 class="text-sm text-gray-500">Akun</h3>
                </li>
                <li class="px-2 py-1">
                    <a href="#" class="text-base/7">Masuk kembali</a>
                </li>
                <li class="px-2 py-1">
                    <a href="#" class="text-base/7">Daftar akun</a>
                </li>
                <li class="px-2 py-1">
                    <a href="#" class="text-base/7">Membership</a>
                </li>
            </ul>
            <!-- //// -->
            <ul class="flex flex-col tracking-tighter text-gray-200">
                <li class="px-2 py-1 mb-2 sm:mt-2 sm:mb-1">
                    <h3 class="text-sm text-gray-500">Pengembang</h3>
                </li>
                <li class="px-2 py-1">
                    <a href="#" class="text-base/7">Muhammad Rizki ( project manager )</a>
                </li>
                <li class="px-2 py-1">
                    <a href="#" class="text-base/7">Eka wirayuda ( front-end )</a>
                </li>
                <li class="px-2 py-1">
                    <a href="#" class="text-base/7">Muhammad Padlan ( Back-end )</a>
                </li>
            </ul>
        </div>

        @livewire('social_media')

    </footer>
</div>
