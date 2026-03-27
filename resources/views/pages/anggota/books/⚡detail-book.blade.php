<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Book;
use App\Models\ReadingHistory;
use App\Traits\WithFlashMessages;

new class extends Component {
    use WithFlashMessages;
    public $id;

    public function mount($id)
    {
        $this->id = $id;
    }

    #[Computed]
    public function selectedBook()
    {
        return Book::with('categories')->find($this->id);
    }

    #[Computed]
    public function is_favorite()
    {
        return ReadingHistory::where('book_id', $this->id)
            ->where('user_id', auth()->id())
            ->where('is_favorite', true)
            ->first();
    }

    public function favorite()
    {
        $book = ReadingHistory::where('book_id', $this->id)->where('user_id', auth()->id());
        $book->update([
            'is_favorite' => true,
        ]);

        $this->FlashMessage('sukses', 'berhasil menambahkan ke favorit', 'anggota.detail-book', $this->id);
    }

    public function unfavorite()
    {
        $book = ReadingHistory::where('book_id', $this->id)->where('user_id', auth()->id());
        $book->update([
            'is_favorite' => false,
        ]);

        $this->FlashMessage('sukses', 'membatalkan ke favorit', 'anggota.detail-book', $this->id);
    }

    public function render()
    {
        return $this->view()->title('Detail Buku')->layout('layouts.anggota');
    }
};
?>

<div class="bg-gray-50 text-gray-700 py-24">
    <div class="px-4 py-8 space-y-8">
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
                            {{ $this->selectedBook->category_names }}
                        </span>

                    </p>

                    <p>
                        <span class="font-medium text-gray-600">Tahun:</span>
                        {{ $this->selectedBook->publication_year }}
                    </p>

                    <p>
                        <span class="font-medium text-gray-600">Halaman:</span>
                        {{ $this->selectedBook->total_pages }}
                    </p>

                    <p>
                        <span class="font-medium text-gray-600">Bahasa:</span>
                        Inggris
                    </p>
                </div>

                <!-- RATING -->
                <div class="flex items-center gap-2 text-yellow-500">
                    ⭐⭐⭐⭐⭐
                    <span class="text-sm text-gray-500"> 4.8 / 5 (120 ulasan) </span>
                </div>

                <!-- CTA BUTTON -->
                <div class="flex gap-4">
                    <a href="#" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Mulai Membaca
                    </a>

                    @if ($this->is_favorite)
                        <button wire:click="unfavorite"
                            class="px-6 py-3 border border-gray-300 rounded-lg bg-gray-100 transition">
                            Batalkan Disukai
                        </button>
                    @else
                        <button wire:click="favorite"
                            class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                            Disukai
                        </button>
                    @endif
                </div>
            </div>
        </section>

        <!-- DESKRIPSI BUKU -->
        <section class="bg-white rounded-xl p-6 shadow-sm space-y-4">
            <h2 class="text-xl font-semibold text-gray-800">Deskripsi Buku</h2>

            <p class="text-gray-600 leading-relaxed">
                Atomic Habits adalah buku yang menjelaskan bagaimana perubahan kecil
                yang dilakukan secara konsisten dapat menghasilkan dampak besar dalam
                kehidupan. James Clear membahas konsep kebiasaan melalui pendekatan
                ilmiah dan memberikan strategi praktis untuk membangun kebiasaan baik
                serta menghentikan kebiasaan buruk.
            </p>

            <p class="text-gray-600 leading-relaxed">
                Buku ini sangat cocok bagi siapa saja yang ingin meningkatkan
                produktivitas, memperbaiki gaya hidup, dan mencapai tujuan jangka
                panjang melalui perubahan kecil yang konsisten.
            </p>
        </section>

        <!-- INFORMASI BUKU -->
        <section class="bg-white rounded-xl p-6 shadow-sm space-y-4">
            <h2 class="text-xl font-semibold text-gray-800">Informasi Buku</h2>

            <div class="grid md:grid-cols-2 gap-4 text-sm">
                <p>
                    <span class="font-medium text-gray-600">Penulis:</span>
                    James Clear
                </p>

                <p>
                    <span class="font-medium text-gray-600">Penerbit:</span>
                    Avery
                </p>

                <p>
                    <span class="font-medium text-gray-600">ISBN:</span>
                    9780735211292
                </p>

                <p>
                    <span class="font-medium text-gray-600">Tanggal Terbit:</span>
                    16 Oktober 2018
                </p>
            </div>
        </section>
    </div>
    @if (session('sukses'))
        <div x-data="{ show: false }" x-show="show" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4" x-init="setTimeout(() => {
                show = true;
                setTimeout(() => show = false, 3000)
            }, 300)"
            class="fixed top-24 left-1/2 -translate-x-1/2 z-50
    w-[calc(100%-2rem)] sm:w-auto sm:max-w-sm
    flex items-start sm:items-center px-4 py-3 text-sm
    text-fg-success-strong rounded-xl bg-success-soft
    border border-success-subtle shadow-lg"
            role="alert">
            <svg class="w-4 h-4 me-2 shrink-0 mt-0.5 sm:mt-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <p>{{ session('sukses') }}</p>
        </div>
    @endif
</div>
