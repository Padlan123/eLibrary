<?php

use Livewire\Component;
use App\Models\SubscriptionTransaction;
use App\Models\ReadingHistory;
use App\Models\Book;
use App\Models\User;
use Livewire\Attributes\Computed;

new class extends Component {
    public $username = '';
    public $initials = '';
    public $created_at = '';

    public function mount()
    {
        $user = auth()->user();
        $words = explode(' ', $user->username);
        $initial = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));

        $this->username = $user->username;
        $this->created_at = $user->created_at;
        $this->initials = $initial;
    }

    #[Computed]
    public function isPremium()
    {
        return User::whereHas('subscribes', function ($query) {
            $query->where('status', 'active');
        })
            ->where('id', auth()->id())
            ->exists();
    }

    #[Computed]
    public function reject()
    {
        return SubscriptionTransaction::where('user_id', auth()->id())
            ->where('status', 'rejected')
            ->exists();
    }

    #[Computed]
    public function histories()
    {
        return ReadingHistory::with('book')
            ->where('user_id', auth()->id())
            ->where('status', 'reading')
            ->latest('last_read_at')
            ->get();
    }

    #[Computed]
    public function favorites()
    {
        return auth()->user()->favoriteBooks()->with('categories')->get()->sortByDesc(fn($book) => $book->pivot->created_at);
    }

    public function render()
    {
        return $this->view()->title('Profil')->layout('layouts.anggota');
    }
};
?>

