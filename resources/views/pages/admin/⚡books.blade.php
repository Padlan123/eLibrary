<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Book;
use App\Models\Category;
use Livewire\WithPagination;

new class extends Component {
    public $category = '';

    #[Computed]
    public function categories()
    {
        return Category::orderBy('name', 'asc')->get();
    }

    public function modalCreate()
    {
        $this->dispatch('open-create-modal');
    }
    public function modalDelete($id)
    {
        $this->dispatch('open-delete-modal', id: $id);
    }

    public function modalUpdate($id)
    {
        $this->dispatch('open-update-modal', id: $id);
    }

    public function recommended($id)
    {
        Book::find($id)->update(['is_recommended' => true]);
    }

    public function unrecommended($id)
    {
        Book::find($id)->update(['is_recommended' => false]);
    }

    #[Computed]
    public function books()
    {
        $books = Book::query()
            ->with(['categories'])
            ->when($this->category, function ($query) {
                $query->whereHas('categories', function ($q) {
                    $q->where('categories.id', $this->category);
                });
            })
            ->orderBy('title', 'asc')
            ->latest()
            ->get();
        return $books;
    }

    #[Computed]
    public function render()
    {
        return $this->view()->layout('layouts.admin', ['title' => 'Kelola Buku']);
    }
};
?>

