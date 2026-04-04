<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Book;

new class extends Component {
    public int $id;
    public ?int $userRating = null;

    public function mount(int $id): void
    {
        $this->id = $id;

        // Load rating awal saat halaman pertama dibuka
        $pivot = auth()->user()->ratedBooks()->where('book_id', $this->id)->first();
        $this->userRating = $pivot?->pivot->rating;
    }

    #[Computed(persist: true)]
    public function selectedBook()
    {
        return Book::with('categories')->find($this->id);
    }

    #[Computed]
    public function is_favorite(): bool
    {
        return Book::whereHas('favoriteBooks', function ($query) {
            $query->where('user_id', auth()->id());
        })
            ->where('id', $this->id)
            ->exists();
    }

    public function favorite(int $id): void
    {
        auth()
            ->user()
            ->favoriteBooks()
            ->syncWithoutDetaching([$id]);
    }

    public function unfavorite(int $id): void
    {
        auth()->user()->favoriteBooks()->detach($id);
    }

    public function rate(int $rating): void
    {
        if ($rating < 1 || $rating > 5) {
            return;
        }

        auth()
            ->user()
            ->ratedBooks()
            ->syncWithoutDetaching([
                $this->id => ['rating' => $rating],
            ]);

        $this->userRating = $rating;
    }

    public function removeRating(): void
    {
        auth()
            ->user()
            ->ratedBooks()
            ->syncWithoutDetaching([
                $this->id => ['rating' => null],
            ]);

        $this->userRating = null;
    }

    #[Computed]
    public function averageRating(): float|null
    {
        $ratings = $this->selectedBook->ratedByUsers()->wherePivotNotNull('rating')->pluck('rating'); // sesuaikan nama tabel pivot rating kamu

        return $ratings->count() > 0 ? round($ratings->avg(), 1) : null;
    }

    #[Computed]
    public function ratingCount(): int
    {
        return $this->selectedBook->ratedByUsers()->wherePivotNotNull('rating')->count();
    }

    public function render()
    {
        return $this->view()->title('Detail Buku')->layout('layouts.anggota');
    }
};
?>


