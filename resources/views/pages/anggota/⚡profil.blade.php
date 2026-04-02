<?php

use Livewire\Component;
use App\Models\SubscriptionTransaction;
use App\Models\ReadingHistory;
use App\Models\Book;
use App\Models\User;
use Livewire\Attributes\Computed;

new class extends Component {
    public $initials = '';
    public string $username = '';
    public $open = false;

    public function mount()
    {
        $this->username = $this->user()->username;
        $this->setInitials();
    }

    protected function setInitials(): void
    {
        $words = explode(' ', trim($this->username));
        $this->initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
    }

    #[Computed]
    public function user()
    {
        return auth()->user();
    }

    public function openEditProfileModal()
    {
        $this->open = true;
    }

    public function save()
    {
        $this->validate([
            'username' => 'required|string|max:255',
        ]);

        $this->user()->update([
            'username' => $this->username,
        ]);

        $this->setInitials();

        $this->open = false;
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

<div class="space-y-12 py-24">

    <!-- PROFILE -->
    <section class="relative py-10 px-4 md:px-10">
        <div class="relative z-10 flex flex-col  items-center text-center">
            <div class="space-y-4 max-w-md">
                <div class="flex flex-col items-center">
                    <h1 class="text-xl md:text-2xl font-semibold text-gray-800">
                        {{ $this->user->username }}
                    </h1>
                    <h1 class="text-sm text-slate-400">
                        {{ $this->user->email }}
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

                <p class="text-sm text-gray-500">Bergabung sejak {{ $this->user->created_at->format('Y') }}</p>

                <button type="button" wire:click="openEditProfileModal"
                    class="inline-block text-sm text-white bg-blue-600 px-4 py-2 rounded-lg hover:bg-blue-500 transition">
                    Edit profil
                </button>
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
                            <a href="{{ route('anggota.home') }}" class="mt-2 text-sm text-blue-600 hover:underline">
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
                                    alt="{{ $favorite->title }}" class="w-full aspect-2/3 object-cover rounded-lg" />
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

    {{-- Modal Edit Profil  --}}
    <section x-data="{
        open: @entangle('open'),
        preview: null,
        cover_file_name: 'Tidak ada file dipilih',
    }" x-cloak x-show="open" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">

        <div @click.stop class="relative p-4 w-full max-w-md max-h-full">
            <div
                class="relative bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6 fade-in-up">
                <div class="flex items-center justify-between border-b border-default pb-4 md:pb-5">
                    <h3 class="text-lg font-medium text-heading">
                        Edit Nama Pengguna
                    </h3>
                    <button type="button"
                        class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center items-center"
                        @click="open = false">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <form wire:submit="save" class="flex flex-col space-y-4">

                    <div class="relative">
                        <input wire:model="username" type="text" id="judul"
                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-heading bg-transparent rounded-base border border-gray-500 appearance-none focus:outline-none focus:ring-0 focus:border-brand peer"
                            placeholder=" " />
                        <label for="judul"
                            class="absolute text-sm text-body duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-left bg-neutral-primary px-2 peer-focus:px-2 peer-focus:text-fg-brand peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Nama
                            Pengguna</label>

                    </div>
                    @error('username')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror

                    <button wire:loading.attr="disabled" wire:click="save"
                        wire:loading.class="pointer-events-none cursor-not-allowed opacity-60"
                        class="mt-6 bg-linear-to-r from-indigo-500 to-blue-600 text-white px-6 py-3 rounded-xl shadow hover:from-indigo-600 hover:to-blue-700 transition-colors duration-300 w-full">
                        <span wire:loading class="opacity-50" wire:target="save">⏳
                            Loading...</span>
                        <span wire:loading.remove wire:target="save">Ubah Profil</span>
                    </button>
                </form>
            </div>
        </div>

    </section>
</div>
