<?php

use Livewire\Component;

new class extends Component {
    public function render()
    {
        return $this->view()->layout('layouts.admin', ['title' => 'Dashboard']);
    }
};
?>

<div>
    <!-- CARDS -->
    <div class="grid md:grid-cols-2 gap-6 mb-8">
        <div
            class="bg-linear-to-r w-full from-indigo-500 to-indigo-600 text-white px-6 py-4 rounded-2xl shadow-lg hover:scale-[1.02] transition">
            <p class="opacity-80 lg:text-base">Total Pengguna</p>
            <h3 class="text-2xl font-bold mt-2 lg:text-3xl">1,245</h3>
        </div>

        <div
            class="bg-linear-to-r w-full from-green-400 to-emerald-600 text-white px-6 py-4 rounded-2xl shadow-lg hover:scale-[1.02] transition">
            <p class="opacity-80 lg:text-base">Total Profit Langganan</p>
            <h3 class="text-2xl font-bold mt-2 lg:text-3xl">Rp 12.500.000</h3>
        </div>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow-lg p-6 overflow-x-auto">
        <h3 class="text-xl font-bold mb-6">Konfirmasi Berlangganan</h3>

        <table class="w-full border-separate border-spacing-y-3 min-w-150">
            <thead>
                <tr class="text-gray-500 text-left">
                    <th>Email</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <tr class="bg-gray-50 rounded-lg shadow-sm">
                    <td class="p-4">user@email.com</td>
                    <td class="p-4">05 Feb 2026</td>

                    <td class="p-4">
                        <span class="bg-yellow-100 text-yellow-600 px-3 py-1 rounded-full text-sm">
                            Pending
                        </span>
                    </td>

                    <td class="p-4 space-x-2">
                        <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition">
                            Approve
                        </button>

                        <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition">
                            Reject
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
