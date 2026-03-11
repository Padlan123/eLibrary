<div>
    <footer class="bg-gray-800 p-12">
        <div class="items-center mx-2">
            <a href="#" class="text-xl tracking-widest text-white md:text-2xl lg:text-3xl">READIFY</a>
        </div>
        <div class="grid items-start mt-4 grid-cols-2 gap-y-4 gap-x-12 tracking-tight leading-5 md:grid-cols-4">
            <!-- //// -->
            <ul class="flex flex-col text-gray-200">
                <li class="px-2 py-1 mb-2 sm:mt-2 sm:mb-1">
                    <h3 class="text-xs text-gray-500 lg:text-sm">Navigasi</h3>
                </li>
                <li class="px-2 py-1"><a href="#" class="text-sm lg:text-lg">Beranda</a></li>
                <li class="px-2 py-1"><a href="#" class="text-sm lg:text-lg">Fitur</a></li>
                <li class="px-2 py-1">
                    <a href="#" class="text-sm lg:text-lg">Tentang kami</a>
                </li>
            </ul>
            <!-- //// -->
            <ul class="flex flex-col text-gray-200">
                <li class="px-2 py-1 mb-2 sm:mt-2 sm:mb-1">
                    <h3 class="text-xs text-gray-500 lg:text-sm">Tentang</h3>
                </li>
                <li class="px-2 py-1">
                    <a href="#tentang" class="text-sm lg:text-lg">Tentang kami</a>
                </li>
                <li class="px-2 py-1">
                    <a href="#" class="text-sm lg:text-lg">Sosial media</a>
                </li>
                <li class="px-2 py-1">
                    <a href="#" class="text-sm lg:text-lg">E-Buku Kami</a>
                </li>
                <li class="px-2 py-1">
                    <a href="#" class="text-sm lg:text-lg">Kontak Kami</a>
                </li>
            </ul>
            <!-- //// -->
            <ul class="flex flex-col text-gray-200">
                <li class="px-2 py-1 mb-2 sm:mt-2 sm:mb-1">
                    <h3 class="text-xs text-gray-500 lg:text-sm">Akun</h3>
                </li>
                <li class="px-2 py-1">
                    <a href="{{ route('login') }}" class="text-sm lg:text-lg" title="masuk akun">Masuk akun</a>
                </li>
                <li class="px-2 py-1">
                    <a href="{{ route('register') }}" class="text-sm lg:text-lg" title="daftar akun">Daftar akun</a>
                </li>
                <li class="px-2 py-1">
                    <a href="#" class="text-sm lg:text-lg">Membership</a>
                </li>
            </ul>
            <!-- //// -->
            <ul class="flex flex-col text-gray-200">
                <li class="px-2 py-1 mb-2 sm:mt-2 sm:mb-1">
                    <h3 class="text-xs text-gray-500 lg:text-sm">Pengembang</h3>
                </li>
                <li class="px-2 py-1">
                    <p class="text-sm lg:text-lg">Muhammad Rizki <br> ( project manager )</p>
                </li>
                <li class="px-2 py-1">
                    <p class="text-sm lg:text-lg">Eka wirayuda <br> ( front-end )</p>
                </li>
                <li class="px-2 py-1">
                    <p class="text-sm lg:text-lg">Muhammad Padlan <br> ( Back-end )</p>
                </li>
            </ul>
        </div>
        <x-social_media></x-social_media>
    </footer>
</div>
