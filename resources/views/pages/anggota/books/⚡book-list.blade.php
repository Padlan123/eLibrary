<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use App\Models\Book;
use App\Models\ReadingHistory;

new class extends Component {
    #[Computed]
    public function books()
    {
        return Book::with([
            'favoriteBooks' => function ($query) {
                $query->where('user_id', auth()->id());
            },
        ])
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

<div>
    <section aria-labelledby="populer-title" class="px-4 md:px-6 space-y-10 py-12 lg:py-24 fade-in-up">
        <div class="space-y-6">
            <header id="populer-title" class="text-center">
                <h2 class="text-xl font-semibold text-gray-700 tracking-wide uppercase">
                    Buku Populer
                </h2>
            </header>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <!-- BOOK CARD - START - FOREACH -->
                @forelse ($this->books as $book)
                    <article wire:key="{{ $book->id }}"
                        class="relative group flex gap-3 p-3 bg-white rounded-md shadow-sm hover:shadow-lg hover:-translate-y-1 transition-transform ease-out duration-500">
                        <figure class="w-20 shrink-0 aspect-2/3 overflow-hidden rounded-md">
                            <img src="{{ $book->cover_file_name ? Storage::url($book->cover_file_name) : url('https://img.pikbest.com/origin/09/02/31/56bpIkbEsTFtz.jpg!f305cw') }}"
                                loading="{{ $loop->index < 3 ? 'eager' : 'lazy' }}"
                                fetchpriority="{{ $loop->index < 3 ? 'high' : 'auto' }}"
                                class="w-full h-full object-cover transition duration-500 group-hover:scale-105" />
                        </figure>

                        <div class="flex flex-col justify-between flex-1">
                            <div>
                                <h3 class="text-sm md:text-base font-medium text-gray-700 line-clamp-2">
                                    <a href="#" class="hover:text-blue-600 transition">
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
                                        <button wire:loading.remove
                                            wire:target="unfavorite({{ $book->id }}), favorite({{ $book->id }})"
                                            wire:click="unfavorite({{ $book->id }})"
                                            class="px-3 py-1 text-sm border border-gray-300 rounded-lg bg-gray-100 transition">
                                            Batalkan Suka
                                        </button>
                                    @else
                                        <button wire:loading.remove
                                            wire:target="unfavorite({{ $book->id }}), favorite({{ $book->id }})"
                                            wire:click="favorite({{ $book->id }})"
                                            class="px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                                            Suka
                                        </button>
                                    @endif
                                    <button wire:loading
                                        wire:target="unfavorite({{ $book->id }}), favorite({{ $book->id }})"
                                        class="px-3 py-1 text-sm bg-gray-200 rounded-lg transition">
                                        Loading...
                                    </button>
                                </div>

                                <a href="{{ route('anggota.detail-book', $book->id) }}"
                                    class="text-xs text-gray-500 hover:text-blue-600 transition">
                                    Lihat detail →
                                </a>
                            </div>
                        </div>
                        @if ($book->subscription === true)
                            <span
                                class="absolute right-2 top-2 z-99 bg-warning-soft text-fg-warning text-xs font-medium px-2 py-0.5 rounded shadow">Premium</span>
                        @endif
                    </article>
                @empty
                    <div class="col-span-full text-center py-10">
                        <p class="text-gray-500 text-lg">Tidak ada buku yang ditemukan</p>
                    </div>
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
</div>
