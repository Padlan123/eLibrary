<?php

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Computed;

new class extends Component {
    #[Computed]
    public function subscribes()
    {
        return User::whereHas('subscribes', function ($query) {
            $query->active();
        })
            ->with([
                'subscribes',
                function ($query) {
                    $query->active();
                },
            ])
            ->latest()
            ->get();
    }

    #[Computed]
    public function users()
    {
        return User::role('anggota')->latest()->get();
    }

    public function render()
    {
        return $this->view()->layout('layouts.admin', ['title' => 'Dashboard']);
    }
};
?>

<div>

    <!-- CARDS -->
    <div class="grid md:grid-cols-2 gap-6 mb-8">
        <div class="bg-linear-to-r w-full from-indigo-500 to-indigo-600 text-white px-6 py-4 rounded-2xl shadow-lg ">
            <p class="opacity-80 lg:text-base">Total Pengguna</p>
            <h3 class="text-2xl font-bold mt-2 lg:text-3xl">1,245</h3>
        </div>

        <div class="bg-linear-to-r w-full from-green-400 to-emerald-600 text-white px-6 py-4 rounded-2xl shadow-lg ">
            <p class="opacity-80 lg:text-base">Total Profit Langganan</p>
            <h3 class="text-2xl font-bold mt-2 lg:text-3xl">Rp 12.500.000</h3>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-linear-to-r from-blue-500 to-indigo-600 text-white p-6 md:p-8 rounded-2xl shadow-lg ">
            <p class="opacity-80 text-sm md:text-base">Total Pengguna</p>
            <h3 class="text-3xl md:text-4xl font-bold mt-2">{{ $this->users->count() }}</h3>
        </div>

        <div class="bg-linear-to-r from-cyan-400 to-cyan-600 text-white p-6 md:p-8 rounded-2xl shadow-lg ">
            <p class="opacity-80 text-sm md:text-base">
                Total pengguna berlangganan
            </p>
            <h3 class="text-3xl md:text-4xl font-bold mt-2">{{ $this->subscribes->count() }}</h3>
        </div>
    </div>
</div>
