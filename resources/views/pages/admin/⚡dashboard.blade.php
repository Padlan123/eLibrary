<?php

use Livewire\Component;
use App\Traits\WithFlashMessages;

new class extends Component {
    use WithFlashMessages;

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



</div>
