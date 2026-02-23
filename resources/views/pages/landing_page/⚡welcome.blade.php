<?php

use Livewire\Component;

new class extends Component {
    public function mount()
    {
        if (auth()->check()) {
            $role = auth()->user()->getRoleNames()->first();
            return match ($role) {
                'admin' => redirect()->route('admin.dashboard'),
                'anggota' => redirect()->route('anggota.home'),
                default => redirect()->route('login'),
            };
        }
    }

    public function render()
    {
        return $this->view()->title('Readify');
    }
};
?>

<div>
    @livewire('navbar')
    <section id="home" class="bg-gray-900 min-h-screen flex items-center justify-center">
        <div class="relative isolate">
            <div aria-hidden="true"
                class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80">
                <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"
                    class="relative left-[calc(50%-11rem)] aspect-1155/678 w-144.5 -translate-x-1/2 rotate-30 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-288.75">
                </div>
            </div>
            <div class="text-center py-32">
                <h1 class="text-3xl font-bold tracking-tighter text-white mb-6 w-2/3 mx-auto md:text-4xl lg:text-5xl">
                    Baca Buku Dimana Saja
                </h1>
                <p class="text-sm text-gray-400 tracking-tighter w-3/4 mx-auto pt-2 md:text-base lg:text-lg">
                    Digilab E-Book adalah situs web baca buku online. Baca Buku Materi
                    Pelajaran, Baca Buku Cerita Novel, Baca Buku Self-Development, dalam
                    bahasa indonesia. Tanpa Iklan menganggu dan hanya di Readify
                </p>
                <div class="mt-10 flex items-center justify-center gap-x-6">
                    <a href="{{ route('register') }}"
                        class="rounded-md bg-indigo-500 p-1 text-xs font-semibold text-white shadow-xs hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 md:text-sm md:p-2 lg:text-base lg:p-3">Daftar
                        Sekarang</a>
                    <a href="#" class="text-xs font-semibold text-white md:text-sm lg:text-base">Pelajari lebih
                        lanjut
                        <ion-icon class=" size-3" name="arrow-forward-outline"></ion-icon><span
                            aria-hidden="true"></span></a>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-12 bg-gray-300/40">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mx-auto max-w-2xl text-center flex flex-col items-center gap-4">
                <h2 class="text-xs font-semibold text-indigo-400 md:text-sm lg:text-base">Fitur</h2>
                <p class="text-3xl font-semibold tracking-tight text-pretty md:text-4xl lg:text-4xl">
                    Semua buku tersedia dengan satu sentuhan</p>
                <p class="text-sm md:text-base lg:text-lg">Semuanya jadi lebih mudah dengan adanya web yang
                    menyediakan
                    E-Book dengan fitur yang lengkap dan menyenangkan</p>
            </div>
            <div class="mx-auto mt-16 max-w-2xl md:px-16 lg:max-w-full">
                <dl class="grid max-w-xl grid-cols-1 gap-y-10 lg:grid-cols-2 lg:gap-x-8 lg:max-w-full">
                    <div class="relative pl-16">
                        <dt class="text-sm font-semibold md:text-base lg:text-lg">
                            <div
                                class="absolute top-0 left-0 flex size-9 items-center justify-center rounded-lg bg-indigo-500 lg:size-10">
                                <ion-icon class="size-5 text-white lg:size-6" name="search-outline"></ion-icon>
                            </div>
                            OPAC
                        </dt>
                        <dd class="mt-2 text-xs text-gray-700 md:text-sm lg:text-base">Memungkinkan pengunjung mencari
                            buku dengan
                            cepat
                            berdasarkan judul,
                            penulis, genre.</dd>
                    </div>
                    <div class="relative pl-16">
                        <dt class="text-sm font-semibold md:text-base lg:text-lg">
                            <div
                                class="absolute top-0 left-0 flex size-9 items-center justify-center rounded-lg bg-indigo-500 lg:size-10">
                                <ion-icon class="size-5 text-white lg:size-6" name="book-outline"></ion-icon>
                            </div>
                            Koleksi digital
                        </dt>
                        <dd class="mt-2 text-xs text-gray-700 md:text-sm lg:text-base">Menyediakan akses E-Book dalam
                            format PDF,
                            sehingga
                            pengguna tidak
                            harus pergi ke perpustakaan.</dd>
                    </div>
                    <div class="relative pl-16">
                        <dt class="text-sm font-semibold md:text-base lg:text-lg">
                            <div
                                class="absolute top-0 left-0 flex size-9 items-center justify-center rounded-lg bg-indigo-500 lg:size-10">
                                <ion-icon class="size-5 text-white lg:size-6" name="person-outline"></ion-icon>
                            </div>
                            Akun Pengguna
                        </dt>
                        <dd class="mt-2 text-xs text-gray-700 md:text-sm lg:text-base">Memudahkan pengguna untuk membaca
                            E-buku
                            secara online,
                            lengkap
                            dengan riwayat
                            bacaan dan pengaturan pribadi.</dd>
                    </div>
                    <div class="relative pl-16">
                        <dt class="text-sm font-semibold md:text-base lg:text-lg">
                            <div
                                class="absolute top-0 left-0 flex size-9 items-center justify-center rounded-lg bg-indigo-500 lg:size-10">
                                <ion-icon class="size-5 text-white lg:size-6" name="lock-open-outline"></ion-icon>
                            </div>
                            Fitur Premium
                        </dt>
                        <dd class="mt-2 text-xs text-gray-700 md:text-sm lg:text-base">Pengguna dapat mengakses ke
                            seluruh koleksi
                            E-Book
                            yang lebih luas
                            dibandingkan versi gratis.</dd>
                    </div>
                </dl>

            </div>
        </div>
    </section>

    <section id="tentang" class="py-16 bg-gray-100">
        <div class="mx-auto px-6 text-center">
            <h2 class="text-2xl font-bold mb-8 text-gray-800 md:text-3xl">Tentang Kami</h2>
            <p class="text-base text-gray-600 mx-auto leading-relaxed md:text-lg lg:max-w-4xl">
                READIFY lahir dari passion untuk membuat literatur lebih mudah
                diakses. Kami menyediakan platform baca buku online yang bebas iklan,
                dengan koleksi lengkap dari berbagai genre. Komitmen kami adalah
                memberikan pengalaman membaca yang imersif dan tanpa gangguan,
                sehingga Anda bisa fokus pada cerita dan pengetahuan yang Anda cintai.
            </p>
        </div>
    </section>

    @livewire('footer')
</div>
