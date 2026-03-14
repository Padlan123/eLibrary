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
        return Category::orderBy('name', 'asc')->get();
    }

    public function updatingCategory()
    {
        if (!$this->checkRateLimit('filterBooks', max: 10, jedaWaktu: 30)) {
            $this->limit = true;
            return;
        }

        $this->limit = false;
        $this->resetErrorBag('throttle');
    }

    #[Computed]
    public function books()
    {
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
        return $this->view()->title('Home')->layout('layouts.anggota');
    }
};
?>

<div>
    <section class="max-w-7xl mx-auto px-4 md:px-6 hero-carousel mt-32">
        <div class="relative overflow-hidden rounded-xl shadow-lg">
            <!-- CAROUSEL - MAX SLIDE 8 -->
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
                goTo(index) {
                    this.current = index;
                },
                startAutoplay() {
                    this.autoplayInterval = setInterval(() => this.next(), 5000);
                },
                stopAutoplay() {
                    clearInterval(this.autoplayInterval);
                }
            }" @mouseenter="stopAutoplay" @mouseleave="startAutoplay"
                class="relative overflow-hidden aspect-video md:aspect-16/5">
                <!-- TRACK -->
                <div x-ref="track" class="flex transition-transform duration-500"
                    :style="`transform: translateX(-${current * 100}%)`">
                    @foreach ($this->books as $index => $book)
                        <article class="min-w-full">
                            @if ($book->cover_file_name)
                                <img src="{{ url('storage/' . $book->cover_file_name) }}" alt="{{ $book->title }}"
                                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
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

                <!-- PREV BUTTON -->
                <button @click="prev"
                    class="absolute z-99 left-3 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white rounded-full w-9 h-9 flex items-center justify-center transition"
                    aria-label="Previous slide">
                    &#8592;
                </button>

                <!-- NEXT BUTTON -->
                <button @click="next"
                    class="absolute z-99 right-3 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white rounded-full w-9 h-9 flex items-center justify-center transition"
                    aria-label="Next slide">
                    &#8594;
                </button>

                <!-- DOT INDICATORS -->
                <div class="absolute z-99 bottom-3 left-1/2 -translate-x-1/2 flex gap-2">
                    <template x-for="(_, index) in total" :key="index">
                        <button @click="goTo(index)" :class="current === index ? 'bg-white scale-125' : 'bg-white/50'"
                            class="w-2 h-2 rounded-full transition-all duration-300"
                            :aria-label="`Go to slide ${index + 1}`"></button>
                    </template>
                </div>
            </div>

            <!-- TITLE, SUBTITLE, CTA -->
            <div
                class="absolute inset-0 bg-linear-to-t from-black/80 via-black/40 to-transparent flex items-center justify-center text-center">
                <div class="max-w-xl text-white space-y-3">
                    <h1 class="text-2xl md:text-3xl font-semibold">
                        Perpustakaan Digital
                    </h1>

                    <p class="text-sm md:text-base text-gray-100">
                        Temukan berbagai buku menarik dan mulai membaca kapan saja di
                        Readify.
                    </p>

                    <a href="#terbaru"
                        class="inline-block px-3 text-sm md:text-base md:px-4 py-1 md:py-2 bg-blue-600 hover:bg-blue-700 shadow-sm hover:shadow-md rounded-md transition">
                        Mulai Membaca
                    </a>
                </div>
            </div>

            <!-- DOTS NAVIGATION -->
            <div class="dots absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2"></div>
        </div>
    </section>

    <!-- Section Card Buku -->
    {{-- <section class="scroll-mt-12 py-12 bg-gray-100 w-full" id="card">
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
                            <select wire:model.live.debounce="category" id="kategori"
                                class="w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body">
                                <option value="">Semua Kategori</option>
                                @foreach ($this->categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                    <figure wire:key="{{ $book->id }}"
                        class="w-full p-2 flex bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl hover:-translate-y-1 transition ease-in-out duration-300 relative">
                        @if ($book->cover_file_name)
                            <img src="{{ url('storage/' . $book->cover_file_name) }}" alt=""
                                class="object-cover object-center aspect-portrait w-28 rounded-lg" />
                        @else
                            <img src="{{ url('https://img.pikbest.com/origin/09/02/31/56bpIkbEsTFtz.jpg!f305cw') }}"
                                alt="" class="object-cover object-center aspect-portrait w-28 rounded-lg" />
                        @endif
                        <div class="flex flex-col justify-between px-3 w-full py-1">
                            <div class="space-y-3 pb-2">
                                <a href="#" class="flex items-center justify-between gap-2">
                                    <h4 class="text-lg text-gray-700 leading-tight">{{ $book->title }}</h4>
                                    <p
                                        class="text-[12px] h-3 mr-1 font-medium text-gray-400 tracking-tight font-mono whitespace-nowrap">
                                        {{ $book->created_at->diffForHumans() }}</p>
                                </a>
                                <div class="flex items-center gap-2">
                                    @foreach ($book->categories as $kategori)
                                        <span>
                                            <p
                                                class="text-sm text-gray-700 bg-gray-200 rounded-full px-2 outline-none ring-1 ring-gray-200 transition ease-in duration-300 hover:scale-105">
                                                {{ $kategori->name }}</p>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex items-center justify-between w-full mt-6">
                                <span class="text-sm text-gray-500">Tahun Terbit : {{ $book->publication_year }}</span>
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
                        <p class="text-gray-500 text-lg">Tidak ada buku yang ditemukan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section> --}}

    <section aria-labelledby="populer-title" class="max-w-7xl mx-auto px-4 md:px-6 space-y-10">
        <div class="space-y-6">
            <header id="populer-title" class="text-center">
                <h2 class="text-xl font-semibold text-gray-700 tracking-wide uppercase">
                    Buku Populer
                </h2>
            </header>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <!-- BOOK CARD - START - FOREACH -->
                @forelse ($this->books as $book)
                    <article
                        class="group flex gap-3 p-3 bg-white rounded-md shadow-sm hover:shadow-lg hover:-translate-y-1 transition-transform ease-out duration-500">
                        <figure class="w-20 shrink-0 aspect-2/3 overflow-hidden rounded-md">
                            <img src="/img/book/book-1.jpg" alt="Cover buku Atomic Habits karya James Clear"
                                loading="lazy"
                                class="w-full h-full object-cover transition duration-500 group-hover:scale-105" />
                        </figure>

                        <div class="flex flex-col justify-between flex-1">
                            <div>
                                <h3 class="text-sm md:text-base font-medium text-gray-700 line-clamp-2">
                                    <a href="#" class="hover:text-blue-600 transition">
                                        {{ $book->title }}
                                    </a>
                                </h3>

                                <p class="text-xs text-gray-500">$book</p>
                                <p class="text-xs text-gray-600">Pengembangan diri</p>
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
                @endforelse

                <!-- BOOK CARD - END - FOREACH -->

            </div>
        </div>

        <div class="flex justify-center">
            <a href="#"
                class="bg-linear-to-t from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 py-1 px-3 text-center rounded-lg shadow-sm text-sm text-white transition">
                Lihat semua
            </a>
        </div>
    </section>
    <x-footer></x-footer>
</div>
