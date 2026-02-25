<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\SubscriptionTransaction;

new class extends Component {
    public bool $show = false;
    public string $imageUrl = '';

    public function showPaymentProof(string $imageUrl)
    {
        $this->imageUrl = $imageUrl;
        $this->show = true;
    }

    public function close()
    {
        $this->show = false;
        $this->imageUrl = '';
    }

    public function approveTransaction($id)
    {
        $transaksi = SubscriptionTransaction::find($id);
        if ($transaksi) {
            $transaksi->status = 'Disetujui';
            $transaksi->save();
        }
    }

    public function rejectTransaction($id)
    {
        $transaksi = SubscriptionTransaction::find($id);
        if ($transaksi) {
            $transaksi->status = 'Ditolak';
            $transaksi->save();
        }
    }

    #[computed]
    public function transactions()
    {
        return SubscriptionTransaction::latest()->get();
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

        <table class="w-full border-separate border-spacing-y-3 min-w-250 lg:min-w-0">
            <thead>
                <tr class="text-gray-500 text-left">
                    <th>Nama</th>
                    <th>Nama Paket</th>
                    <th>Nama Pengirim</th>
                    <th>Nomor Pengirim</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($this->transactions as $transaction)
                    <tr class="bg-gray-50 rounded-lg shadow-sm">
                        <td class="p-1">{{ $transaction->member->username }}</td>
                        <td class="p-1">{{ $transaction->package->name }}</td>
                        <td class="p-1">{{ $transaction->name }}</td>
                        <td class="p-1">{{ $transaction->number }}</td>
                        <td class="p-1">{{ $transaction->paid_date->format('d M Y') }}</td>
                        <td class="p-1">
                            <button
                                wire:click="showPaymentProof('{{ asset('storage/' . $transaction->payment_proof) }}')"
                                class="hover:opacity-75 transition bg-blue-500 p-1.5 rounded-md text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-6">
                                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                    <path fill-rule="evenodd"
                                        d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </td>

                        <td class="p-1">
                            <span class="bg-yellow-100 text-yellow-600 px-3 py-1 rounded-full text-sm">
                                {{ $transaction->status }}
                            </span>
                        </td>

                        <td class="p-1 space-x-2">
                            <button wire:click="approveTransaction({{ $transaction->id }})"
                                class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                                setujui
                            </button>

                            <button wire:click="rejectTransaction({{ $transaction->id }})"
                                class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                                tolak
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-500 py-4">
                            Belum ada transaksi berlangganan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{-- resources/views/livewire/bukti-pembayaran-modal.blade.php --}}
    @if ($show)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60" wire:click.self="close">
            <div class="relative bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4">
                {{-- Header --}}
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Bukti Pembayaran</h3>
                    <button wire:click="close" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Image --}}
                <div class="p-4">
                    <img src="{{ $imageUrl }}" alt="Bukti Pembayaran"
                        class="w-full h-auto max-h-[70vh] object-contain rounded-lg">
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-2 p-4 border-t">
                    <a href="{{ $imageUrl }}" target="_blank"
                        class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Buka di Tab Baru
                    </a>
                    <button wire:click="close"
                        class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
