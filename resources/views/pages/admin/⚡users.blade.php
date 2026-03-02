<?php

use Livewire\Component;

new class extends Component {
    public function render()
    {
        return $this->view()->layout('layouts.admin', ['title' => 'Kelola Pengguna']);
    }
};
?>

<div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div
            class="bg-linear-to-r from-blue-500 to-indigo-600 text-white p-6 md:p-8 rounded-2xl shadow-lg hover:scale-[1.02] transition">
            <p class="opacity-80 text-sm md:text-base">Total Pengguna</p>
            <h3 class="text-3xl md:text-4xl font-bold mt-2">-</h3>
        </div>

        <div
            class="bg-linear-to-r from-cyan-400 to-cyan-600 text-white p-6 md:p-8 rounded-2xl shadow-lg hover:scale-[1.02] transition">
            <p class="opacity-80 text-sm md:text-base">
                Total pengguna berlangganan
            </p>
            <h3 class="text-3xl md:text-4xl font-bold mt-2">-</h3>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6 overflow-x-auto">
        <h3 class="text-lg md:text-xl font-bold mb-6">Tabel Pengguna</h3>

        <table class="w-full min-w-175 border-separate border-spacing-y-3">
            <thead>
                <tr class="text-gray-500 text-left text-sm">
                    <th>Username</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Paket langganan</th>
                    <th>Tanggal langganan</th>
                </tr>
            </thead>

            <tbody>
                <tr class="bg-gray-50 rounded-lg shadow-sm">
                    <td class="p-4">User kece</td>
                    <td class="p-4">user@email.com</td>
                    <td class="p-4">
                        <span class="bg-blue-400 px-3 py-1 rounded-full text-sm">
                            Berlangganan
                        </span>
                    </td>
                    <td class="p-4">
                        <span class="bg-amber-400 px-3 py-1 rounded-full text-sm">
                            Paket pro
                        </span>
                    </td>
                    <td class="p-4">2026-07-12</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
