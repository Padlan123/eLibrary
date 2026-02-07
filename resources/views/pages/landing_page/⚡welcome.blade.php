<?php

use Livewire\Component;

new class extends Component {
    public function render()
    {
        return $this->view()->title('Readify');
    }
};
?>

<div>
    <section class="min-h-screen relative">
        <div class="bg-gray-900 backdrop-blur-sm absolute top-0 right-0 left-0 min-h-screen pb-24 sm:pb-32 md:pb-38 ">
            <div aria-hidden="true"
                class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80">
                <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"
                    class="relative left-[calc(50%-11rem)] aspect-1155/678 w-144.5 -translate-x-1/2 rotate-30 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-288.75">
                </div>
            </div>
            <div class="text-center mt-64">
                <h1 class="text-5xl font-bold tracking-tighter text-white">
                    Baca Buku Dimana Saja
                </h1>
                <p class="text-xl text-gray-400 tracking-tighter w-3/4 mx-auto pt-2">
                    Digilab E-Book adalah situs web baca buku online. Baca Buku Materi
                    Pelajaran, Baca Buku Cerita Novel, Baca Buku Self-Development, dalam
                    bahasa indonesia. Tanpa Iklan menganggu dan hanya di Readify
                </p>
                <div class="mt-10 flex items-center justify-center gap-x-6">
                    <a href="{{ route('register') }}"
                        class="rounded-md bg-indigo-500 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Daftar
                        Sekarang</a>
                    <a href="#" class="text-sm/6 font-semibold text-white">Pelajari lebih lanjut <ion-icon
                            class=" size-3" name="arrow-forward-outline"></ion-icon><span aria-hidden="true"></span></a>
                </div>
            </div>
            <div aria-hidden="true"
                class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]">
                <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"
                    class="relative left-[calc(50%+3rem)] aspect-1155/678 w-144.5 -translate-x-1/2 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%+36rem)] sm:w-288.75">
                </div>
            </div>
        </div>


    </section>

    <div class="py-24 pt-64 bg-gray-300/40 sm:py-48 lg:py-32 lg:mt-0">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-base/7 font-semibold text-indigo-400">Fitur</h2>
                <p class="mt-2 text-4xl font-semibold tracking-tight text-pretty sm:text-5xl lg:text-balance">
                    Semua buku tersedia dengan satu sentuhan</p>
                <p class="mt-6 text-lg/8"> Semuanya jadi lebih mudah dengan adanya web yang menyediakan
                    E-Book dengan fitur yang lengkap dan menyenangkan</p>
            </div>
            <div class="mx-auto mt-16 max-w-2xl sm:mt-20 md:pl-12 lg:mt-24 lg:max-w-4xl">
                <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-2 lg:gap-y-16">
                    <div class="relative pl-16">
                        <dt class="text-base font-semibold">
                            <div
                                class="absolute top-0 left-0 flex size-10 items-center justify-center rounded-lg bg-indigo-500">
                                <ion-icon class="size-6 text-white" name="search-outline"></ion-icon>
                            </div>
                            OPAC
                        </dt>
                        <dd class="mt-2 text-base text-gray-700">Memungkinkan pengunjung mencari buku dengan cepat
                            berdasarkan judul,
                            penulis, genre.</dd>
                    </div>
                    <div class="relative pl-16">
                        <dt class="text-base font-semibold">
                            <div
                                class="absolute top-0 left-0 flex size-10 items-center justify-center rounded-lg bg-indigo-500">
                                <ion-icon class="size-6 text-white" name="book-outline"></ion-icon>
                            </div>
                            Koleksi digital
                        </dt>
                        <dd class="mt-2 text-base text-gray-700">Menyediakan akses E-Book dalam format PDF, sehingga
                            pengguna tidak
                            harus pergi ke perpustakaan.dd>
                    </div>
                    <div class="relative pl-16">
                        <dt class="text-base font-semibold">
                            <div
                                class="absolute top-0 left-0 flex size-10 items-center justify-center rounded-lg bg-indigo-500">
                                <ion-icon class="size-6 text-white" name="person-outline"></ion-icon>
                            </div>
                            Akun Pengguna
                        </dt>
                        <dd class="mt-2 text-base text-gray-700">Memudahkan peminjaman E-buku secara online, lengkap
                            dengan riwayat
                            peminjaman dan perpanjangan.</dd>
                    </div>
                    <div class="relative pl-16">
                        <dt class="text-base font-semibold">
                            <div
                                class="absolute top-0 left-0 flex size-10 items-center justify-center rounded-lg bg-indigo-500">
                                <ion-icon class="size-6 text-white" name="lock-open-outline"></ion-icon>
                            </div>
                            Fitur Premium
                        </dt>
                        <dd class="mt-2 text-base text-gray-700">Pengguna dapat mengakses ke seluruh koleksi E-Book
                            yang lebih luas
                            dibandingkan versi gratis.</dd>
                    </div>
                </dl>

            </div>
        </div>
    </div>

    @livewire('footer')
</div>
