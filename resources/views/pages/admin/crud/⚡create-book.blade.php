<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Traits\WithBookValidation;
use App\Traits\WithFlashMessages;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

new class extends Component {
    use WithFileUploads, WithBookValidation, WithFlashMessages;

    public $book_categories = [];
    public bool $subscription = false;

    public $title;
    public $publication_year;
    public $author;
    public $publisher;
    public $summary;
    public $cover_file_name;
    public $pdf_file_name;

    public $categories;

    public function save()
    {
        $this->validateBook();

        try {
            DB::transaction(function () {
                $book = Book::create([
                    'title' => $this->title,
                    'author' => $this->author,
                    'publication_year' => $this->publication_year,
                    'publisher' => $this->publisher,
                    'summary' => $this->summary,
                    'subscription' => $this->subscription,
                    'cover_file_name' => $this->cover_file_name,
                    'pdf_file_name' => $this->pdf_file_name,
                ]);

                $book->categories()->attach($this->book_categories);
            });
            $this->flashMessage('sukses', 'buku berhasil ditambahkan', 'admin.books');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }
};
?>

<div>
    <form wire:submit="save" class="flex flex-col space-y-4">

        <div class="relative">
            <input wire:model="title" type="text" id="judul"
                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-heading bg-transparent rounded-base border border-gray-500 appearance-none focus:outline-none focus:ring-0 focus:border-brand peer"
                placeholder=" " />
            <label for="judul"
                class="absolute text-sm text-body duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-left bg-neutral-primary px-2 peer-focus:px-2 peer-focus:text-fg-brand peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Judul</label>
            @error('title')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

        </div>

        <div class="gap-12">
            <button id="dropdownSearchButton" data-dropdown-toggle="dropdownSearch" type="button"
                class="inline-flex items-center text-white bg-gray-600 border border-transparent hover:bg-gray-700 mx-auto  shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 ">
                Kategori
                <svg class="w-4
                    h-4 ms-1.5 -me-1" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m19 9-7 7-7-7" />
                </svg>
            </button>
            @error('book_categories')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

            <div id="dropdownSearch"
                class="hidden z-99 bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-60">
                <ul class="select-none overflow-y-auto p-2 text-sm text-body font-medium"
                    aria-labelledby="dropdownSearchButton">
                    @foreach ($this->categories as $category)
                        <li wire:key="{{ $category->id }}">
                            <div
                                class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded-md">
                                <input wire:model="book_categories" id="{{ $category->id }}" type="checkbox"
                                    value="{{ $category->id }}"
                                    class="w-4 h-4 border border-default-strong rounded-xs bg-neutral-secondary-strong focus:ring-2 focus:ring-brand-soft">
                                <label for="{{ $category->id }}"
                                    class="w-full ms-2 text-sm font-medium text-heading">{{ $category->name }}</label>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="relative">
            <input wire:model="publication_year" type="text" id="tahun"
                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-heading bg-transparent rounded-base border border-gray-500 appearance-none focus:outline-none focus:ring-0 focus:border-brand peer"
                placeholder=" " />
            <label for="tahun"
                class="absolute text-sm text-body duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-left bg-neutral-primary px-2 peer-focus:px-2 peer-focus:text-fg-brand peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Tahun
                Terbit</label>
            @error('publication_year')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

        </div>

        <div class="relative">
            <input wire:model="author" type="text" id="penulis"
                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-heading bg-transparent rounded-base border border-gray-500 appearance-none focus:outline-none focus:ring-0 focus:border-brand peer"
                placeholder=" " />
            <label for="penulis"
                class="absolute text-sm text-body duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-left bg-neutral-primary px-2 peer-focus:px-2 peer-focus:text-fg-brand peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Penulis</label>
            @error('author')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

        </div>

        <div class="relative">
            <input wire:model="publisher" type="text" id="penerbit"
                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-heading bg-transparent rounded-base border border-gray-500 appearance-none focus:outline-none focus:ring-0 focus:border-brand peer"
                placeholder=" " />
            <label for="penerbit"
                class="absolute text-sm text-body duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-left bg-neutral-primary px-2 peer-focus:px-2 peer-focus:text-fg-brand peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Penerbit</label>
            @error('publisher')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

        </div>

        <div>
            <label>Akses buku</label>
            <div class="flex items-center my-4">
                <input wire:model="subscription" id="default-radio-1" type="radio" value="0" name="default-radio"
                    class="w-4 h-4 text-neutral-primary border-default-medium bg-neutral-secondary-medium rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border appearance-none">
                <label for="default-radio-1" class="select-none ms-2 text-sm font-medium text-heading">gratis</label>
            </div>
            <div class="flex items-center">
                <input wire:model="subscription" id="default-radio-2" type="radio" value="1" name="default-radio"
                    class="w-4 h-4 text-neutral-primary border-default-medium bg-neutral-secondary-medium rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border appearance-none">
                <label for="default-radio-2" class="select-none ms-2 text-sm font-medium text-heading">premium</label>
            </div>
            @error('subscription')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

        </div>


        <div class="py-3">
            <label for="">sinopsis</label>
            <textarea wire:model="summary" rows="4" placeholder="Masukkan sinopsis e-book"
                class="mt-4 w-full rounded-xl px-4 py-3 focus:outline-none focus:ring-0 focus:border-brand"></textarea>
            @error('summary')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

        </div>

        <div x-data="{ cover_file_name: 'Tidak ada file dipilih', pdf_file_name: 'Tidak ada file dipilih' }" class="grid grid-cols-1 gap-2 mt-4">
            <label>Tambahkan Cover Buku</label>
            <input wire:model="cover_file_name" type="file" class="hidden" id="cover_file_name"
                @change="cover_file_name = $event.target.files[0] ? $event.target.files[0].name : 'Tidak ada file dipilih'" />
            <label for="cover_file_name"
                class="flex items-center cursor-pointer border border-gray-600 bg-gray-700 rounded-base w-full shadow-xs overflow-hidden">
                <span
                    class="bg-gray-800 hover:bg-gray-600 text-white text-sm px-4 py-2 whitespace-nowrap transition-colors">
                    Pilih File
                </span>
                <span x-text="cover_file_name" class="text-gray-400 text-sm px-3 truncate"></span>
            </label>
            @error('cover_file_name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror


            <label>Tambahkan PDF</label>
            <input wire:model="pdf_file_name" type="file" class="hidden" id="pdf_file_name"
                @change="pdf_file_name = $event.target.files[0] ? $event.target.files[0].name : 'Tidak ada file dipilih'" />
            <label for="pdf_file_name"
                class="flex items-center cursor-pointer border border-gray-600 bg-gray-700 rounded-base w-full shadow-xs overflow-hidden">
                <span
                    class="bg-gray-800 hover:bg-gray-600 text-white text-sm px-4 py-2 whitespace-nowrap transition-colors">
                    Pilih File
                </span>
                <span x-text="pdf_file_name" class="text-gray-400 text-sm px-3 truncate"></span>
            </label>
            @error('pdf_file_name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

        </div>

        <button wire:loading.attr="disabled" wire:click="save"
            wire:loading.class="pointer-events-none cursor-not-allowed opacity-60"
            class="mt-6 bg-linear-to-r from-indigo-500 to-blue-600 text-white px-6 py-3 rounded-xl shadow hover:from-indigo-600 hover:to-blue-700 transition-colors duration-300 w-full">
            <span wire:loading class="opacity-50" wire:target="save, cover_file_name, pdf_file_name">⏳ Sedang
                mengupload
                file</span>
            <span wire:loading.remove wire:target="save, cover_file_name, pdf_file_name">Submit</span>
        </button>
    </form>
</div>
