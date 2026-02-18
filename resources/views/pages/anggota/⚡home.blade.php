<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Book;
use App\Models\Category;
use Livewire\WithPagination;
use App\Traits\WithRateLimiting;

new class extends Component {
    use WithPagination, WithRateLimiting;

    public $category = '';
    public bool $limit = false;

    #[Computed]
    public function categories()
    {
        return Category::orderBy('nama_kategori')->get();
    }

    public function updatingCategory()
    {
        if (!$this->checkRateLimit('filterBooks', max: 10, jedaWaktu: 60)) {
            $this->limit = true;
            return;
        }

        $this->limit = false;
        $this->resetErrorBag('throttle');
    }

    #[Computed]
    public function books()
    {
        if ($this->limit) {
            return collect();
        }
        $books = Book::query()
            ->with('categories')
            ->when($this->category, function ($query) {
                $query->whereHas('categories', function ($q) {
                    $q->where('categories.id', $this->category);
                });
            })
            ->latest()
            ->paginate(8);
        return $books;
    }

    public function render()
    {
        return $this->view()->title('Home');
    }
};
?>

<div>
    @livewire('navbar')
    <!-- Section Hero -->
    <section class="relative py-28 w-full"
        style="background-image: url(/img/background/dashboard-pelanggan.webp); background-size: cover; background-position: center;">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>

        <div class="relative max-w-7xl mx-auto px-8 py-28 text-center space-y-4">
            <div class="grid grid-cols-1 items-center space-y-2 tracking-tight animate-fade-in">
                <h2 class="text-5xl font-semibold text-gray-200">
                    Baca Buku Dimana Saja
                </h2>
                <p class="max-w-4xl mx-auto text-lg text-gray-300 leading-relaxed">
                    Digilab E-Book adalah situs web baca buku online. Buku Materi Pelajaran, Buku novel, Buku
                    Self-Development. Tanpa Iklan menganggu dan hanya di READIFY.
                </p>
            </div>

            <div class="flex justify-center gap-4 mt-6">
                <a href="#card"
                    class="inline-flex items-center justify-center h-9 px-4 text-base font-medium text-gray-100 rounded-full bg-blue-500 hover:bg-blue-600 transition">
                    Jelajahi Buku
                </a>
                <a href="#premium"
                    class="inline-flex items-center justify-center h-9 px-4 text-base font-medium text-gray-100 rounded-full bg-linear-to-r from-blue-400 via-blue-500 to-blue-600 hover:from-blue-500 hover:to-blue-700 transition">
                    Mulai Berlangganan
                </a>
            </div>
        </div>
    </section>

    <!-- Section Card Buku -->
    <section class="scroll-mt-12 py-12 bg-gray-100 w-full" id="card">
        <div class="max-w-7xl mx-auto px-8">
            <div class="mb-10">
                <h2 class="text-2xl font-semibold text-gray-800">Koleksi Buku</h2>
                <div class="flex items-end justify-between">
                    <p class="text-gray-500 leading-relaxed max-w-md">
                        Temukan berbagai koleksi buku digital yang tersedia.
                    </p>
                    <div class="mt-4">
                        <form class="max-w-sm mx-auto">
                            <label for="kategori" class="mb-2.5 text-sm font-medium text-heading">Pilih Kategori
                                Buku</label>
                            <select wire:model.live="category" id="kategori"
                                class="w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body">
                                <option value="">Semua Kategori</option>
                                @foreach ($this->categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                @error('throttle')
                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                        ⛔ {{ $message }}
                    </div>
                @enderror
                @forelse ($this->books as $book)
                    <figure
                        class="w-full p-2 flex bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl hover:-translate-y-1 transition ease-in-out duration-300 relative">
                        @if ($book->cover_image)
                            <img src="{{ url('storage/covers/' . $book->cover_image) }}" alt=""
                                class="object-cover object-center aspect-portrait w-28 rounded-lg" />
                        @else
                            <img src="{{ url('https://img.pikbest.com/origin/09/02/31/56bpIkbEsTFtz.jpg!f305cw') }}"
                                alt="" class="object-cover object-center aspect-portrait w-28 rounded-lg" />
                        @endif
                        <div class="flex flex-col justify-between px-3 w-full py-1">
                            <div class="space-y-3 pb-2">
                                <a href="#" class="flex items-center justify-between gap-2">
                                    <h4 class="text-lg text-gray-700 leading-tight">{{ $book->judul }}</h4>
                                    <p
                                        class="text-[12px] h-3 mr-1 font-medium text-gray-400 tracking-tight font-mono whitespace-nowrap">
                                        {{ $book->created_at->diffForHumans() }}</p>
                                </a>
                                <div class="flex items-center gap-2">
                                    @foreach ($book->categories as $kategori)
                                        <span>
                                            <p
                                                class="text-sm text-gray-700 bg-gray-200 rounded-full px-2 outline-none ring-1 ring-gray-200 transition ease-in duration-300 hover:scale-105">
                                                {{ $kategori->nama_kategori }}</p>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex items-center justify-between w-full mt-6">
                                <span class="text-sm text-gray-500">Tahun Terbit : {{ $book->tahun_terbit }}</span>
                                <a href="#"
                                    class="inline-flex items-center justify-center h-9 px-3 text-sm font-medium text-gray-100 bg-blue-500 hover:bg-blue-600 rounded-md transition relative">
                                    Baca buku
                                    @if ($book->premium)
                                        <span
                                            class="absolute -top-2 -right-2 bg-yellow-400 text-xs font-bold px-2 py-0.5 rounded-full shadow">Premium</span>
                                    @endif
                                </a>
                            </div>
                        </div>
                    </figure>
                @empty
                    <div class="col-span-full text-center py-10">
                        <p class="text-gray-500 text-lg">Tidak ada buku yang ditemukan untuk kategori ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Why readify -->
    {{-- <section class="py-12 bg-white w-full" id="why">
        <div class="max-w-7xl px-8 grid grid-cols-1 items-center space-y-6">
            <div>
                <span class="max-w-4xl flex flex-col items-center justify-center mx-auto text-center space-y-4">
                    <h3 class="text-xl text-gray-700 font-semibold">
                        Mengapa Readify?
                    </h3>
                    <p class="text-base text-gray-600 max-w-2xl">
                        Readify hadir sebagai platform perpustakaan digital yang
                        memudahkan pengguna menemukan, membaca, dan mengelola koleksi buku
                        secara online. Dirancang sederhana dan fungsional agar pengalaman
                        membaca terasa nyaman dan efisien.
                    </p>
                </span>
            </div>
            <div class="flex justify-center pb-6">
                <div class="grid grid-cols-2 max-w-4xl mx-auto gap-8">
                    <div class="tracking-tight p-2 m-2 h-full flex flex-col">
                        <span class="flex items-center space-x-1">
                            <ion-icon name="search-outline" class="text-xl text-blue-500"></ion-icon>
                            <h4 class="text-lg text-gray-700 leading-relaxed">OPAC</h4>
                        </span>
                        <span>
                            <p class="text-base/7 text-gray-600">
                                Fitur pencarian katalog yang membantu pengguna menemukan buku
                                berdasarkan judul, penulis, atau kategori secara cepat dan
                                terstruktur.
                            </p>
                        </span>
                    </div>
                    <div class="tracking-tight p-2 m-2 h-full flex flex-col">
                        <span class="flex items-center space-x-1">
                            <ion-icon name="book-outline" class="text-xl text-amber-800"></ion-icon>
                            <h4 class="text-lg text-gray-700 leading-relaxed">
                                Koleksi digital
                            </h4>
                        </span>
                        <span>
                            <p class="text-base/7 text-gray-600">
                                Menyediakan berbagai jenis buku digital mulai dari buku
                                pelajaran, novel, hingga pengembangan diri yang dapat diakses
                                kapan saja.
                            </p>
                        </span>
                    </div>
                    <div class="tracking-tight p-2 m-2 h-full flex flex-col">
                        <span class="flex items-center space-x-1">
                            <ion-icon name="person-outline" class="text-xl text-gray-500"></ion-icon>
                            <h4 class="text-lg text-gray-700 leading-relaxed">
                                Akun pengguna
                            </h4>
                        </span>
                        <span>
                            <p class="text-base/7 text-gray-600">
                                Setiap pengguna memiliki akun pribadi untuk menyimpan riwayat
                                bacaan, mengelola koleksi favorit, dan menyesuaikan pengalaman
                                membaca.
                            </p>
                        </span>
                    </div>
                    <div class="tracking-tight p-2 m-2 h-full flex flex-col">
                        <span class="flex items-center space-x-1">
                            <ion-icon name="star-outline" class="text-xl text-yellow-500"></ion-icon>
                            <h4 class="text-lg text-gray-700 leading-relaxed">Premium</h4>
                        </span>
                        <span>
                            <p class="text-base/7 text-gray-600">
                                Akses tambahan untuk menikmati fitur eksklusif seperti koleksi
                                khusus dan pengalaman membaca tanpa gangguan.
                            </p>
                        </span>
                    </div>
                </div>
            </div>
            <a href="#"
                class="mx-auto inline-block px-4 py-2 rounded-lg font-medium bg-linear-to-r from-blue-300/80 to-blue-400/80 text-blue-900 hover:from-blue-300 hover:to-blue-400 shadow-sm ring-1 ring-blue-300 transition">
                Lihat paket premium
            </a>
        </div>
    </section> --}}

    <!-- Section Berlangganan Premium -->
    <section class="py-16 bg-gray-50 w-full" id="premium">
        <div class="max-w-7xl mx-auto px-8 text-center space-y-6">
            <h3 class="text-2xl font-semibold text-gray-800">Berlangganan Premium</h3>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Nikmati pengalaman membaca tanpa gangguan, akses koleksi eksklusif, dan fitur premium lainnya. Pilih
                paket yang sesuai dengan kebutuhanmu.
            </p>

            <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Bulanan -->
                <div
                    class="bg-white rounded-xl shadow-md p-6 flex flex-col justify-between hover:shadow-xl transition ease-in-out duration-300">
                    <div class="space-y-4">
                        <h4 class="text-xl font-semibold text-gray-700">Bulanan</h4>
                        <p class="text-3xl font-bold text-gray-900">Rp25.000</p>
                        <p class="text-gray-600">Per bulan, dapatkan akses penuh ke semua buku dan fitur premium.</p>
                    </div>
                    <a href="#"
                        class="mt-6 inline-block bg-blue-500 text-white font-medium rounded-lg px-6 py-2 hover:bg-blue-600 transition">
                        Mulai Bulanan
                    </a>
                </div>

                <!-- Tahunan (highlight) -->
                <div
                    class="bg-linear-to-r from-blue-400 to-blue-500 rounded-xl shadow-xl p-6 flex flex-col justify-between text-white border-2 border-blue-600 hover:shadow-2xl transition ease-in-out duration-300">
                    <div class="space-y-4">
                        <h4 class="text-xl font-semibold">Tahunan</h4>
                        <p class="text-3xl font-bold">Rp200.000</p>
                        <p>Bayar sekali untuk 12 bulan dan hemat lebih banyak dibanding paket bulanan.</p>
                        <span
                            class="inline-block bg-yellow-400 text-black px-2 py-1 rounded-full font-bold text-sm">Hemat
                            20%</span>
                    </div>
                    <a href="#"
                        class="mt-6 inline-block bg-white text-blue-600 font-medium rounded-lg px-6 py-2 hover:bg-gray-100 transition">
                        Mulai Tahunan
                    </a>
                </div>
            </div>

            <p class="text-gray-500 mt-6 text-sm max-w-md mx-auto">
                Semua paket dilengkapi akses fitur premium, koleksi eksklusif, dan pengalaman membaca tanpa iklan.
            </p>
        </div>
    </section>
    @livewire('footer')
</div>
