<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Traits\WithBookValidation;
use App\Traits\WithFlashMessages;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

new class extends Component {
    use WithFileUploads, WithBookValidation, WithFlashMessages;

    public $open = false;

    public $book_categories = [];
    public bool $subscription = false;

    public $title;
    public $publication_year;
    public $author;
    public $publisher;
    public $summary;
    public $cover_file_name;
    public $pdf_file_name;

    public $preview;

    public $categories = [];

    #[On('open-create-modal')]
    public function openModal()
    {
        $this->categories = Category::orderBy('name', 'asc')->get();
        $this->open = true;
    }

    #[computed]
    public function getCategoriesBook()
    {
        if ($this->book_categories == []) {
            return;
        }

        return Category::whereIn('id', $this->book_categories)->get();
    }

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
                    'cover_file_name' => $this->cover_file_name->store('cover', 'public'),
                    'pdf_file_name' => $this->pdf_file_name->store('pdf', 'public'),
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

<div wire:ignore>
    <section x-data="{
        open: false,
        preview: null,
        cover_file_name: 'Tidak ada file dipilih',
    }" x-on:open-create-modal.window="open = true"
        x-on:close-create-modal.window="open = false" x-cloak @click="open = false" x-show="open"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">

        <div @click.stop class="relative p-4 w-full max-w-md max-h-full">
            <div
                class="relative bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6 fade-in-up">
                <div class="flex items-center justify-between border-b border-default pb-4 md:pb-5">
                    <h3 class="text-lg font-medium text-heading">
                        Buat E-Book Baru
                    </h3>
                    <button type="button"
                        class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center items-center"
                        @click="open = false">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18 17.94 6M18 18 6.06 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
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

                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" type="button"
                            class="inline-flex items-center text-body bg-white border border-gray-600 hover:bg-gray-300 mx-auto shadow-xs leading-5 rounded-base text-sm px-4 py-2.5 w-full appearance-none focus:outline-none focus:ring-0 focus:border-brand peer">
                            Kategori
                            <svg class="h-4 w-4 ms-auto rtl:rotate-180" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m9 5 7 7-7 7" />
                            </svg>
                        </button>
                        @error('book_categories')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        <div x-show="open" @click.outside="open = false" x-transition
                            class="absolute left-1/2 top-0 ml-2 z-50 bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-48 md:w-60 md:left-full">
                            <ul class="select-none overflow-y-auto p-2 text-sm text-body font-medium">
                                @foreach ($this->categories as $category)
                                    <li wire:key="{{ $category->id }}">
                                        <div
                                            class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded-md">
                                            <input wire:model.live.debounce.300ms="book_categories"
                                                id="{{ $category->id }}" type="checkbox" value="{{ $category->id }}"
                                                class="w-4 h-4 border border-default-strong rounded-xs bg-neutral-secondary-strong focus:ring-2 focus:ring-brand-soft">
                                            <label for="{{ $category->id }}"
                                                class="w-full ms-2 text-sm font-medium text-heading">{{ $category->name }}</label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @if ($this->getCategoriesBook)
                            <h2 class="my-2 text-base font-medium text-gray-700">Kategori yang dipilih</h2>
                            <ul class="max-w-md space-x-4 gap-y-2 text-body flex flex-wrap mb-2">
                                @foreach ($this->getCategoriesBook as $category)
                                    <li class="list-none">
                                        <span
                                            class="bg-gray-600 text-neutral-primary text-xs font-medium px-2 py-1 rounded">{{ $category->name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
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
                            <input wire:model="subscription" id="default-radio-1" type="radio" value="0"
                                name="default-radio"
                                class="w-4 h-4 text-neutral-primary border-default-medium bg-neutral-secondary-medium rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border appearance-none">
                            <label for="default-radio-1"
                                class="select-none ms-2 text-sm font-medium text-heading">gratis</label>
                        </div>
                        <div class="flex items-center">
                            <input wire:model="subscription" id="default-radio-2" type="radio" value="1"
                                name="default-radio"
                                class="w-4 h-4 text-neutral-primary border-default-medium bg-neutral-secondary-medium rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border appearance-none">
                            <label for="default-radio-2"
                                class="select-none ms-2 text-sm font-medium text-heading">premium</label>
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
                            @change="const file = $el.files[0];if (file) {preview = URL.createObjectURL(file);cover_file_name = file.name;$wire.upload('preview', file);} else {cover_file_name = 'Tidak ada file dipilih';}"
                            accept="image/*" />
                        <label for="cover_file_name"
                            class="flex items-center cursor-pointer border border-gray-600 bg-white rounded-base w-full shadow-xs overflow-hidden">
                            <span
                                class="bg-white border-r border-gray-600 text-gray-800 text-sm px-4 py-2 whitespace-nowrap transition-colors">
                                Pilih File
                            </span>
                            <span x-text="cover_file_name" class="text-gray-400 text-sm px-3 truncate"></span>
                        </label>
                        <img x-show="preview" :src="preview" class="mt-2 w-full rounded-lg object-cover" />
                        @error('cover_file_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror


                        <label>Tambahkan PDF</label>
                        <input wire:model="pdf_file_name" type="file" class="hidden" id="pdf_file_name"
                            @change="pdf_file_name = $event.target.files[0] ? $event.target.files[0].name : 'Tidak ada file dipilih'" />
                        <label for="pdf_file_name"
                            class="flex items-center cursor-pointer border border-gray-600 bg-white rounded-base w-full shadow-xs overflow-hidden">
                            <span
                                class="bg-white border-r border-gray-600 text-gray-800 text-sm px-4 py-2 whitespace-nowrap transition-colors">
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
                        <span wire:loading class="opacity-50" wire:target="save, cover_file_name, pdf_file_name">⏳
                            Sedang
                            mengupload
                            file</span>
                        <span wire:loading.remove wire:target="save, cover_file_name, pdf_file_name">Submit</span>
                    </button>
                </form>
            </div>
        </div>

    </section>
</div>