<div class="px-12 py-32 space-y-8 bg-gray-50 text-gray-700">
    <section class="grid gap-8 lg:grid-cols-3">
        <!-- BOOK COVER -->
        <figure class="w-full max-w-xs mx-auto lg:mx-0">
            <img src="{{ Storage::url($this->selectedBook->cover_file_name) }}"
                alt="Cover buku Atomic Habits karya James Clear"
                class="w-full rounded-lg shadow-lg aspect-2/3 object-cover" />
        </figure>

        <!-- BOOK INFO -->
        <div class="lg:col-span-2 flex flex-col justify-between">
            <!-- TITLE -->
            <div class="space-y-8">

                <header class="space-y-2">
                    <h1 class="text-3xl font-bold text-gray-800">{{ $this->selectedBook->title }}</h1>

                    <p class="text-lg text-gray-500">{{ $this->selectedBook->author }}</p>
                </header>

                <ul class="grid md:grid-cols-2 space-y-3">
                    <li>
                        <span class="font-medium text-gray-600">Kategori:</span>

                        <span class="text-xs text-gray-600">
                            {{ $this->selectedBook->categories->pluck('name')->join(', ') }}
                        </span>
                    </li>
                    <li>
                        <span class="font-medium text-gray-600">Tahun Terbit:</span>
                        {{ $this->selectedBook->publication_year }}
                    </li>
                    <li>
                        <span class="font-medium text-gray-600">Penulis:</span>
                        {{ $this->selectedBook->author }}
                    </li>
                    <li>
                        <span class="font-medium text-gray-600">Penerbit:</span>
                        {{ $this->selectedBook->publisher }}
                    </li>
                    <li>
                        <span class="font-medium text-gray-600">Halaman:</span>
                        {{ $this->selectedBook->total_pages ?? '' }}
                    </li>
                    <li class="flex items-center space-x-2">
                        <div class="flex">
                            @for ($i = 1; $i <= 5; $i++)
                                <span
                                    class="text-lg {{ $i <= round($this->averageRating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}">
                                    ★
                                </span>
                            @endfor
                        </div>

                        <span class="text-sm text-gray-500">
                            @if ($this->averageRating)
                                {{ $this->averageRating }} / 5
                                <span class="text-gray-400">
                                    ({{ $this->ratingCount }} {{ Str::plural('ulasan', $this->ratingCount) }})
                                </span>
                            @else
                                Belum ada ulasan
                            @endif
                        </span>
                    </li>
                </ul>
            </div>

            <!-- CTA BUTTON -->
            <div class="space-y-3 pt-8">
                <div class="flex gap-4">
                    <a href="{{ route('anggota.books.read', $this->selectedBook) }}"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Mulai Membaca
                    </a>

                    @if ($this->is_favorite)
                        <button wire:loading.remove wire:target="unfavorite, favorite"
                            wire:click="unfavorite({{ $this->selectedBook->id }})"
                            class="px-6 py-3 border border-gray-300 rounded-lg bg-gray-100 transition">
                            Batalkan Suka
                        </button>
                    @else
                        <button wire:loading.remove wire:target="unfavorite, favorite"
                            wire:click="favorite({{ $this->selectedBook->id }})"
                            class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                            Suka
                        </button>
                    @endif
                    <button wire:loading wire:target="unfavorite, favorite"
                        class="px-6 py-3 bg-gray-200 rounded-lg transition">
                        Loading...
                    </button>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-500 font-medium w-24">Rating kamu:</span>

                    <div class="flex items-center gap-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <button wire:click="rate({{ $i }})" wire:loading.attr="disabled"
                                wire:target="rate, removeRating" title="Beri rating {{ $i }}"
                                class="text-2xl transition-transform hover:scale-110 focus:outline-none disabled:opacity-50
                           {{ $userRating !== null && $i <= $userRating ? 'text-yellow-400' : 'text-gray-300 hover:text-yellow-300' }}">
                                ★
                            </button>
                        @endfor
                    </div>

                    @if ($userRating !== null)
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-yellow-600">{{ $userRating }}/5</span>
                            <button wire:click="removeRating" wire:loading.remove wire:target="removeRating"
                                class="text-xs text-gray-400 hover:text-red-400 underline transition">
                                Hapus
                            </button>
                        </div>
                    @else
                        <span class="text-xs text-gray-400 italic">Belum dinilai</span>
                    @endif

                    <span wire:loading wire:target="rate, removeRating" class="text-xs text-gray-400">
                        Menyimpan...
                    </span>
                </div>
            </div>
        </div>
    </section>
    <!-- RATING SECTION -->

    <!-- DESKRIPSI BUKU -->
    <section class="bg-white rounded-xl p-6 shadow-sm space-y-4">
        <h2 class="text-xl font-semibold text-gray-800">Deskripsi Buku</h2>

        <div x-data="{
            expanded: false,
            overflowing: false,
            init() {
                this.$nextTick(() => {
                    const el = this.$refs.content;
                    this.overflowing = el.scrollHeight > el.clientHeight;
                });
            }
        }">
            {{-- Wrapper konten dengan max-height saat collapsed --}}
            <div x-ref="content" class="overflow-hidden transition-all duration-500 ease-in-out"
                :class="expanded ? 'max-h-500' : 'max-h-30 md:max-h-18'">
                @foreach (explode("\n", $this->selectedBook->summary) as $paragraph)
                    @if (trim($paragraph))
                        <p class="text-gray-600 leading-relaxed mb-3 last:mb-0">
                            {{ trim($paragraph) }}
                        </p>
                    @endif
                @endforeach
            </div>

            {{-- Fade overlay saat collapsed --}}
            <div x-show="!expanded && overflowing"
                class="relative -mt-8 h-8 bg-linear-to-t from-white to-transparent pointer-events-none"></div>

            {{-- Tombol toggle, hanya muncul jika konten melebihi batas --}}
            <div x-show="overflowing" class="mt-2">
                <button @click="expanded = !expanded"
                    class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center gap-1 transition-colors">
                    <span x-text="expanded ? 'Persingkat' : 'Selengkapnya'"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-300"
                        :class="expanded ? 'rotate-180' : 'rotate-0'" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
        </div>
    </section>

</div>
