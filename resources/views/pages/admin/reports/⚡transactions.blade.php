<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\SubscriptionTransaction;
use Livewire\WithPagination;

new class extends Component {
    public $from = ' ';
    public $to = ' ';

    public function mount()
    {
        $this->from = now()->startOfMonth()->format('Y-m-d');
        $this->to = now()->format('Y-m-d');
    }

    public function getUrlDownload()
    {
        return route('admin.report.transactions.download', [
            'from' => $this->from,
            'to' => $this->to,
        ]);
    }
    #[Computed]
    public function transactions()
    {
        $transactions = SubscriptionTransaction::whereBetween('paid_date', [$this->from, $this->to])
            ->orderBy('paid_date', 'desc')
            ->paginate(15);

        return $transactions;
    }
};
?>

<div>
    <div class="flex gap-4 px-6 py-4 w-full">
        <input wire:model.live.debounce="from" id="from" type="date"
            class="w-full inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-100 transition-colors">

        <label class="inline-flex items-center">sampai</label>

        <input wire:model.live="to" id="to" type="date"
            class="w-full inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-100 transition-colors">

        <a href="{{ $this->getUrlDownload() }}"
            class="w-full px-6  inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white text-semibold rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" />
            </svg>Download</a>
    </div>

    <h3 class="my-4 font-medium leading-tight text-slate-800">Preview PDF</h3>

    <table class="w-full text-sm border-collapse">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left border">#</th>
                <th class="px-4 py-2 text-left border">Tanggal</th>
                <th class="px-4 py-2 text-left border">Invoice</th>
                <th class="px-4 py-2 text-left border">Pelanggan</th>
                <th class="px-4 py-2 text-left border">Harga</th>
                <th class="px-4 py-2 text-left border">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($this->transactions as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2 border">{{ $item->paid_date->format('d/m/Y') }}</td>
                    <td class="px-4 py-2 border">{{ $item->invoice_number }}</td>
                    <td class="px-4 py-2 border">{{ $item->member->username }}</td>
                    <td class="px-4 py-2 border">Rp {{ number_format($item->package->price, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 border">{{ ucfirst($item->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
