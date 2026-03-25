<?php

use Livewire\Component;
use App\Models\SubscriptionTransaction;
use Livewire\Attributes\Computed;

new class extends Component {
    #[Computed]
    public function transactionHistories()
    {
        return SubscriptionTransaction::where('member_id', auth()->id())
            ->latest()
            ->get();
    }

    public function render()
    {
        return $this->view()->title('Home')->layout('layouts.anggota');
    }
};
?>

<div>
    <div class="px-4 pt-6 pb-8 mt-24 flex flex-col gap-3 w-1/2 mx-auto shadow">
        <h1 class="text-2xl font-semibold text-slate-600 leading-5">Riwayat Transaksi</h1>
        @forelse ($this->transactionHistories as $history)
            <a href="{{ route('anggota.invoice', $history->id) }}">
                <article class="flex mt-3 gap-3 hover:bg-gray-50 transition cursor-pointer">
                    <div class="text-green-500">📩</div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">
                            {{ $history->invoice_number }}
                        </p>
                        <p class="text-xs text-gray-500">
                            Klik untuk melihat invoice dan pembayaran
                        </p>
                    </div>
                    <span class="text-xs text-gray-400">{{ $history->created_at->diffForHumans() }}</span>
                </article>
            </a>
        @empty
        @endforelse
    </div>

</div>
