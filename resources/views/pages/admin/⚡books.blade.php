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

    #[Computed]
    public function books()
    {
        $books = Book::query()
            ->with('categories')
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
        <section class="bg-white/80 backdrop-blur-md rounded-2xl shadow-lg p-6 md:p-8 border overflow-x-auto">
            <div class="flex justify-between w-xl md:w-full">
                <h2 class="font-semibold mb-4">Daftar E-Book</h2>
                <select wire:model.live.debounce="category" id="kategori"
                    class="px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body w-1/4">
                    <option value="">Semua kategori</option>
                    @foreach ($this->categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <table class="w-full border-separate border-spacing-y-3 min-w-150">
                <thead>
                    <tr>
                        <th class="p-3">Judul</th>
                        <th class="p-3">Penulis</th>
                        <th class="p-3">Tahun</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($this->books as $book)
                        <tr wire:key="{{ $book->id }}" class="bg-white rounded-xl shadow-md">
                            <td class="p-3">{{ $book->title }}</td>
                            <td class="p-3">{{ $book->author }}</td>
                            <td class="p-3">{{ $book->publication_year }}</td>
                            <td class="p-3">
                                @foreach ($book->categories as $category)
                                    {{ $category->name }} @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </td>

                            <td class="p-3 text-center space-x-2">
                                <button wire:click="modalUpdate({{ $book->id }})"
                                    class="bg-blue-50 text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="size-6">
                                        <path
                                            d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                                    </svg>

                                </button>

                                <button wire:click="modalDelete({{ $book->id }})"
                                    class="bg-red-50 text-red-600 px-3 py-2 rounded-lg" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="size-6">
                                        <path fill-rule="evenodd" d=" M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256
                                    1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087
                                    6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564
                                    1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815
                                    2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15
                                    4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355
                                    5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0
                                    1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-3 text-center text-gray-500">tidak ada buku di kategori ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </div>
    @livewire('pages::admin.crud.create')
    @livewire('pages::admin.crud.delete')
    @livewire('pages::admin.crud.update')
</div>
