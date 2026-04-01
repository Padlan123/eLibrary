{{-- resources/views/vendor/pagination/custom.blade.php --}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-center gap-1 flex-wrap mt-8">
        {{-- Tombol Previous --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1.5 text-sm text-gray-300 bg-gray-100 rounded-lg cursor-not-allowed select-none">
                ← sebelumnya
            </span>
        @else
            <button wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled"
                x-on:click="$el.closest('section').scrollIntoView({ behavior: 'smooth', block: 'start' })"
                class="px-3 py-1.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition">
                ← sebelumnya
            </button>
        @endif

        {{-- Nomor Halaman --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-3 py-1.5 text-sm text-gray-400 select-none">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-1.5 text-sm font-semibold text-white bg-blue-600 rounded-lg select-none">
                            {{ $page }}
                        </span>
                    @else
                        <button wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                            wire:loading.attr="disabled"
                            x-on:click="$el.closest('section').scrollIntoView({ behavior: 'smooth', block: 'start' })"
                            class="px-3 py-1.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 transition">
                            {{ $page }}
                        </button>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Tombol Next --}}
        @if ($paginator->hasMorePages())
            <button wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled"
                x-on:click="$el.closest('section').scrollIntoView({ behavior: 'smooth', block: 'start' })"
                class="px-3 py-1.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition">
                Selanjutnya →
            </button>
        @else
            <span class="px-3 py-1.5 text-sm text-gray-300 bg-gray-100 rounded-lg cursor-not-allowed select-none">
                Selanjutnya →
            </span>
        @endif
    </nav>
@endif
