<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\User;

new class extends Component {
    #[Computed]
    public function subscribes()
    {
        return User::whereHas('subscribes', function ($query) {
            $query->status->active();
        })
            ->with([
                'subscribes',
                function ($query) {
                    $query->status->active();
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
        return $this->view()->layout('layouts.admin', ['title' => 'Kelola Pengguna']);
    }
};
?>

<div>
    <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6 overflow-x-auto">
        <h3 class="text-lg md:text-xl font-bold mb-6">Tabel Pengguna</h3>

        <table class="w-full min-w-175 border-separate border-spacing-y-3">
            <thead>
                <tr class="text-gray-500 text-left text-sm">
                    <th>Username</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Tanggal berakhir</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($this->users as $user)
                    <tr class="bg-gray-50 rounded-lg shadow-sm">
                        <td class="p-4">{{ $user->username }}</td>
                        <td class="p-4">{{ $user->email }}</td>
                        @if ($user->subscribes->firstWhere('status', 'active'))
                            <td class="p-4">
                                <span class="bg-blue-400 px-3 py-1 rounded-full text-sm">
                                    premium
                                </span>
                            </td>
                            <td class="p-4">
                                {{ $user->subscribes->first()?->end_date?->format('d M Y') ?? '-' }}
                            </td>
                        @else
                            <td class="p-4">
                                <span class="bg-blue-400 px-3 py-1 rounded-full text-sm">
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