<div>
    <div class="px-4 md:px-8 space-y-4">
        <button wire:click="modalCreate"
            class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong  shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5"
            type="button">
            Tambah E-Book
        </button>
        @if (session('error'))
            <div id="toast-warning"
                class="flex items-center w-full max-w-sm p-4 text-body bg-neutral-primary-soft rounded-base shadow-xs border border-default"
                role="alert">
                <div
                    class="inline-flex items-center justify-center shrink-0 w-7 h-7 text-fg-warning bg-warning-soft rounded">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 11.917 9.724 16.5 19 7.5" />
                    </svg>
                    <span class="sr-only">Check icon</span>
                </div>
                <div class="ms-3 text-sm font-normal">{{ session('error') }}</div>
                <button type="button"
                    class="ms-auto flex items-center justify-center text-body hover:text-heading bg-transparent box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded text-sm h-8 w-8 focus:outline-none"
                    data-dismiss-target="#toast-warning" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18 17.94 6M18 18 6.06 6" />
                    </svg>
                </button>
            </div>
        @endif
        @if (session('sukses'))
            <div id="toast-success"
                class="flex items-center w-full max-w-sm p-4 text-body bg-neutral-primary-soft rounded-base shadow-xs border border-default"
                role="alert">
                <div
                    class="inline-flex items-center justify-center shrink-0 w-7 h-7 text-fg-success bg-success-soft rounded">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 11.917 9.724 16.5 19 7.5" />
                    </svg>
                    <span class="sr-only">Check icon</span>
                </div>
                <div class="ms-3 text-sm font-normal">{{ session('sukses') }}</div>
                <button type="button"
                    class="ms-auto flex items-center justify-center text-body hover:text-heading bg-transparent box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded text-sm h-8 w-8 focus:outline-none"
                    data-dismiss-target="#toast-success" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18 17.94 6M18 18 6.06 6" />
                    </svg>
                </button>
            </div>
        @endif

        <section class="bg-white/80 backdrop-blur-md rounded-2xl shadow p-6 md:p-8 border border-blue-100">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <h2 class="font-semibold text-lg text-gray-800">Daftar E-Book</h2>
                <select wire:model.live.debounce="category" id="kategori"
                    class="px-3 py-2 bg-white border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm w-full sm:w-48">
                    <option value="">Semua kategori</option>
                    @foreach ($this->categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- DESKTOP: Table (hidden on mobile) --}}
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="pb-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Judul</th>
                            <th class="pb-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Penulis
                            </th>
                            <th class="pb-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Tahun</th>
                            <th class="pb-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Kategori
                            </th>
                            <th
                                class="pb-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($this->books as $book)
                            <tr wire:key="{{ $book->id }}"
                                class="hover:bg-gray-50/70 transition-colors duration-150">
                                <td class="py-3.5 px-4 text-sm font-medium text-gray-800">{{ $book->title }}</td>
                                <td class="py-3.5 px-4 text-sm text-gray-500">{{ $book->author }}</td>
                                <td class="py-3.5 px-4 text-sm text-gray-500">{{ $book->publication_year }}</td>
                                <td class="py-3.5 px-4">
                                    @foreach ($book->categories as $category)
                                        <span
                                            class="inline-block bg-blue-50 text-blue-700 text-xs font-medium px-2.5 py-1 rounded-full mr-1 mb-1">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if ($book->is_recommended)
                                            {{-- Sudah direkomendasikan: bintang solid kuning, tooltip "Batalkan rekomendasi" --}}
                                            <button wire:click="unrecommended({{ $book->id }})"
                                                title="Batalkan rekomendasi"
                                                class="inline-flex items-center gap-1.5 bg-yellow-400 text-white text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-yellow-500 transition-colors cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="size-3.5">
                                                    <path fill-rule="evenodd"
                                                        d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        @else
                                            {{-- Belum direkomendasikan: bintang outline, abu-abu --}}
                                            <button wire:click="recommended({{ $book->id }})"
                                                title="Rekomendasikan buku ini"
                                                class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-400 text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-yellow-50 hover:text-yellow-400 transition-colors cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="size-3.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.499Z" />
                                                </svg>
                                            </button>
                                        @endif
                                        <button wire:click="modalUpdate({{ $book->id }})"
                                            class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-600 text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-blue-100 transition-colors cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="w-3.5 h-3.5">
                                                <path
                                                    d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                                            </svg>

                                        </button>
                                        <button wire:click="modalDelete({{ $book->id }})" type="button"
                                            class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-red-100 transition-colors cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="w-3.5 h-3.5">
                                                <path fill-rule="evenodd"
                                                    d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                                    clip-rule="evenodd" />
                                            </svg>

                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-sm text-gray-400">
                                    Tidak ada buku di kategori ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- MOBILE: Cards (hidden on desktop) --}}
            <div class="lg:hidden space-y-3">
                @forelse ($this->books as $book)
                    <div wire:key="card-{{ $book->id }}"
                        class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm">

                        {{-- Title & Author --}}
                        <div class="mb-3">
                            <p class="font-semibold text-gray-800 text-sm leading-snug">{{ $book->title }}</p>
                            <p class="text-gray-400 text-xs mt-0.5">{{ $book->author }}</p>
                        </div>

                        {{-- Year & Categories --}}
                        <div class="flex flex-wrap items-center gap-1.5 mb-3">
                            <span class="bg-gray-100 text-gray-500 text-xs px-2.5 py-1 rounded-full">
                                {{ $book->publication_year }}
                            </span>
                            @foreach (explode(',', $book->category_names) as $cat)
                                <span class="bg-blue-50 text-blue-700 text-xs font-medium px-2.5 py-1 rounded-full">
                                    {{ trim($cat) }}
                                </span>
                            @endforeach
                        </div>

                        {{-- Actions --}}
                        <div class="flex gap-2 pt-3 border-t border-gray-100">

                            <button wire:click="modalUpdate({{ $book->id }})"
                                class="flex-1 flex items-center justify-center gap-1.5 bg-blue-50 text-blue-600 text-xs font-medium py-2 rounded-lg hover:bg-blue-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-3.5 h-3.5">
                                    <path
                                        d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                                </svg>
                                Edit
                            </button>
                            <button wire:click="modalDelete({{ $book->id }})" type="button"
                                class="flex-1 flex items-center justify-center gap-1.5 bg-red-50 text-red-600 text-xs font-medium py-2 rounded-lg hover:bg-red-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-3.5 h-3.5">
                                    <path fill-rule="evenodd"
                                        d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                        clip-rule="evenodd" />
                                </svg>
                                Hapus
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-sm text-gray-400">
                        Tidak ada buku di kategori ini
                    </div>
                @endforelse
            </div>

        </section>
    </div>
</div>
