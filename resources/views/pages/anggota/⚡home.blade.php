<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use App\Models\Book;

new class extends Component {
    #[Computed]
    public function books()
    {
        return Book::with('categories')->latest()->limit(8)->get();
    }

    public function render()
    {
        return $this->view()->title('Home')->layout('layouts.anggota');
    }
};
?>

<div class="relative max-w-7xl mx-auto">
    <section class="px-4 md:px-6 mt-32 fade-in-up">
        @if ($this->books->isNotEmpty())
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
                class="relative overflow-hidden rounded-2xl bg-gray-950/70 shadow-xl">

                {{-- TRACK --}}
                <div x-ref="track" class="flex transition-transform duration-500 ease-in-out"
                    :style="`transform: translateX(-${current * 100}%)`">

                    @foreach ($this->books as $book)
                        <article class="min-w-full flex flex-col md:flex-row min-h-64 md:min-h-80">

                            {{-- KIRI: Cover portrait --}}
                            <div
                                class="relative w-full md:w-2/5 lg:w-1/3 shrink-0 flex items-center justify-center bg-gray-900 py-6 px-8 md:py-8 md:px-10">
                                {{-- Blur background dari cover --}}
                                <div class="absolute inset-0 overflow-hidden opacity-30">
                                    <img src="{{ Storage::url($book->cover_file_name) }}" alt=""
                                        class="w-full h-full object-cover scale-110 blur-xl" aria-hidden="true" />
                                </div>

                                {{-- Cover utama --}}
                                <div class="relative z-10 shadow-2xl rounded-lg overflow-hidden lg:w-48 w-30">
                                    <img src="{{ Storage::url($book->cover_file_name) }}" alt="{{ $book->title }}"
                                        loading="{{ $loop->index === 0 ? 'eager' : 'lazy' }}"
                                        fetchpriority="{{ $loop->index === 0 ? 'high' : 'low' }}"
                                        class="w-full h-full object-cover" />
                                </div>
                            </div>

                            {{-- KANAN: Info buku --}}
                            <div class="flex-1 flex flex-col justify-center px-6 py-6 md:px-10 md:py-8 text-white">

                                {{-- Badge kategori --}}
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach ($book->categories as $category)
                                        <span
                                            class="text-xs font-medium px-2.5 py-1 rounded-full bg-white/10 text-white/80 border border-white/10">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>

                                {{-- Judul --}}
                                <h2
                                    class="text-xl md:text-2xl lg:text-3xl font-semibold leading-tight line-clamp-2 mb-2">
                                    {{ $book->title }}
                                </h2>

                                {{-- Penulis --}}
                                <p class="text-sm text-white/60 mb-4">
                                    {{ $book->author }}

                                    &mdash; {{ $book->publication_year }}

                                </p>
                                {{-- CTA --}}
                                <div class="flex items-center gap-3 ">
                                    <a href="{{ route('anggota.books.read', $book) }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                        Baca Sekarang
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="w-3.5 h-3.5">
                                            <path fill-rule="evenodd"
                                                d="M16.72 7.72a.75.75 0 0 1 1.06 0l3.75 3.75a.75.75 0 0 1 0 1.06l-3.75 3.75a.75.75 0 1 1-1.06-1.06l2.47-2.47H3a.75.75 0 0 1 0-1.5h16.19l-2.47-2.47a.75.75 0 0 1 0-1.06Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('anggota.detail-book', $book->id) }}"
                                        class="text-white/80 text-sm font-medium hover:text-white transition-colors">
                                        Lihat Detail
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="w-4 h-4 inline-block">
                                            <path fill-rule="evenodd"
                                                d="M12.97 3.97a.75.75 0 0 1 1.06 0l7.5 7.5a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 1 1-1.06-1.06L18.44 12l-5.47-5.47a.75.75 0 0 1 0-1.06Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach

                </div>

                {{-- PREV BUTTON --}}
                <button @click="prev"
                    class="absolute z-10 left-3 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/20 text-white rounded-full w-9 h-9 flex items-center justify-center transition backdrop-blur-sm border border-white/10"
                    aria-label="Slide sebelumnya">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                        <path fill-rule="evenodd"
                            d="M7.72 12.53a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 1 1 1.06 1.06L9.31 12l6.97 6.97a.75.75 0 1 1-1.06 1.06l-7.5-7.5Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                {{-- NEXT BUTTON --}}
                <button @click="next"
                    class="absolute z-10 right-3 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/20 text-white rounded-full w-9 h-9 flex items-center justify-center transition backdrop-blur-sm border border-white/10"
                    aria-label="Slide berikutnya">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                        <path fill-rule="evenodd"
                            d="M16.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                {{-- DOT INDICATORS --}}
                <div class="absolute z-10 bottom-4 right-6 flex gap-2">
                    <template x-for="(_, index) in total" :key="index">
                        <button @click="goTo(index)" :class="current === index ? 'bg-white w-5' : 'bg-white/40 w-2'"
                            class="h-2 rounded-full transition-all duration-300"
                            :aria-label="`Slide ${index + 1}`"></button>
                    </template>
                </div>

                {{-- SLIDE COUNTER --}}
                <div class="absolute z-10 bottom-4 left-6 text-white/50 text-xs tabular-nums">
                    <span x-text="current + 1"></span>/<span x-text="total"></span>
                </div>
            </div>
        @else
            <div class="flex flex-col items-center justify-center gap-4 py-20">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-10 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>

                <p class="text-gray-400 text-sm">Belum ada buku tersedia.</p>
            </div>
        @endif

    </section>
    @livewire('pages::anggota.books.filter-book')
    @livewire('pages::anggota.books.book-list')
    @livewire('pages::anggota.books.latest-book')
    @livewire('pages::anggota.books.recommended-book')

</div>
