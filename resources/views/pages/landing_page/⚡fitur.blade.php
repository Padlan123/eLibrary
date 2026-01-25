<?php

use Livewire\Component;

new class extends Component {
    public function render()
    {
        return $this->view()->title('Fitur');
    }
};
?>
<div>
    @livewire('landing_page.navbar')
    <div class="bg-gray-900 py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:text-center">
                <h2 class="text-base/7 font-semibold text-indigo-400">Fitur</h2>
                <p class="mt-2 text-4xl font-semibold tracking-tight text-pretty text-white sm:text-5xl lg:text-balance">
                    Semua buku tersedia dengan satu sentuhan</p>
                <p class="mt-6 text-lg/8 text-gray-300">Semuanya jadi lebih mudah dengan adanya web yang menyediakan
                    e-book dengan fitur yang lengkap dan menyenangkan</p>
            </div>
            <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-4xl">
                <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-2 lg:gap-y-16">
                    <div class="relative pl-16">
                        <dt class="text-base/7 font-semibold text-white">
                            OPAC
                        </dt>
                        <dd class="mt-2 text-base/7 text-gray-400">Memungkinkan pengunjung mencari buku dengan cepat
                            berdasarkan judul, penulis, genre </dd>
                    </div>
                    <div class="relative pl-16">
                        <dt class="text-base/7 font-semibold text-white">
                            Koleksi Digital
                        </dt>
                        <dd class="mt-2 text-base/7 text-gray-400">Menyediakan akses ke e-book dalam format PDF,
                            sehingga pengunjung tidak harus pergi ke perpustakaan</dd>
                    </div>
                    <div class="relative pl-16">
                        <dt class="text-base/7 font-semibold text-white">
                            Akun Pengguna
                        </dt>
                        <dd class="mt-2 text-base/7 text-gray-400">Memudahkan peminjaman buku secara online, lengkap
                            dengan riwayat peminjaman dan perpanjangan</dd>
                    </div>
                    <div class="relative pl-16">
                        <dt class="text-base/7 font-semibold text-white">
                            Fitur Premium
                        </dt>
                        <dd class="mt-2 text-base/7 text-gray-400">Pengguna dapat mengakses ke seluruh koleksi e-book
                            yang lebih luas dibanding versi gratis
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

</div>
