<?php

use Livewire\Component;
use App\Traits\WithFlashMessages;
use Livewire\Attributes\Computed;
use App\Models\SubscriptionTransaction;
use App\Models\MemberSubscription;
use App\Models\Package;
use Carbon\Carbon;

new class extends Component {
    use WithFlashMessages;

    public $packageId = [];
    public $status = ['pending', 'completed', 'rejected'];
    public $search = '';

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
            $this->flashMessage('gagal', 'invoice sudah terdaftar', 'admin.subscriptions');
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
        $this->flashMessage('sukses', 'berlangganan disetujui', 'admin.subscriptions');
    }

    public function rejectTransaction($id)
    {
        $transaction = SubscriptionTransaction::find($id);
        if (!$transaction || $transaction->status == 'completed') {
            $this->flashMessage('gagal', 'Transaksi sudah disetujui', 'admin.subscriptions');
            return;
        }

        $transaction->status = 'rejected';
        $transaction->save();
    }

    #[Computed]
    public function packages()
    {
        return Package::all();
    }

    #[Computed]
    public function selectedPackages()
    {
        return Package::whereIn('id', $this->packageId)->get();
    }

    #[Computed]
    public function selectedStatuses()
    {
        return SubscriptionTransaction::whereIn('status', $this->status)->get();
    }

    #[computed]
    public function transactions()
    {
        return SubscriptionTransaction::with('package', 'member')
            ->when($this->packageId, function ($query) {
                $query->whereIn('package_id', $this->packageId);
            })
            ->when($this->status, function ($query) {
                $query->whereIn('status', $this->status);
            })
            ->when($this->search, function ($query) {
                $query->whereHas('member', function ($q) {
                    $q->where('username', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->get();
    }

    public function render()
    {
        return $this->view()->layout('layouts.admin', ['title' => 'Kelola Langganan']);
    }
};
?>

<div>

    <div class="max-w-7xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-100 mb-8">

        <!-- ── Page Header ─────────────────────────────────── -->
        <div
            class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between px-5 pt-5 pb-3 border-b border-slate-100">
            <div>
                <h2 class="text-lg font-bold text-slate-800 tracking-tight">Kelola Berlangganan</h2>
                <p class="text-xs text-slate-400 mt-0.5">Pantau & kelola semua transaksi berlangganan pengguna</p>
            </div>
            <span
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full self-start sm:self-auto">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                    <path fill-rule="evenodd"
                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                        clip-rule="evenodd" />
                </svg>
                {{ $this->transactions->count() }} transaksi
            </span>
        </div>

        @if (session('gagal'))
            <div id="toast-danger"
                class="flex items-center w-full max-w-sm p-4 text-body bg-neutral-blue-soft rounded-base shadow-xs border border-default"
                role="alert">
                <div
                    class="inline-flex items-center justify-center shrink-0 w-7 h-7 text-fg-danger bg-danger-soft rounded">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
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
                class="flex items-center w-full max-w-sm p-4 text-body bg-neutral-blue-soft rounded-base shadow-xs border border-default"
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

        <!-- ── Toolbar ──────────────────────────────────────── -->
        <div
            class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-5 py-4 bg-slate-50/60 border-b border-slate-100">

            <!-- Search -->
            <div class="relative w-full sm:max-w-xs">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                </div>
                <input wire:model.live.debounce="search" type="text"
                    class="block w-full pl-9 pr-4 py-2.5 text-sm text-slate-700 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder:text-slate-400"
                    placeholder="Cari pengguna..." />
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2 flex-wrap">
                <ul class="max-w-md space-y-1 text-body list-disc list-inside">
                    <li class="text-xs list-none">
                        filter :
                    </li>
                </ul>
                <ul class="max-w-md space-y-1 text-body list-disc list-inside">
                    <li class="text-xs">
                        @foreach ($this->selectedPackages as $package)
                            {{ $package->name }} @if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </li>
                </ul>
                <ul class="max-w-md space-y-1 text-body list-disc list-inside">
                    <li class="text-xs">
                        @foreach ($this->selectedStatuses as $status)
                            {{ $status->status }} @if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </li>
                </ul>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button"
                        class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-100 transition-colors">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path d="M3 4h18M7 8h10M11 12h2" />
                        </svg>
                        Paket
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition
                        class="absolute z-20 w-52 p-4 bg-white rounded-xl shadow-lg border border-slate-100">
                        <p class="mb-3 text-xs font-bold text-slate-400 uppercase tracking-widest">Filter Paket</p>
                        <ul class="space-y-2.5 text-sm">
                            @foreach ($this->packages as $package)
                                <li wire:key="{{ $package->id }}" class="flex items-center gap-2.5">
                                    <input wire:model.live="packageId" id="{{ $package->id }}" type="checkbox"
                                        checked value="{{ $package->id }}" class="w-4 h-4 rounded " />
                                    <label for="{{ $package->id }}"
                                        class="text-sm font-medium text-slate-700 cursor-pointer">{{ $package->name }}</label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Filter Dropdown (Flowbite) -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button"
                        class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-100 transition-colors">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path d="M3 4h18M7 8h10M11 12h2" />
                        </svg>
                        Status
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition
                        class="absolute z-20 w-40 p-4 bg-white rounded-xl shadow-lg border border-slate-100">
                        <p class="mb-3 text-xs font-bold text-slate-400 uppercase tracking-widest">Filter Status
                        </p>
                        <ul class="space-y-2.5 text-sm">
                            <li class="flex items-center gap-2.5">
                                <input wire:model.live="status" id="f-pending" type="checkbox" checked
                                    class="w-4 h-4 rounded text-blue-600 border-slate-300 focus:ring-blue-500 cursor-pointer"
                                    value="pending" />
                                <label for="f-pending"
                                    class="text-sm font-medium text-slate-700 cursor-pointer">Pending</label>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <input wire:model.live="status" id="f-approved" type="checkbox" checked
                                    class="w-4 h-4 rounded text-green-600 border-slate-300 focus:ring-green-500 cursor-pointer"
                                    value="completed" />
                                <label for="f-approved"
                                    class="text-sm font-medium text-slate-700 cursor-pointer">Disetujui</label>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <input wire:model.live="status" id="f-rejected" type="checkbox" checked
                                    class="w-4 h-4 rounded text-red-600 border-slate-300 focus:ring-red-500 cursor-pointer"
                                    value="rejected" />
                                <label for="f-rejected"
                                    class="text-sm font-medium text-slate-700 cursor-pointer">Ditolak</label>
                            </li>
                        </ul>
                    </div>
                </div>




            </div>
        </div>

        <!-- ── DESKTOP TABLE (lg+) ──────────────────────────── -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-sm text-left" id="desktopTable">
                <thead
                    class="text-xs text-slate-400 uppercase tracking-widest bg-slate-50/80 border-b border-slate-100">
                    <tr>
                        <th scope="col" class="px-5 py-3.5 font-semibold">Pengguna</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold">Paket</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold">Nama Pengirim</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold">No. Pengirim</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold">Tanggal</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold text-center">Bukti</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold text-center">Status</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50" id="tableBody">

                    @forelse ($this->transactions as $transaction)
                        <tr wire:key="{{ $transaction->id }}" class="table-row-hover bg-white">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div
                                        class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs shrink-0">
                                        -</div>
                                    <span
                                        class="font-semibold text-slate-800">{{ $transaction->member->username }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span
                                    class="inline-block bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-md">{{ $transaction->package->name }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $transaction->name }}</td>
                            <td class="px-5 py-3.5"><span
                                    class="num-pill text-slate-500">{{ $transaction->number }}</span></td>
                            <td class="px-5 py-3.5 text-slate-500 text-xs">
                                {{ $transaction->paid_date->format('d M Y') }}</td>
                            <td class="px-5 py-3.5 text-center">
                                <button
                                    wire:click="showPaymentProof('{{ asset('storage/' . $transaction->payment_proof) }}')"
                                    type="button"
                                    class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </button>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span
                                    class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-amber-100">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>{{ $transaction->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="approveTransaction({{ $transaction->id }})" type="button"
                                        class="inline-flex items-center gap-1 text-xs font-semibold text-green-700 bg-green-50 hover:bg-green-100 border border-green-200 px-3 py-1.5 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2.5">
                                            <path d="m5 13 4 4L19 7" />
                                        </svg>
                                        Setujui
                                    </button>
                                    <button wire:click="rejectTransaction({{ $transaction->id }})" type="button"
                                        class="inline-flex items-center gap-1 text-xs font-semibold text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 px-3 py-1.5 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2.5">
                                            <path d="M6 18 18 6M6 6l12 12" />
                                        </svg>
                                        Tolak
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-16 text-slate-400">
                                <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.5">
                                    <path
                                        d="M9 17v-2m3 2v-4m3 4v-6M5 20h14a2 2 0 0 0 2-2V8l-5-5H5a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2z" />
                                </svg>
                                <p class="font-medium text-sm">Tidak ada transaksi ditemukan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- ── TABLET (sm–lg): Scrollable compact table ─────── -->
        <div class="hidden sm:block lg:hidden overflow-x-auto">
            <p class="text-xs text-slate-400 px-4 pt-3 pb-1 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path
                        d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2" />
                </svg>
                Geser ke kanan untuk lihat semua kolom
            </p>
            <div style="min-width: 600px;">
                <table class="w-full text-sm text-left">
                    <thead
                        class="text-xs text-slate-400 uppercase tracking-widest bg-slate-50/80 border-b border-slate-100">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Pengguna</th>
                            <th class="px-4 py-3 font-semibold">Paket</th>
                            <th class="px-4 py-3 font-semibold">Nama Pengirim</th>
                            <th class="px-4 py-3 font-semibold">No. Pengirim</th>
                            <th class="px-4 py-3 font-semibold">Tanggal</th>
                            <th class="px-4 py-3 font-semibold text-center">Bukti</th>
                            <th class="px-4 py-3 font-semibold text-center">Status</th>
                            <th class="px-4 py-3 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($this->transactions as $transaction)
                            <tr wire:key="{{ $transaction->id }}"
                                class="bg-white hover:bg-blue-50/30 transition-colors">
                                <td class="px-4 py-3 font-semibold text-slate-800">
                                    {{ $transaction->member->username }}
                                </td>
                                <td class="px-4 py-3"><span
                                        class="bg-blue-50 text-blue-700 text-xs font-semibold px-2 py-0.5 rounded">{{ $transaction->package->name }}</span>
                                </td>
                                <td class="px-4 py-3 num-pill text-slate-500">{{ $transaction->name }}</td>
                                <td class="px-4 py-3 num-pill text-slate-500">{{ $transaction->number }}</td>
                                <td class="px-4 py-3 text-xs text-slate-500">
                                    {{ $transaction->paid_date->format('d M Y') }}</td>
                                <td class="px-4 py-3 text-center"><button
                                        wire:click="showPaymentProof('{{ asset('storage/' . $transaction->payment_proof) }}')"
                                        class="inline-flex items-center justify-center w-7 h-7 bg-blue-600 text-white rounded-lg hover:bg-blue-700"><svg
                                            class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg></button></td>
                                <td class="px-4 py-3 text-center"><span
                                        class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full"><span
                                            class="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse"></span>{{ $transaction->status }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-1 justify-center"><button
                                            wire:click="approveTransaction({{ $transaction->id }})"
                                            class="text-xs font-semibold text-green-700 bg-green-50 border border-green-200 px-2.5 py-1 rounded-lg hover:bg-green-100">✓</button><button
                                            wire:click="rejectTransaction({{ $transaction->id }})"
                                            class="text-xs font-semibold text-red-700 bg-red-50 border border-red-200 px-2.5 py-1 rounded-lg hover:bg-red-100">✕</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-16 text-slate-400">
                                    <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path
                                            d="M9 17v-2m3 2v-4m3 4v-6M5 20h14a2 2 0 0 0 2-2V8l-5-5H5a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2z" />
                                    </svg>
                                    <p class="font-medium text-sm">Tidak ada transaksi ditemukan</p>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        <!-- ── MOBILE CARDS (<sm) ───────────────────────────── -->
        <div class="md:hidden px-3 py-3 space-y-3">

            @forelse ($this->transactions as $transaction)
                <div class="card-animate bg-white border border-slate-100 rounded-xl shadow-xs overflow-hidden"
                    data-status="pending" data-name="budi_santoso paket pro budi santoso">
                    <div class="flex items-center justify-between px-4 pt-4 pb-3 border-b border-slate-50">
                        <div class="flex items-center gap-2.5">
                            <div
                                class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm shrink-0">
                                -</div>
                            <div>
                                <p class="font-bold text-slate-800 text-sm leading-tight">
                                    {{ $transaction->member->username }}</p>
                                <span
                                    class="text-xs bg-blue-50 text-blue-700 font-semibold px-1.5 py-0.5 rounded mt-0.5 inline-block">{{ $transaction->package->name }}</span>
                            </div>
                        </div>
                        <span
                            class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-amber-100">
                            <span
                                class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>{{ $transaction->status }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-2.5 px-4 py-3">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Pengirim</p>
                            <p class="text-sm text-slate-700 font-medium">{{ $transaction->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Nomor</p>
                            <p class="num-pill text-slate-600">{{ $transaction->number }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Tanggal</p>
                            <p class="text-sm text-slate-700">{{ $transaction->paid_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Bukti</p>
                            <button
                                wire:click="showPaymentProof('{{ asset('storage/' . $transaction->payment_proof) }}')"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 bg-blue-50 border border-blue-100 px-2.5 py-1 rounded-lg hover:bg-blue-100 transition-colors mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                Lihat
                            </button>
                        </div>
                    </div>
                    <div class="flex gap-2 px-4 pb-4">
                        <button wire:click="approveTransaction({{ $transaction->id }})"
                            class="flex-1 flex items-center justify-center gap-1.5 text-sm font-semibold text-green-700 bg-green-50 hover:bg-green-100 border border-green-200 py-2 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path d="m5 13 4 4L19 7" />
                            </svg>
                            Setujui
                        </button>
                        <button wire:click="rejectTransaction({{ $transaction->id }})"
                            class="flex-1 flex items-center justify-center gap-1.5 text-sm font-semibold text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 py-2 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path d="M6 18 18 6M6 6l12 12" />
                            </svg>
                            Tolak
                        </button>
                    </div>
                </div>

            @empty
                <div class="text-center py-14 text-slate-400">
                    <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path
                            d="M9 17v-2m3 2v-4m3 4v-6M5 20h14a2 2 0 0 0 2-2V8l-5-5H5a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2z" />
                    </svg>
                    <p class="font-medium text-sm">Tidak ada transaksi ditemukan</p>
                </div>
            @endforelse
            <div
                class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-4 border-t border-slate-100 bg-slate-50/60">
                <p class="text-xs text-slate-400">Menampilkan <span class="font-semibold text-slate-600">1–5</span>
                    dari
                    <span class="font-semibold text-slate-600">5</span> transaksi
                </p>
                <nav class="flex items-center gap-1">
                    <button
                        class="px-3 py-1.5 text-xs font-medium text-slate-500 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors disabled:opacity-40"
                        disabled>← Sebelumnya</button>
                    <button
                        class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 border border-blue-600 rounded-lg">1</button>
                    <button
                        class="px-3 py-1.5 text-xs font-medium text-slate-500 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors disabled:opacity-40"
                        disabled>Berikutnya →</button>
                </nav>
            </div>
        </div>
        @if ($show)
            <div class="fixed inset-0 z-50 flex items-center justify-center min-h-screen bg-black/70"
                wire:click.self="close">
                <div class="relative bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4">

                    <div class="flex items-center justify-between p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Bukti Pembayaran</h3>
                        <button wire:click="close" class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>


                    <div class="p-4">
                        <img src="{{ $imageUrl }}" alt="Bukti Pembayaran"
                            class="w-full h-auto max-h-[70vh] object-contain rounded-lg">
                    </div>


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
    @livewire('pages::admin.reports.transactions')

</div>
