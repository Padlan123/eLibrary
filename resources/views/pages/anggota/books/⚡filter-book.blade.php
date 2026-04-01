<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Book;

new class extends Component {
    use WithPagination;
    public $keyword = '';
    public int $perPage = 6;

    #[Computed]
    public function searches()
    {
        return Book::where('title', 'LIKE', '%' . $this->keyword . '%')->paginate($this->perPage);
    }
};
?>
<section x-data="{
    getPerPage() {
        const width = window.innerWidth;
        if (width < 768) return 3;
        if (width < 1024) return 4;
        return 6;
    }
}" x-init="$wire.set('perPage', getPerPage());
window.addEventListener('resize', () => {
    $wire.set('perPage', getPerPage())
})" id="cari-buku"
    class="px-8 pt-24 flex flex-col space-y-4 w-full py-12 md:px-6 md:space-y-8 lg:py-24 fade-in-up">
    <header class="text-center">
        <h2 class="text-2xl font-semibold text-gray-700 uppercase tracking-widest">
            Cari Buku
        </h2>
    </header>

    <div class="flex items-center gap-4 px-6 lg:px-0 justify-center">
        <input wire:model.live.debounce.500ms="keyword" id="search" type="search"
            placeholder="Cari judul atau kategori"
            class="px-3 py-1 md:py-1.5 text-sm bg-gray-100 focus:bg-white rounded-lg outline-none border-none transition" />
        <p>
            <span class="text-gray-400">({{ $this->searches->total() }} buku)</span>
        </p>
    </div>
    <div wire:loading wire:target="gotoPage, nextPage, previousPage"
        class="absolute inset-0 bg-white/60 backdrop-blur-sm z-10 flex items-center justify-center rounded-lg">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
            </svg>
            Memuat buku...
        </div>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 md:w-full gap-4">
        @forelse ($this->searches as $search)
            <article wire:key="{{ $search->id }}"
                class="relative group flex gap-2 w-full p-3 bg-white rounded-md shadow-sm hover:shadow-lg hover:-translate-y-1 transition-transform ease-out duration-500">
                <figure class="w-20 shrink-0 aspect-2/3 overflow-hidden rounded-md">
                    <img src="{{ $search->cover_file_name ? Storage::url($search->cover_file_name) : url('https://img.pikbest.com/origin/09/02/31/56bpIkbEsTFtz.jpg!f305cw') }}"
                        loading="{{ $loop->index < 3 ? 'eager' : 'lazy' }}"
                        fetchpriority="{{ $loop->index < 3 ? 'high' : 'auto' }}"
                        class="w-full h-full object-cover transition duration-500 group-hover:scale-105" />
                </figure>

                <div class="flex flex-col justify-between flex-1">
                    <div>
                        <h3 class="text-sm md:text-base font-medium text-gray-700 w-36 truncate">
                            <a href="{{ route('anggota.detail-book', $search->id) }}"
                                class="hover:text-blue-600 transition ">
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
                        </div>

                        <a href="{{ route('anggota.detail-book', $search->id) }}"
                            class="text-xs text-gray-500 hover:text-blue-600 transition">
                            Lihat detail →
                        </a>
                    </div>
                </div>

                @if ($search->subscription === true)
                    <span
                        class="absolute right-2 top-2 z-2 bg-warning-soft text-fg-warning text-xs font-medium px-2 py-0.5 rounded shadow">
                        Premium
                    </span>
                @endif
            </article>
        @empty
            <p class="col-span-4 text-center w-full text-sm text-gray-400 py-12">
                Buku tidak ditemukan dengan kata kunci "{{ $this->keyword }}"
            </p>
        @endforelse
    </div>

    {{ $this->searches->links('vendor.pagination.custom') }}

</section>
