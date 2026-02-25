<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Book;
use App\Models\Category;

new class extends Component {
    public $category = '';

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
            ->paginate(10);

        return $books;
    }

    #[Computed]
    public function categories()
    {
        return Category::all();
    }

    #[Computed]
    public function render()
    {
        return $this->view()->layout('layouts.admin', ['title' => 'Kelola Buku']);
    }
};
?>

<div>
    <div class="p-4 md:p-8 space-y-4">
        <button data-modal-target="form-modal" data-modal-toggle="form-modal"
            class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong  shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5"
            type="button">
            Tambah E-Book
        </button>
        <section id="form-modal" tabindex="-1" aria-hidden="true"
            class="hidden bg-white/80 backdrop-blur-md rounded-2xl shadow-lg p-6 md:p-8 border">
            <h2 class="font-semibold mb-6">Tambah E-Book</h2>

            <form wire:submit="save" class="flex flex-col">

                <div class="flex px-4 py-3 gap-12 items-center">
                    <label for="judul">Judul</label>
                    <input id="judul" placeholder="Masukkan judul e-book"
                        class="border rounded-xl outline-none px-4 py-3 w-full" />
                </div>

                <div class=" px-4 py-3 gap-12">
                    <label>kategori</label>
                    <ul
                        class="flex items-center select-none text-sm font-medium text-heading bg-neutral-primary-soft border border-transparent rounded-base gap-6 px-4 py-2">
                        <li class="w-24 shadow-sm">
                            <div class="flex items-center ps-3">
                                <input id="vue-checkbox-list" type="checkbox" value=""
                                    class="w-4 h-4  rounded-xs bg-neutral-secondary-medium ">
                                <label for="vue-checkbox-list"
                                    class="w-full py-3 ms-2 text-sm font-medium text-heading">Sains</label>
                            </div>
                        </li>
                        <li class="w-24 shadow-sm">
                            <div class="flex items-center ps-3">
                                <input id="vue-checkbox-list" type="checkbox" value=""
                                    class="w-4 h-4  rounded-xs bg-neutral-secondary-medium ">
                                <label for="vue-checkbox-list"
                                    class="w-full py-3 ms-2 text-sm font-medium text-heading">Sains</label>
                            </div>
                        </li>
                        <li class="w-24 shadow-sm">
                            <div class="flex items-center ps-3">
                                <input id="vue-checkbox-list" type="checkbox" value=""
                                    class="w-4 h-4  rounded-xs bg-neutral-secondary-medium ">
                                <label for="vue-checkbox-list"
                                    class="w-full py-3 ms-2 text-sm font-medium text-heading">Sains</label>
                            </div>
                        </li>
                        <li class="w-24 shadow-sm">
                            <div class="flex items-center ps-3">
                                <input id="vue-checkbox-list" type="checkbox" value=""
                                    class="w-4 h-4  rounded-xs bg-neutral-secondary-medium ">
                                <label for="vue-checkbox-list"
                                    class="w-full py-3 ms-2 text-sm font-medium text-heading">Sains</label>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="flex px-4 py-3 gap-12 items-center">
                    <label for="tahun">Tahun</label>
                    <input id="tahun" placeholder="Masukkan tahun terbit"
                        class="block border rounded-xl px-4 py-3 outline-none w-full" />
                </div>

                <div class="flex px-4 py-3 gap-10 items-center">
                    <label for="penulis">Penulis</label>
                    <input id="penulis" placeholder="Masukkan nama penulis"
                        class="block border rounded-xl px-4 py-3 outline-none w-full" />
                </div>

                <div class="px-4 py-3">
                    <label for="">sinopsis</label>
                    <textarea rows="4" placeholder="Masukkan sinopsis e-book"
                        class="mt-4 w-full border rounded-xl px-4 py-3outline-none"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <span>Tambahkan Foto</span>
                    <input type="file" name="foto" class="border rounded-xl p-2" />
                    <span>Tambahkan PDF</span>
                    <input type="file" name="pdf" class="border rounded-xl p-2" />
                </div>

                <button wire:click="save"
                    class="mt-6 bg-linear-to-r from-indigo-500 to-blue-600 text-white px-6 py-3 rounded-xl shadow hover:from-indigo-600 hover:to-blue-700 transition-colors duration-300 w-full">
                    <span wire:loading.remove>Simpan</span>
                    <span wire:loading wire:target="save">Loading...</span>
                </button>
            </form>
        </section>

        <section class="bg-white/80 backdrop-blur-md rounded-2xl shadow-lg p-6 md:p-8 border overflow-x-auto">
            <div class="flex justify-between">
                <h2 class="font-semibold mb-6">Daftar E-Book</h2>
                <select wire:model.live.debounce="category" id="kategori"
                    class="w-1/4 px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body">
                    <option value="">Semua kategori</option>
                    @foreach ($this->categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <table class="w-full border-separate border-spacing-y-3 min-w-150">
                <thead>
                    <tr>
                        <th class="p-4">Judul</th>
                        <th class="p-4">Penulis</th>
                        <th class="p-4">Tahun</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($this->books as $book)
                        <tr wire:key="{{ $book->id }}" class="bg-white rounded-xl shadow-md">
                            <td class="p-4">{{ $book->title }}</td>
                            <td class="p-4">{{ $book->author }}</td>
                            <td class="p-4">{{ $book->publication_year }}</td>
                            <td class="p-4">
                                @foreach ($book->categories as $category)
                                    {{ $category->name }} @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </td>

                            <td class="p-4 text-center space-x-2">
                                <button onclick="openEditModal()"
                                    class="bg-blue-50 text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="size-6">
                                        <path
                                            d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                                    </svg>

                                </button>

                                <button onclick="openDeleteModal()"
                                    class="bg-red-50 text-red-600 px-3 py-2 rounded-lg hover:bg-red-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="size-6">
                                        <path fill-rule="evenodd"
                                            d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                            clip-rule="evenodd" />
                                    </svg>

                                </button>
                            </td>
                        @empty
                            <td colspan="5" class="p-4 text-center text-gray-500">Tidak ada buku tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </div>
</div>
