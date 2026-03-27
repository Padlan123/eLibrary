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

    #[Computed]
    public function categories()
    {
        return Category::all();
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
