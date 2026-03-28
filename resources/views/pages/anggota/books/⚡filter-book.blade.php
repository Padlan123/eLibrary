<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use App\Models\Book;

new class extends Component {
    public $keyword;

    #[On('search-filter')]
    public function updateKeyword($search)
    {
        $this->keyword = $search;
        unset($this->searches);
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

    #[Computed]
    public function searches()
    {
        return Book::with([
            'favoriteBooks' => function ($query) {
                $query->where('user_id', auth()->id());
            },
        ])
            ->where('title', 'LIKE', '%' . $this->keyword . '%')
            ->get();
    }
};
?>
<div>



    <div x-data="{
        loading: false,
        show: false,
        init() {
            Livewire.on('search-filter', () => {
                this.loading = true
                this.show = false
            })
    
            $wire.$watch('keyword', (value) => {
                if (!value) {
                    this.show = false
                    this.loading = false
                    return
                }
                this.loading = false
                this.$nextTick(() => { this.show = true })
            })
        }
    }" Saat keyword berubah jadi kosong/null, show langsung di-set false sehingga background
        hilang.>
        {{-- Skeleton loading --}}
        <template x-if="loading && $wire.keyword" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-2 ">
            <div class="px-8 pt-20 space-y-4 fixed top-0 left-0 bg-amber-50 w-full py-12 z-99">
                <div class="h-4 w-40 bg-gray-200 rounded animate-pulse"></div>
                <section class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @for ($i = 0; $i < 4; $i++)
                        <div class="bg-white rounded-lg overflow-hidden shadow-sm">
                            <div class="h-44 bg-gray-200 animate-pulse"></div>
                            <div class="p-3 space-y-2">
                                <div class="h-3 bg-gray-200 rounded animate-pulse"></div>
                                <div class="h-3 w-2/3 bg-gray-200 rounded animate-pulse"></div>
                            </div>
                        </div>
                    @endfor
                </section>
            </div>
        </template>

        {{-- Hasil pencarian --}}
        <div x-show="show && !loading" x-transition:enter="transition ease-out duration-400"
            x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            class="px-8 pt-24 space-y-4 fixed top-0 left-0 bg-amber-50 w-full py-12 z-99">
            @if ($this->keyword)
                <div class="flex justify-between text-sm text-gray-600">
                    <p>
                        Hasil untuk:
                        <span class="font-semibold text-gray-800">{{ $this->keyword }}</span>
                        <span class="text-gray-400">({{ $this->searches->count() }} buku)</span>
                    </p>
                </div>

                <section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4">
                    @forelse ($this->searches as $search)
                        <article wire:key="{{ $search->id }}" x-data="{ show: false }" x-init="setTimeout(() => show = true, {{ $loop->index * 60 }})"
                            x-show="show" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 -translate-y-3"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="relative group flex gap-3 p-3 bg-white rounded-md shadow-sm hover:shadow-lg hover:-translate-y-1 transition-transform ease-out duration-500">

                            <figure class="w-20 shrink-0 aspect-2/3 overflow-hidden rounded-md">
                                <img src="{{ $search->cover_file_name ? Storage::url($search->cover_file_name) : url('https://img.pikbest.com/origin/09/02/31/56bpIkbEsTFtz.jpg!f305cw') }}"
                                    loading="{{ $loop->index < 3 ? 'eager' : 'lazy' }}"
                                    fetchpriority="{{ $loop->index < 3 ? 'high' : 'auto' }}"
                                    class="w-full h-full object-cover transition duration-500 group-hover:scale-105" />
                            </figure>

                            <div class="flex flex-col justify-between flex-1">
                                <div>
                                    <h3 class="text-sm md:text-base font-medium text-gray-700 line-clamp-2">
                                        <a href="#" class="hover:text-blue-600 transition">
                                            {{ $search->title }}
                                        </a>
                                    </h3>
                                    <p class="text-xs text-gray-500">{{ $search->author }}</p>
                                    <span class="text-xs text-gray-600">{{ $search->category_names }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="space-x-2">
                                        <a href="{{ route('anggota.books.read', $search) }}"
                                            class="px-3 py-1 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-md transition">
                                            Baca
                                        </a>

                                        @php $isFavorite = $search->favoriteBooks->isNotEmpty(); @endphp

                                        @if ($isFavorite)
                                            <button wire:loading.remove
                                                wire:target="unfavorite({{ $search->id }}), favorite({{ $search->id }})"
                                                wire:click="unfavorite({{ $search->id }})"
                                                class="px-3 py-1 text-sm border border-gray-300 rounded-lg bg-gray-100 transition">
                                                Batalkan Suka
                                            </button>
                                        @else
                                            <button wire:loading.remove
                                                wire:target="unfavorite({{ $search->id }}), favorite({{ $search->id }})"
                                                wire:click="favorite({{ $search->id }})"
                                                class="px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                                                Suka
                                            </button>
                                        @endif

                                        <button wire:loading
                                            wire:target="unfavorite({{ $search->id }}), favorite({{ $search->id }})"
                                            class="px-3 py-1 text-sm bg-gray-200 rounded-lg transition">
                                            Loading...
                                        </button>
                                    </div>

                                    <a href="{{ route('anggota.detail-book', $search->id) }}"
                                        class="text-xs text-gray-500 hover:text-blue-600 transition">
                                        Lihat detail →
                                    </a>
                                </div>
                            </div>

                            @if ($search->subscription === true)
                                <span
                                    class="absolute right-2 top-2 z-99 bg-warning-soft text-fg-warning text-xs font-medium px-2 py-0.5 rounded shadow">
                                    Premium
                                </span>
                            @endif
                        </article>
                    @empty
                        <p class="col-span-4 text-center text-sm text-gray-400">Buku tidak ditemukan.</p>
                    @endforelse
                </section>
            @endif
        </div>
    </div>

</div>
