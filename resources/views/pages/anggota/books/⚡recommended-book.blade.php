<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Book;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;
    #[Computed]
    public function books()
    {
        return Book::with('categories')->latest()->paginate(8);
    }
};
?>

<div>
    <section id="rekomendasi" aria-labelledby="rekomendasi"
        class="max-w-7xl mx-auto px-4 md:px-6 space-y-6 md:space-y-8 py-12 lg:py-24">
        <header class="text-center" id="rekomendasi">
            <h2 class="text-2xl font-semibold text-gray-700 uppercase tracking-widest">
                Rekomendasi
            </h2>
        </header>

        <div class="grid gap-6 lg:grid-cols-3 items-start">
            <!-- LEFT CONTENT -->
            <div class="lg:col-span-2 flex flex-col gap-6">
                <div class="rounded-lg shadow-lg">
                    <div x-data="{
                        current: 0,
                        total: 0,
                        autoplayInterval: null,
                        init() {
                            this.total = this.$refs.track.children.length;
                            this.startAutoplay();
                        },
                        next() {
                            this.current = (this.current + 1) % this.total;
                        },
                        prev() {
                            this.current = (this.current - 1 + this.total) % this.total;
                        },
                        startAutoplay() {
                            this.autoplayInterval = setInterval(() => this.next(), 5000);
                        },
                    }" class="relative overflow-hidden aspect-video md:aspect-16/5">
                        <!-- TRACK -->
                        <div x-ref="track" class="flex h-full transition-transform duration-500"
                            :style="`transform: translateX(-${current * 100}%)`">
                            @foreach ($this->books as $index => $book)
                                <article class="min-w-full">
                                    @if ($book->cover_file_name)
                                        <img src="{{ url('storage/' . $book->cover_file_name) }}"
                                            alt="{{ $book->title }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                            fetchpriority="{{ $index === 0 ? 'high' : 'low' }}"
                                            class="w-full h-full object-cover" />
                                    @else
                                        <img src="{{ url('https://img.pikbest.com/origin/09/02/31/56bpIkbEsTFtz.jpg!f305cw') }}"
                                            alt="{{ $book->title }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                            fetchpriority="{{ $index === 0 ? 'high' : 'low' }}"
                                            class="w-full h-full object-cover" />
                                    @endif
                                </article>
                            @endforeach


                        </div>

                        <div class="absolute inset-0 bg-linear-to-t from-black/40 via-black/20 to-transparent"></div>

                        <!-- DOT INDICATORS -->
                        <div class="absolute z-99 bottom-3 left-1/2 -translate-x-1/2 flex gap-2">
                            <template x-for="(_, index) in total" :key="index">
                                <button :class="current === index ? 'bg-white scale-125' : 'bg-white/50'"
                                    class="w-2 h-2 rounded-full transition-all duration-300"
                                    :aria-label="`Go to slide ${index + 1}`"></button>
                            </template>
                        </div>
                    </div>
                </div>


                <!-- BOOK GRID -->
                <div class="grid gap-4 md:grid-cols-2">
                    <!-- BOOK CARD - START - FOREACH -->
                    @forelse ($this->books as $book)
                        <article
                            class="group flex gap-3 p-3 bg-white rounded-md shadow-sm hover:shadow-lg hover:-translate-y-1 transition-transform ease-out duration-500">
                            <figure class="w-20 shrink-0 aspect-2/3 overflow-hidden rounded-md">
                                @if ($book->cover_file_name)
                                    <img src="{{ url('storage/' . $book->cover_file_name) }}"
                                        loading="{{ $this->books->currentPage() === 1 && $loop->index < 3 ? 'eager' : 'lazy' }}"
                                        fetchpriority="{{ $this->books->currentPage() === 1 && $loop->index < 3 ? 'high' : 'auto' }}"
                                        class="w-full h-full object-cover transition duration-500 group-hover:scale-105" />
                                @else
                                    <img src="{{ url('https://img.pikbest.com/origin/09/02/31/56bpIkbEsTFtz.jpg!f305cw') }}"
                                        loading="{{ $this->books->currentPage() === 1 && $loop->index < 3 ? 'eager' : 'lazy' }}"
                                        fetchpriority="{{ $this->books->currentPage() === 1 && $loop->index < 3 ? 'high' : 'auto' }}"
                                        class="w-full h-full object-cover transition duration-500 group-hover:scale-105" />
                                @endif
                            </figure>

                            <div class="flex flex-col justify-between flex-1">
                                <div>
                                    <h3 class="text-sm md:text-base font-medium text-gray-700 line-clamp-2">
                                        <a href="#" class="hover:text-blue-600 transition">
                                            {{ $book->title }}
                                        </a>
                                    </h3>

                                    <p class="text-xs text-gray-500">{{ $book->author }}</p>
                                    @foreach ($book->categories as $kategori)
                                        <span class="text-xs text-gray-600">
                                            {{ $kategori->name }} @if (!$loop->last)
                                                ,
                                            @endif
                                        </span>
                                    @endforeach
                                </div>

                                <div class="flex items-center justify-between">
                                    <a href="#" aria-label="Baca buku Atomic Habits"
                                        class="px-3 py-1 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-md transition">
                                        Baca
                                    </a>

                                    <a href="#" class="text-xs text-gray-500 hover:text-blue-600 transition">
                                        Lihat detail →
                                    </a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full text-center py-10">
                            <p class="text-gray-500 text-lg">Tidak ada buku yang ditemukan</p>
                        </div>
                    @endforelse
                    <!-- BOOK CARD - END - FOREACH -->
                </div>
            </div>

            <!-- SIDEBAR -->
            <aside class="flex flex-col gap-4 lg:sticky lg:top-16">
                <!-- CATEGORY -->
                <div class="p-6 rounded-2xl bg-white/90 shadow-lg border border-gray-100 space-y-3">
                    <header class="border-b">
                        <h3 class="text-lg font-semibold text-gray-700 pb-2">
                            Kategori Buku
                        </h3>
                    </header>

                    <nav aria-label="rak kategori">
                        <ul class="space-y-2 text-sm">
                            <li>
                                <a href="#"
                                    class="flex justify-between px-3 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-700 transition">
                                    Pengembangan Diri
                                    <span aria-label="kategori buku" class="text-sm text-gray-400">(12)</span>
                                </a>
                            </li>

                            <li>
                                <a href="#"
                                    class="flex justify-between px-3 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-700 transition">
                                    Teknologi
                                    <span aria-label="kategori buku" class="text-sm text-gray-400">(8)</span>
                                </a>
                            </li>

                            <li>
                                <a href="#"
                                    class="flex justify-between px-3 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-700 transition">
                                    Bisnis
                                    <span aria-label="kategori buku" class="text-sm text-gray-400">(5)</span>
                                </a>
                            </li>

                            <li>
                                <a href="#"
                                    class="flex justify-between px-3 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-700 transition">
                                    Fiksi
                                    <span aria-label="kategori buku" class="text-sm text-gray-400">(9)</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>

                <!-- PREMIUM -->
                <div class="p-5 rounded-2xl bg-linear-to-br from-blue-500 to-indigo-500 text-white shadow-lg space-y-4">
                    <h4 class="text-lg font-semibold">Rekomendasi Premium</h4>

                    <p class="text-sm opacity-90">
                        Jelajahi pilihan buku premium terbaik yang direkomendasikan
                        untuk meningkatkan wawasan dan pengalaman membaca Anda.
                    </p>

                    <a href="transaksi.html"
                        class="inline-block mt-4 text-sm font-medium bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                        Lihat Rekomendasi
                    </a>
                </div>

                <!-- CTA UPLOAD BUKU -->
                <div class="p-5 rounded-2xl bg-linear-to-br from-blue-500 to-indigo-500 text-white shadow-lg space-y-4">
                    <h4 class="text-lg font-semibold">
                        Punya buku yang ingin dibagikan?
                    </h4>

                    <p class="text-sm opacity-90">
                        Unggah buku Anda dan bagikan ilmu kepada pembaca lain.
                    </p>

                    <a href="upload-user.html"
                        class="inline-block mt-4 text-sm font-medium bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                        unggah buku
                    </a>
                </div>
            </aside>
        </div>
    </section>
</div>
