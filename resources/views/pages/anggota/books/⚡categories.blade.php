<?php

use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Computed;

new class extends Component {
    #[Computed]
    public function categories()
    {
        return Category::latest()->get();
    }
};
?>

<div>
    <section aria-labelledby="jelajahi-kategori" class="max-w-7xl mx-auto px-4 md:px-6 space-y-10 py-12 lg:py-24">
        <header class="text-center" id="jelajahi-kategori">
            <h2 class="text-xl font-semibold text-gray-700 tracking-wide uppercase">
                Jelajahi Kategori
            </h2>
        </header>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse ($this->categories as $category)
                <a class="p-4 rounded-xl bg-white shadow hover:shadow-md text-center">
                    {{ $category->name }}
                </a>
            @empty
                <p>tidak ada kategori</p>
            @endforelse
        </div>
    </section>
</div>
