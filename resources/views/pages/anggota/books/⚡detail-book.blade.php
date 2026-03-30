<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Book;

new class extends Component {
    public $id;

    public function mount($id)
    {
        $this->id = $id;
    }

    #[Computed(persist: true)]
    public function selectedBook()
    {
        return Book::with('categories')->find($this->id);
    }

    #[Computed]
    public function is_favorite()
    {
        return Book::whereHas('favoriteBooks', function ($query) {
            $query->where('user_id', auth()->id());
        })
            ->where('id', $this->id)
            ->exists();
    }

    public function favorite($id)
    {
        auth()
            ->user()
            ->favoriteBooks()
            ->syncWithoutDetaching([$id]);
    }

    public function unfavorite($id)
    {
        auth()->user()->favoriteBooks()->detach($id);
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
        <div class="lg:col-span-2 flex flex-col gap-6">
            <!-- TITLE -->
            <header class="space-y-2">
                <h1 class="text-3xl font-bold text-gray-800">{{ $this->selectedBook->title }}</h1>

                <p class="text-lg text-gray-500">{{ $this->selectedBook->author }}</p>
            </header>

            <!-- BOOK META -->
            <div class="grid grid-cols-2 gap-4 text-sm">
                <p>
                    <span class="font-medium text-gray-600">Kategori:</span>

                    <span class="text-xs text-gray-600">
                        {{ $this->selectedBook->categories->pluck('name')->join(', ') }}
                    </span>

                </p>

                <p>
                    <span class="font-medium text-gray-600">Tahun:</span>
                    {{ $this->selectedBook->publication_year }}
                </p>

                <p>
                    <span class="font-medium text-gray-600">Halaman:</span>
                    {{ $this->selectedBook->total_pages ?? '' }}
                </p>

            </div>

            <!-- RATING -->
            {{-- <div class="flex items-center gap-2 text-yellow-500">
                    ⭐⭐⭐⭐⭐
                    <span class="text-sm text-gray-500"> 4.8 / 5 (120 ulasan) </span>
                </div> --}}

            <!-- CTA BUTTON -->
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
        </div>
    </section>

    <!-- DESKRIPSI BUKU -->
    <section class="bg-white rounded-xl p-6 shadow-sm space-y-4">
        <h2 class="text-xl font-semibold text-gray-800">Deskripsi Buku</h2>

        @foreach (explode("\n", $this->selectedBook->summary) as $paragraph)
            @if (trim($paragraph))
                <p class="text-gray-600 leading-relaxed">
                    {{ trim($paragraph) }}
                </p>
            @endif
        @endforeach
    </section>

    <!-- INFORMASI BUKU -->
    <section class="bg-white rounded-xl p-6 shadow-sm space-y-4">
        <h2 class="text-xl font-semibold text-gray-800">Informasi Buku</h2>

        <div class="grid md:grid-cols-2 gap-4 text-sm">
            <p>
                <span class="font-medium text-gray-600">Penulis:</span>
                {{ $this->selectedBook->author }}
            </p>

            <p>
                <span class="font-medium text-gray-600">Penerbit:</span>
                {{ $this->selectedBook->publisher }}
            </p>

            {{-- <p>
                    <span class="font-medium text-gray-600">ISBN:</span>
                    9780735211292
                </p> --}}

            <p>
                <span class="font-medium text-gray-600">Tahun Terbit:</span>
                {{ $this->selectedBook->publication_year }}
            </p>
        </div>
    </section>
</div>
