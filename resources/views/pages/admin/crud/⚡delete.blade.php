<?php

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use App\Traits\WithFlashMessages;
use App\Models\Book;

new class extends Component {
    use WithFlashMessages;

    public $open = false;
    public $deleteId = null;
    public $deleteTitle = null;

    #[On('open-delete-modal')]
    public function openModal($id)
    {
        $this->deleteId = $id;
        $this->deleteTitle = Book::find($this->deleteId)?->title;
        $this->open = true;
    }

    public function delete()
    {
        Book::findOrFail($this->deleteId)->delete();
        $this->dispatch('close-delete-modal');
        $this->flashMessage('sukses', 'buku berhasil dihapus', 'admin.books');
        $this->deleteId = null;
        $this->open = false;
    }
};
?>

<div>
    <section x-data="{ open: false }" x-on:open-delete-modal.window="open = true"
        x-on:close-delete-modal.window="open = false" x-show="open" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6">
                <button type="button"
                    class="absolute top-3 end-2.5 text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center items-center"
                    @click="open = false">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18 17.94 6M18 18 6.06 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-fg-disabled w-12 h-12" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="mb-6 text-body">Apakah kamu yakin ingin menghapus buku dengan judul
                        <span class="font-semibold">

                            {{ $this->deleteTitle }}?
                        </span>
                    </h3>
                    </h3>
                    <div class="flex items-center space-x-4 justify-center">
                        <button wire:click="delete" type="button"
                            class="text-white bg-danger box-border border border-transparent hover:bg-danger-strong focus:ring-4 focus:ring-danger-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                            Hapus
                        </button>
                        <button @click="open = false" type="button"
                            class="text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