<div>
    <div class="space-y-12 py-24">
        <!-- PROFILE -->
        <section class="relative py-10 px-4 md:px-10">
            <div class="relative z-10 flex flex-col  items-center text-center">
                <div class="space-y-4 max-w-md">
                    <div class="flex items-center justify-center gap-2 flex-wrap">
                        <h1 class="text-xl md:text-2xl font-semibold text-gray-800">
                            {{ $this->username }}
                        </h1>
                    </div>
                    <div
                        class="size-14 lg:size-20 rounded-full ring-1 mx-auto ring-white flex items-center justify-center bg-blue-500 cursor-pointer my-2">
                        <span class="text-white text-2xl lg:text-4xl font-bold">
                            {{ $initials }}
                        </span>
                    </div>
                    @if ($this->isPremium)
                        <span class="text-xs bg-blue-500 text-white px-2 py-0.5 rounded-full">
                            Premium
                        </span>
                    @endif
                    @if ($this->reject && !$this->isPremium)
                        <div class="relative inline-block group">
                            <span class="text-xs bg-red-500 text-white px-2 py-0.5 rounded-full cursor-default">
                                Berlangganan di tolak
                            </span>
                            <div
                                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 
                    opacity-0 group-hover:opacity-100 transition-opacity duration-150
                    bg-gray-800 text-white text-xs rounded px-2 py-1 
                    whitespace-nowrap pointer-events-none z-10">
                                Perhatikan lebih teliti saat mengisi formulir berlangganan, Coba isi Kembali dengan data
                                yang benar dan lengkap.

                                <div
                                    class="absolute top-full left-1/2 -translate-x-1/2 
                        border-4 border-transparent border-t-gray-800">
                                </div>
                            </div>
                        </div>
                    @endif

                    <p class="text-sm text-gray-500">Bergabung sejak {{ $this->created_at->format('Y') }}</p>

                    <a href="#"
                        class="inline-block text-sm text-white bg-blue-600 px-4 py-2 rounded-lg hover:bg-blue-500 transition">
                        Edit profil
                    </a>
                </div>
            </div>
        </section>

        <!-- PERPUSTAKAAN -->
        <section x-data="{ activeTab: 'my-books' }" class="px-4 md:px-10 space-y-6">
            <header>
                <h2 class="text-xl font-semibold text-gray-800">Perpustakaan</h2>
            </header>

            <div class="rounded-xl overflow-hidden bg-white">

                {{-- TAB NAVIGATION --}}
                <div class="flex border-b">
                    <button @click="activeTab = 'my-books'" type="button"
                        :class="activeTab === 'my-books'
                            ?
                            'border-b-2 border-blue-600 text-blue-600 font-semibold' :
                            'border-b-2 border-transparent text-gray-600 hover:text-gray-900'"
                        class="w-full p-3 text-sm font-medium transition">
                        Histori Baca
                    </button>

                    <button @click="activeTab = 'favorites'" type="button"
                        :class="activeTab === 'favorites'
                            ?
                            'border-b-2 border-blue-600 text-blue-600 font-semibold' :
                            'border-b-2 border-transparent text-gray-600 hover:text-gray-900'"
                        class="w-full p-3 text-sm font-medium transition">
                        Favorit Buku
                    </button>
                </div>

                {{-- TAB: BUKU SAYA --}}
                <template x-if="activeTab === 'my-books'">
                    <div class="p-4 space-y-4">
                        @forelse ($this->histories as $history)
                            <article class="flex gap-4 pb-4 border-b last:border-0 last:pb-0">
                                <figure class="w-20 sm:w-24 shrink-0">
                                    <img src="{{ $history->book->cover_file_name ? Storage::url($history->book->cover_file_name) : '/img/book/default.jpg' }}"
                                        alt="{{ $history->book->title }}"
                                        class="w-full aspect-2/3 object-cover rounded-lg" />
                                </figure>

                                <div class="flex flex-col flex-1 justify-between space-y-3">
                                    <div class="space-y-2">
                                        <p class="text-sm font-medium text-gray-800">
                                            {{ $history->book->title }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $history->book->author ?? '-' }}
                                        </p>

                                        {{-- Progress Bar --}}
                                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-blue-500 transition-all duration-300"
                                                style="width: {{ $history->progress_percent }}%">
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            {{ number_format($history->progress_percent, 0) }}% selesai
                                            @if ($history->last_page && $history->total_pages)
                                                &middot; Halaman {{ $history->last_page }} /
                                                {{ $history->total_pages }}
                                            @endif
                                        </p>
                                    </div>

                                    <div class="flex gap-2">
                                        {{-- Tombol lanjut baca — menuju BookReaderController --}}
                                        <a href="{{ route('anggota.books.read', $history->book) }}"
                                            class="self-start text-sm bg-blue-600 text-white px-3 py-1.5 rounded-lg hover:bg-blue-500 transition">
                                            {{ $history->progress_percent > 0 ? 'Lanjut membaca' : 'Mulai membaca' }}
                                        </a>

                                        @if ($history->status === 'finished')
                                            <span
                                                class="self-start text-xs bg-green-100 text-green-700 px-2 py-1.5 rounded-lg font-medium">
                                                ✓ Selesai
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="flex flex-col items-center justify-center py-16 text-center gap-2">
                                <p class="text-2xl">📚</p>
                                <p class="text-sm text-slate-500">Belum ada buku yang dibaca</p>
                                <a href="{{ route('anggota.home') }}"
                                    class="mt-2 text-sm text-blue-600 hover:underline">
                                    Jelajahi buku
                                </a>
                            </div>
                        @endforelse
                    </div>
                </template>

                {{-- TAB: FAVORIT --}}
                <template x-if="activeTab === 'favorites'">
                    <div class="p-4 space-y-4">
                        @forelse ($this->favorites as $favorite)
                            <article class="flex gap-4 pb-4 border-b last:border-0 last:pb-0">
                                <figure class="w-20 sm:w-24 shrink-0">
                                    <img src="{{ $favorite->cover_file_name ? Storage::url($favorite->cover_file_name) : '/img/book/default.jpg' }}"
                                        alt="{{ $favorite->title }}"
                                        class="w-full aspect-2/3 object-cover rounded-lg" />
                                </figure>

                                <div class="flex flex-col flex-1 justify-between space-y-3">
                                    <div class="space-y-2">
                                        <p class="text-sm font-medium text-gray-800">
                                            {{ $favorite->title }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $favorite->author ?? '-' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $favorite->categories->pluck('name')->join(', ') }}
                                        </p>
                                    </div>

                                    <a href="{{ route('anggota.books.read', $favorite) }}"
                                        class="self-start text-sm bg-blue-600 text-white px-3 py-1.5 rounded-lg hover:bg-blue-500 transition">
                                        Baca
                                    </a>
                                </div>
                            </article>
                        @empty
                            <div class="flex flex-col items-center justify-center py-16 text-center gap-2">
                                <p class="text-2xl">❤️</p>
                                <p class="text-sm text-slate-500">Belum ada buku favorit</p>
                            </div>
                        @endforelse
                    </div>
                </template>

            </div>
        </section>

        <!-- PREMIUM -->
        <section class="px-4 md:px-10">
            <div class="bg-linear-to-br from-blue-600 to-indigo-600 rounded-xl p-4 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-white">Akun premium</h2>
                    <p class="text-sm text-white">Akses ke semua buku tanpa batas</p>
                </div>

                <a href="{{ route('anggota.subscriptions') }}"
                    class="text-sm text-blue-600 bg-white px-4 py-2 rounded-lg hover:shadow-md">
                    Tingkatkan
                </a>
            </div>
        </section>

        <!-- PENGATURAN -->
        <section class="px-4 md:px-10">
            <ul class="rounded-lg bg-white shadow overflow-hidden">
                <li class="hover:bg-gray-50 transition">
                    <a href="{{ route('anggota.transaction.history') }}" class="block p-4 text-gray-800 font-medium">
                        Riwayat Transaksi
                    </a>
                </li>
            </ul>
        </section>
    </div>
</div>
