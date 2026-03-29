<?php

use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;

new #[Lazy] class extends Component {
    public function placeholder()
    {
        return view('placeholder.default', [
            'message' => 'memuat kategori...',
        ]);
    }

    public bool $showAllCategories = false;

    #[Computed]
    public function categories()
    {
        return Category::orderBy('name', 'asc')->get();
    }
};
?>

<div>
    <section aria-labelledby="jelajahi-kategori" class="px-4 md:px-6 space-y-10 py-12 lg:py-24 fade-in-up">
        <header class="text-center" id="jelajahi-kategori">
            <h2 class="text-xl font-semibold text-gray-700 tracking-wide uppercase">
                Jelajahi Kategori
            </h2>
        </header>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse ($this->categories as $index => $category)
                <a
                    class="p-4 rounded-xl bg-white shadow hover:shadow-md text-center transition-all duration-300
                {{ !$showAllCategories && $index >= 8 ? 'hidden' : '' }}">
                    {{ $category->name }}
                </a>
            @empty
                <p>tidak ada kategori</p>
            @endforelse
        </div>

        @if ($this->categories->count() > 8)
            <div class="text-center">
                <button wire:click="$toggle('showAllCategories')"
                    class="px-6 py-2 rounded-full border border-gray-300 text-sm text-gray-600 hover:bg-gray-100 transition cursor-pointer">
                    {{ $showAllCategories ? 'Sembunyikan' : 'Selengkapnya' }}
                </button>
            </div>
        @endif
    </section>
</div>
