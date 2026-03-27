<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
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

<div>
    <section class="px-4 md:px-6 mt-32 fade-in-up">
        <div class="relative overflow-hidden rounded-xl">
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
                <div x-ref="track" class="flex transition-transform duration-500"
                    :style="`transform: translateX(-${current * 100}%)`">
                    @foreach ($this->books as $book)
                        <article class="min-w-full">
                            <img src="{{ $book->cover_file_name ? Storage::url($book->cover_file_name) : url('https://img.pikbest.com/origin/09/02/31/56bpIkbEsTFtz.jpg!f305cw') }}"
                                alt="{{ $book->title }}" loading="{{ $loop->index === 0 ? 'eager' : 'lazy' }}"
                                fetchpriority="{{ $loop->index === 0 ? 'high' : 'low' }}"
                                class="w-full h-full object-cover" />
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
    @livewire('pages::anggota.books.book-list')
    @livewire('pages::anggota.books.categories')
    @livewire('pages::anggota.books.latest-book')
    @livewire('pages::anggota.books.recommended-book')

</div>
