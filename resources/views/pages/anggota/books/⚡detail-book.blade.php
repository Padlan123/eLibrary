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

    #[Computed]
    public function selectedBook()
    {
        return Book::find($this->id);
    }

    public function render()
    {
        return $this->view()->title('Detail Buku')->layout('layouts.anggota');
    }
};
?>

<div class="bg-gray-50 text-gray-700 py-24">
    <div class="max-w-7xl mx-auto px-4 py-8 space-y-8">
        <section class="grid gap-8 lg:grid-cols-3">
            <!-- BOOK COVER -->
            <figure class="w-full max-w-xs mx-auto lg:mx-0">
                <img src="/img/book/book-1.jpg" alt="Cover buku Atomic Habits karya James Clear"
                    class="w-full rounded-lg shadow-lg object-cover" />
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
                        @foreach ($this->selectedBook->categories as $category)
                            <span class="text-xs text-gray-600">
                                {{ $category->name }} @if (!$loop->last)
                                    ,
                                @endif
                            </span>
                        @endforeach
                    </p>

                    <p>
                        <span class="font-medium text-gray-600">Tahun:</span>
                        2018
                    </p>

                    <p>
                        <span class="font-medium text-gray-600">Halaman:</span>
                        320
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

                    <button class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                        Simpan Buku
                    </button>
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
</div>
