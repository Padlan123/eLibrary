<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\User;

new class extends Component {
    #[Computed]
    public function subscribes()
    {
        return User::whereHas('subscribes')->with('subscribes')->get();
    }

    #[Computed]
    public function users()
    {
        return User::latest()->get();
    }

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
            <h3 class="text-3xl md:text-4xl font-bold mt-2">{{ $this->users->count() }}</h3>
        </div>

        <div
            class="bg-linear-to-r from-cyan-400 to-cyan-600 text-white p-6 md:p-8 rounded-2xl shadow-lg hover:scale-[1.02] transition">
            <p class="opacity-80 text-sm md:text-base">
                Total pengguna berlangganan
            </p>
            <h3 class="text-3xl md:text-4xl font-bold mt-2">{{ $this->subscribes->count() }}</h3>
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
                @forelse ($this->users as $user)
                    <tr class="bg-gray-50 rounded-lg shadow-sm">
                        <td class="p-4">{{ $user->username }}</td>
                        <td class="p-4">{{ $user->email }}</td>
                        @if ($user->subscribe)
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
                        @else
                            <td class="p-4">
                                <span class="bg-blue-400 px-3 py-1 rounded-full text-sm">
                                    -
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="bg-amber-400 px-3 py-1 rounded-full text-sm">
                                    -
                                </span>
                            </td>
                            <td class="p-4">-</td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-3 text-center text-gray-500">tidak ada users
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
