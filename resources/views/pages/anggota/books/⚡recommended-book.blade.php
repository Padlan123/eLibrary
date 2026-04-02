<?php

use Livewire\Component;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use App\Models\Book;
use App\Models\Category;

new #[Lazy] class extends Component {
    public function placeholder()
    {
        return view('placeholder.default', [
            'message' => 'memuat buku...',
        ]);
    }

    #[Computed]
    public function categories()
    {
        return Category::whereHas('books', function ($query) {
            $query->where('is_recommended', true);
        })
            ->with([
                'books' => function ($query) {
                    $query->where('is_recommended', true);
                },
            ])
            ->withCount('books')
            ->having('books_count', '>', 0)
            ->orderBy('books_count', 'desc')
            ->limit(6)
            ->get();
    }

    #[Computed]
    public function books()
    {
        return Book::with([
            'favoriteBooks' => function ($query) {
                $query->where('user_id', auth()->id());
            },
        ])
            ->where('is_recommended', true)
            ->orderBy('id', 'desc')
            ->limit(12)
            ->get();
    }

    public function favorite($id)
    {
        auth()
            ->user()
            ->favoriteBooks()
            ->syncWithoutDetaching([$id]);

        unset($this->books);
        $this->dispatch('favorite-updated');
    }

    public function unfavorite($id)
    {
        auth()->user()->favoriteBooks()->detach($id);
        unset($this->books);
        $this->dispatch('favorite-updated');
    }

    #[On('favorite-updated')]
    public function refreshBooks()
    {
        unset($this->books);
    }
};
?>

