<?php

use Livewire\Component;

new class extends Component {
    public $books;
};
?>

<div>
    <div x-data="{
        init() {
            Livewire.hook('commit', () => {
                this.$nextTick(() => {
                    document.getElementById('books-pagination-section')
                        .scrollIntoView({ behavior: 'smooth' });
                });
            });
        }
    }" class="flex items-center justify-between mt-6">
        {{-- Info --}}
        <div class="text-sm text-gray-600">
            Halaman {{ $this->books->currentPage() }} dari {{ $this->books->lastPage() }}
            (Total: {{ $this->books->total() }} buku)
        </div>

        {{-- Buttons --}}
        <div class="flex gap-2">
            @if ($this->books->onFirstPage())
                <button disabled class="px-4 py-2 bg-gray-200 text-gray-400 rounded cursor-not-allowed">
                    ← Sebelumnya
                </button>
            @else
                <button wire:click="previousPage" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    ← Sebelumnya
                </button>
            @endif

            @if ($this->books->hasMorePages())
                <button wire:click="nextPage" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Selanjutnya →
                </button>
            @else
                <button disabled class="px-4 py-2 bg-gray-200 text-gray-400 rounded cursor-not-allowed">
                    Selanjutnya →
                </button>
            @endif
        </div>
    </div>
</div>
