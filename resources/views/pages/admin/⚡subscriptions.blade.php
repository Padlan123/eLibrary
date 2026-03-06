<?php

use Livewire\Component;
use App\Traits\WithFlashMessages;
use Livewire\Attributes\Computed;
use App\Models\SubscriptionTransaction;
use App\Models\MemberSubscription;
use Carbon\Carbon;

new class extends Component {
    use WithFlashMessages;

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
        $transaction = SubscriptionTransaction::find($id);
        if (!$transaction) {
            return;
        }
        $transaction->load('package');

        if ($transaction->status == 'completed' || $transaction->status == 'rejected') {
            $this->flashMessage('gagal', 'invoice sudah terdaftar', 'admin.dashboard');
            return;
        }
        $existMemberSubscription = MemberSubscription::where('member_id', $transaction->member_id)->where('status', 'active')->where('end_date', '>', now())->latest('end_date')->first();

        if ($existMemberSubscription) {
            $existMemberSubscription->end_date = Carbon::parse($existMemberSubscription->end_date)->addDays($transaction->package->duration_days);
            $existMemberSubscription->save();
        } else {
            MemberSubscription::create([
                'member_id' => $transaction->member_id,
                'start_date' => now(),
                'end_date' => now()->addDays($transaction->duration_days),
                'status' => 'active',
            ]);
        }
        $transaction->status = 'completed';
        $transaction->save();
        $this->flashMessage('sukses', 'berlangganan disetujui', 'admin.dashboard');
    }

    public function rejectTransaction($id)
    {
        $transaction = SubscriptionTransaction::find($id);
        if (!$transaction || $transaction->status == 'completed') {
            $this->flashMessage('gagal', 'Transaksi sudah disetujui', 'admin.dashboard');
            return;
        }

        $transaction->status = 'rejected';
        $transaction->save();
    }

    #[computed]
    public function transactions()
    {
        return SubscriptionTransaction::latest()->get();
    }

    public function render()
    {
        return $this->view()->layout('layouts.admin', ['title' => 'Kelola Langganan']);
    }
};
?>

<div>
    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow-lg p-6 overflow-x-auto">
        <h3 class="text-xl font-bold mb-6">Konfirmasi Berlangganan</h3>
        @if (session('gagal'))
            <div id="toast-danger"
                class="flex items-center w-full max-w-sm p-4 text-body bg-neutral-primary-soft rounded-base shadow-xs border border-default"
                role="alert">
                <div
                    class="inline-flex items-center justify-center shrink-0 w-7 h-7 text-fg-danger bg-danger-soft rounded">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18 17.94 6M18 18 6.06 6" />
                    </svg>
                    <span class="sr-only">Error icon</span>
                </div>
                <div class="ms-3 text-sm font-normal">{{ session('gagal') }}</div>
                <button type="button"
                    class="ms-auto flex items-center justify-center text-body hover:text-heading bg-transparent box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded text-sm h-8 w-8 focus:outline-none"
                    data-dismiss-target="#toast-danger" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18 17.94 6M18 18 6.06 6" />
                    </svg>
                </button>
            </div>
        @endif

        @if (session('sukses'))
            <div id="toast-success"
                class="flex items-center w-full max-w-sm p-4 text-body bg-neutral-primary-soft rounded-base shadow-xs border border-default"
                role="alert">
                <div
                    class="inline-flex items-center justify-center shrink-0 w-7 h-7 text-fg-success bg-success-soft rounded">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 11.917 9.724 16.5 19 7.5" />
                    </svg>
                    <span class="sr-only">Check icon</span>
                </div>
                <div class="ms-3 text-sm font-normal">{{ session('sukses') }}</div>
                <button type="button"
                    class="ms-auto flex items-center justify-center text-body hover:text-heading bg-transparent box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded text-sm h-8 w-8 focus:outline-none"
                    data-dismiss-target="#toast-success" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18 17.94 6M18 18 6.06 6" />
                    </svg>
                </button>
            </div>
        @endif
        <table class="w-full border-separate border-spacing-y-3 min-w-250 lg:min-w-0">
            <thead>
                <tr class="text-gray-500 text-left">
                    <th class="text-center">Nama Pengguna</th>
                    <th class="text-center">Nama Paket</th>
                    <th class="text-center">Nama Pengirim</th>
                    <th class="text-center">Nomor Pengirim</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Bukti</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
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
                            @if ($transaction->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-600 px-3 py-1 rounded-full text-sm">
                                    Pending
                                </span>
                            @elseif ($transaction->status == 'rejected')
                                <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-sm">
                                    Ditolak
                                </span>
                            @elseif ($transaction->status == 'completed')
                                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm">
                                    Disetujui
                                </span>
                            @endif
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