<section id="rekomendasi" aria-labelledby="rekomendasi"
    class="px-4 md:px-6 space-y-6 md:space-y-8 py-12 lg:py-24 fade-in-up">
    <header class="text-center" id="rekomendasi">
        <h2 class="text-2xl font-semibold text-gray-700 uppercase tracking-widest">
            Rekomendasi
        </h2>
    </header>

    <div class="grid gap-6 lg:grid-cols-3 items-start">
        <!-- LEFT CONTENT -->
        <div class="lg:col-span-2 flex flex-col gap-6">

            <!-- CAROUSEL SPLIT -->
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
                startAutoplay() {
                    this.autoplayInterval = setInterval(() => this.next(), 5000);
                },
            }" class="relative overflow-hidden rounded-2xl bg-gray-600 shadow-xl">

                <!-- TRACK -->
                <div x-ref="track" class="flex transition-transform duration-500 ease-in-out"
                    :style="`transform: translateX(-${current * 100}%)`">

                    @foreach ($this->books as $book)
                        <article class="min-w-full flex flex-col md:flex-row min-h-64 md:min-h-72">

                            <!-- KIRI: Cover portrait -->
                            <div
                                class="relative w-full md:w-2/5 lg:w-1/3 shrink-0 flex items-center justify-center bg-gray-900 py-6 px-8 md:py-8 md:px-10">
                                <!-- Blur background -->
                                <div class="absolute inset-0 overflow-hidden opacity-30">
                                    <img src="{{ $book->cover_file_name ? url('storage/' . $book->cover_file_name) : url('https://img.pikbest.com/origin/09/02/31/56bpIkbEsTFtz.jpg!f305cw') }}"
                                        alt="" class="w-full h-full object-cover scale-110 blur-xl"
                                        aria-hidden="true" />
                                </div>
                                <!-- Cover utama -->
                                <div class="relative z-10 shadow-2xl rounded-lg overflow-hidden"
                                    style="width: 130px; height: 185px;">
                                    <img src="{{ $book->cover_file_name ? url('storage/' . $book->cover_file_name) : url('https://img.pikbest.com/origin/09/02/31/56bpIkbEsTFtz.jpg!f305cw') }}"
                                        alt="{{ $book->title }}" loading="{{ $loop->index === 0 ? 'eager' : 'lazy' }}"
                                        fetchpriority="{{ $loop->index === 0 ? 'high' : 'low' }}"
                                        class="w-full h-full object-cover" />
                                </div>
                            </div>

                            <!-- KANAN: Info buku -->
                            <div class="flex-1 flex flex-col gap-6 justify-center px-6 py-6 md:px-8 md:py-8 text-white">

                                <!-- Badge kategori -->

                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach ($book->categories as $category)
                                        <span
                                            class="text-xs font-medium px-2.5 py-1 rounded-full bg-white/10 text-white/80 border border-white/10">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>


                                <!-- Judul -->
                                <h3 class="text-lg md:text-2xl font-semibold  leading-tight mb-1.5">
                                    {{ $book->title }}
                                </h3>

                                <!-- Penulis -->
                                <p class="text-sm text-white/60 mb-5">
                                    {{ $book->author }}

                                    &mdash; {{ $book->publication_year }}

                                </p>
                            </div>
                        </article>
                    @endforeach

                </div>


                <!-- DOT INDICATORS -->
                <div class="absolute z-10 bottom-4 right-6 flex gap-2">
                    <template x-for="(_, index) in total" :key="index">
                        <button :class="current === index ? 'bg-white w-5' : 'bg-white/40 w-2'"
                            class="h-2 rounded-full transition-all duration-300"
                            :aria-label="`Slide ${index + 1}`"></button>
                    </template>
                </div>

                <!-- SLIDE COUNTER -->
                <div class="absolute z-10 bottom-4 left-6 text-white/50 text-xs tabular-nums">
                    <span x-text="current + 1"></span>/<span x-text="total"></span>
                </div>

            </div>
            <!-- END CAROUSEL SPLIT -->


            <!-- BOOK GRID -->
            <div class="grid gap-4 md:grid-cols-2">
                @forelse ($this->books as $book)
                    <article
                        class="group flex gap-3 p-3 bg-white rounded-md shadow-sm hover:shadow-lg hover:-translate-y-1 transition-transform ease-out duration-500">
                        <figure class="w-20 shrink-0 aspect-2/3 overflow-hidden rounded-md">

                            <img src="{{ Storage::url($book->cover_file_name) }}" alt="{{ $book->title }}"
                                loading="{{ $loop->index < 3 ? 'eager' : 'lazy' }}"
                                fetchpriority="{{ $loop->index < 3 ? 'high' : 'auto' }}"
                                class="w-full h-full object-cover transition duration-500 group-hover:scale-105" />

                        </figure>

                        <div class="flex flex-col justify-between flex-1">
                            <div>
                                <h3 class="text-sm md:text-base font-medium text-gray-700 w-36 truncate">
                                    <a href="{{ route('anggota.detail-book', $book->id) }}"
                                        class="hover:text-blue-600 transition">
                                        {{ $book->title }}
                                    </a>
                                </h3>

                                <p class="text-xs text-gray-500">{{ $book->author }}</p>

                                <span class="text-xs text-gray-600">
                                    {{ $book->category_names }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="space-x-2">
                                    <a href="{{ route('anggota.books.read', $book) }}"
                                        class="px-3 py-1 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-md transition">
                                        Baca
                                    </a>
                                    @php
                                        $isFavorite = $book->favoriteBooks->isNotEmpty();
                                    @endphp

                                    @if ($isFavorite)
                                        <button wire:click="unfavorite({{ $book->id }})"
                                            class="px-3 py-1 text-sm text-black bg-gray-100 hover:bg-gray-200 rounded-md transition cursor-pointer">
                                            Batal Disukai
                                        </button>
                                    @else
                                        <button wire:click="favorite({{ $book->id }})"
                                            class="px-3 py-1 text-sm text-black bg-gray-100 hover:bg-gray-200 rounded-md transition cursor-pointer">
                                            Sukai
                                        </button>
                                    @endif
                                </div>

                                <a href="{{ route('anggota.detail-book', $book->id) }}"
                                    class="text-xs text-gray-500 hover:text-blue-600 transition">
                                    Lihat detail →
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full text-center py-10">
                        <p class="text-gray-500 text-lg">Tidak ada buku yang direkomendasikan</p>
                    </div>
                @endforelse
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
                        @foreach ($this->categories as $category)
                            <li>
                                <a href="#"
                                    class="flex justify-between px-3 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-700 transition">
                                    {{ $category->name }}
                                    <span aria-label="kategori buku"
                                        class="text-sm text-gray-400">{{ $category->books_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </div>

            <!-- PREMIUM -->
            @if (auth()->user()->hasPermissionTo('premium'))
                <div class="p-5 rounded-2xl bg-linear-to-br from-blue-500 to-indigo-500 text-white shadow-lg space-y-4">
                    <h4 class="text-lg font-semibold">Kamu Anggota Premium</h4>

                    <p class="text-sm opacity-90">
                        Mau perpanjang langgananmu atau upgrade ke paket yang lebih tinggi? Klik tombol di bawah untuk
                        melihat pilihan paket langganan premium kami dan nikmati akses tak terbatas ke koleksi buku
                        terbaik kami.
                    </p>

                    <a href="{{ route('anggota.subscriptions') }}"
                        class="inline-block mt-4 text-sm font-medium bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                        Langganan atau Perpanjang Sekarang
                    </a>
                </div>
            @else
                <div class="p-5 rounded-2xl bg-linear-to-br from-blue-500 to-indigo-500 text-white shadow-lg space-y-4">
                    <h4 class="text-lg font-semibold">Berlangganan Premium</h4>

                    <p class="text-sm opacity-90">
                        Jelajahi pilihan buku premium terbaik yang direkomendasikan
                        untuk meningkatkan wawasan dan pengalaman membaca Anda.
                    </p>

                    <a href="{{ route('anggota.subscriptions') }}"
                        class="inline-block mt-4 text-sm font-medium bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                        Berlangganan Sekarang
                    </a>
                </div>
            @endif
        </aside>
    </div>
</section>
