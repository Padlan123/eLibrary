<?php

use Livewire\Attributes\Computed;
use Illuminate\Support\Str;
use Livewire\Component;
use App\Models\Package;
use Livewire\WithFileUploads;
use App\Traits\WithTransactionValidation;
use App\Traits\WithFlashMessages;
use App\Models\SubscriptionTransaction;

new class extends Component {
    use WithFileUploads, WithTransactionValidation, WithFlashMessages;

    public $is_uploading = false;
    public $packageSelectedId = '';
    public $name = '';
    public $number = '';
    public $payment_proof;

    public ?string $uploaded_path = null;

    public function updatedPaymentProof(): void
    {
        $this->validateOnly('payment_proof');

        $this->uploaded_path = $this->payment_proof?->getRealPath();
        $this->is_uploading = false;
    }

    #[Computed]
    public function pilihanPaket()
    {
        if (!$this->packageSelectedId) {
            return null;
        }
        return Package::find($this->packageSelectedId);
    }

    #[computed]
    public function paketLangganan()
    {
        return Package::all();
    }

    private function generateInvoiceNumber(): string
    {
        $words = explode(' ', auth()->user()->username);
        $initial = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));

        do {
            $invoice = 'INV-' . now()->format('Ymd') . '-' . $initial . strtoupper(Str::random(6));
        } while (SubscriptionTransaction::where('invoice_number', $invoice)->exists());

        return $invoice;
    }

    public function submit()
    {
        if ($this->is_uploading) {
            return;
        }

        if (!$this->payment_proof || !$this->payment_proof->isValid()) {
            $this->addError('payment_proof', 'File tidak valid, silakan upload ulang.');
            return;
        }

        $this->validateTransaction();

        $path = $this->payment_proof->store('bukti', 'public');

        $packageSelected = Package::find($this->packageSelectedId);

        SubscriptionTransaction::create([
            'member_id' => auth()->id(),
            'package_id' => $this->packageSelectedId,
            'invoice_number' => $this->generateInvoiceNumber(),
            'name' => $this->name,
            'number' => $this->number,
            'amount' => $packageSelected->price,
            'payment_proof' => $path,
            'paid_date' => now(),
        ]);

        $this->flashMessage('sukses', 'Berhasil mengajukan berlangganan!', 'anggota.subscriptions');
    }

    public function render()
    {
        return $this->view()->title('Form Berlangganan')->layout('layouts.anggota');
    }
};
?>

<div>
    <section class="bg-linear-to-br from-gray-50 to-gray-100 min-h-screen pt-16 lg:pt-20">
        <div class="py-8 px-4 sm:px-6 md:px-12 flex flex-col lg:flex-row gap-8 max-w-7xl mx-auto">

            {{-- ===== KOLOM PAKET ===== --}}
            <div class="hidden lg:flex flex-col gap-4 w-full lg:w-[55%]">

                {{-- Card Paket Dasar --}}
                <figure
                    class="p-5 sm:p-7 rounded-2xl bg-white border border-gray-100
                               shadow-sm hover:shadow-md transition duration-300">
                    <div class="space-y-3 sm:space-y-4">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-800">
                            Paket Dasar
                        </h2>
                        <div class="flex items-end gap-2">
                            <p class="text-2xl sm:text-3xl font-bold text-gray-900">Rp 49.000</p>
                            <span class="text-gray-500 text-xs sm:text-sm mb-1">/bulan</span>
                        </div>
                        <ul class="space-y-2 pt-1">
                            <li class="flex items-center gap-2 text-xs sm:text-sm text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>

                                Akses tanpa batas 30 hari
                            </li>
                            <li class="flex items-center gap-2 text-xs sm:text-sm text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>

                                Semua buku premium terbuka
                            </li>
                        </ul>
                    </div>
                </figure>

                {{-- Card Paket Medium --}}
                <figure class="relative p-5 sm:p-7 rounded-2xl bg-white border-2 border-blue-600 shadow-md">
                    <span
                        class="absolute -top-3 right-6 text-xs px-3 py-1 rounded-full
                                 bg-blue-600 text-white font-medium tracking-wide shadow-sm">
                        REKOMENDASI
                    </span>
                    <div class="space-y-3 sm:space-y-4">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-800">
                            Paket Medium
                        </h2>
                        <div class="flex items-end gap-2">
                            <p class="text-2xl sm:text-3xl font-bold text-gray-900">Rp 235.000</p>
                            <span class="text-gray-500 text-xs sm:text-sm mb-1">/6 bulan</span>
                        </div>
                        <ul class="space-y-2 pt-1">
                            <li class="flex items-center gap-2 text-xs sm:text-sm text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>

                                Akses tanpa batas 180 hari
                            </li>
                            <li class="flex items-center gap-2 text-xs sm:text-sm text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>

                                Semua manfaat premium
                            </li>
                        </ul>
                    </div>
                </figure>

                {{-- Card Paket Ultimate --}}
                <figure class="relative p-5 sm:p-7 rounded-2xl bg-white border-2 border-blue-600 shadow-md">
                    <span
                        class="absolute -top-3 right-6 text-xs px-3 py-1 rounded-full
                                 bg-blue-600 text-white font-medium tracking-wide shadow-sm">
                        Hemat 25%
                    </span>
                    <div class="space-y-3 sm:space-y-4">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-800">
                            Paket Ultimate
                        </h2>
                        <div class="flex items-end gap-2">
                            <p class="text-2xl sm:text-3xl font-bold text-gray-900">Rp 450.000</p>
                            <span class="text-gray-500 text-xs sm:text-sm mb-1">/1 Tahun</span>
                        </div>
                        <ul class="space-y-2 pt-1">
                            <li class="flex items-center gap-2 text-xs sm:text-sm text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>

                                Akses tanpa batas 360 hari
                            </li>
                            <li class="flex items-center gap-2 text-xs sm:text-sm text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>

                                Semua manfaat paket premium
                            </li>
                        </ul>
                    </div>
                </figure>

            </div>

            {{-- ===== KOLOM FORM ===== --}}
            <div class="w-full lg:w-1/2">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-8 space-y-5 sm:space-y-6">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900">
                        Transaksi
                    </h3>



                    <form wire:submit="submit">
                        <div class="space-y-4 sm:space-y-5">

                            {{-- Field Nama --}}
                            <div class="flex flex-col gap-1.5 sm:gap-2">
                                <label for="username" class="text-sm font-medium text-gray-700 flex items-center gap-1">
                                    Nama
                                    <button data-popover-target="name-popover" data-popover-placement="top"
                                        type="button"
                                        class="text-body box-border shadow-xs font-medium leading-5 rounded-base text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                                        </svg>
                                    </button>
                                    <div data-popover id="name-popover" role="tooltip"
                                        class="absolute z-10 invisible inline-block w-56 sm:w-64 text-sm text-body
                                           transition-opacity duration-300 bg-neutral-primary-soft border
                                           border-default rounded-base shadow-xs opacity-0">
                                        <div class="px-3 py-2">
                                            <p>Masukan atas nama e-wallet\bank</p>
                                        </div>
                                        <div data-popper-arrow></div>
                                    </div>
                                </label>
                                <input type="text" id="username" wire:model="name" placeholder="Masukkan nama"
                                    class="w-full px-4 py-2.5 sm:py-3 rounded-xl border border-gray-200 bg-gray-50
                                       focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                                       transition duration-200 outline-none text-sm sm:text-base" />
                                @error('name')
                                    <div class="flex items-start px-3 py-2 text-xs sm:text-sm
                                            text-fg-danger-strong rounded-xl bg-danger-soft border border-danger-subtle"
                                        role="alert">
                                        <svg class="w-4 h-4 me-2 shrink-0 mt-0.5" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        <p>{{ $message }}</p>
                                    </div>
                                @enderror
                            </div>

                            {{-- Field Nomor --}}
                            <div class="flex flex-col gap-1.5 sm:gap-2">
                                <label for="nomor"
                                    class="text-sm font-medium text-gray-700 flex items-center gap-1">
                                    Nomor
                                    <button data-popover-target="popover-top" data-popover-placement="top"
                                        type="button"
                                        class="text-body box-border shadow-xs font-medium leading-5 rounded-base text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                                        </svg>
                                    </button>
                                    <div data-popover id="popover-top" role="tooltip"
                                        class="absolute z-10 invisible inline-block w-56 sm:w-64 text-sm text-body
                                           transition-opacity duration-300 bg-neutral-primary-soft border
                                           border-default rounded-base shadow-xs opacity-0">
                                        <div class="px-3 py-2">
                                            <p>Masukan nomor e-wallet\bank</p>
                                        </div>
                                        <div data-popper-arrow></div>
                                    </div>
                                </label>
                                <input type="text" id="nomor" wire:model="number"
                                    placeholder="Masukkan nomor"
                                    class="w-full px-4 py-2.5 sm:py-3 rounded-xl border border-gray-200 bg-gray-50
                                       focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                                       transition duration-200 outline-none text-sm sm:text-base" />
                                @error('number')
                                    <div class="flex items-start px-3 py-2 text-xs sm:text-sm
                                            text-fg-danger-strong rounded-xl bg-danger-soft border border-danger-subtle"
                                        role="alert">
                                        <svg class="w-4 h-4 me-2 shrink-0 mt-0.5" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        <p>{{ $message }}</p>
                                    </div>
                                @enderror
                            </div>

                            {{-- Pilih Paket --}}
                            <div class="flex flex-col gap-1.5 sm:gap-2">
                                <label for="paket" class="text-sm font-medium text-gray-700">
                                    Pilih Paket
                                </label>
                                <select id="paket" wire:model.live="packageSelectedId"
                                    class="w-full px-4 py-2.5 sm:py-3 rounded-xl border border-gray-200 bg-gray-50
                                       focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                                       transition duration-200 outline-none text-sm sm:text-base">
                                    <option value="">-- Pilih Paket --</option>
                                    @foreach ($this->paketLangganan as $paket)
                                        <option value="{{ $paket->id }}">{{ $paket->name }}</option>
                                    @endforeach
                                </select>
                                @error('packageSelectedId')
                                    <div class="flex items-start px-3 py-2 text-xs sm:text-sm
                                            text-fg-danger-strong rounded-xl bg-danger-soft border border-danger-subtle"
                                        role="alert">
                                        <svg class="w-4 h-4 me-2 shrink-0 mt-0.5" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        <p>{{ $message }}</p>
                                    </div>
                                @enderror
                            </div>

                            {{-- Transfer ke --}}
                            <div class="flex flex-col gap-1.5 sm:gap-2">
                                <label class="text-sm font-medium text-gray-700">Transfer ke</label>
                                <div
                                    class="flex flex-col xs:flex-row items-start xs:items-center justify-between
                                        gap-1 bg-gray-100 py-2.5 sm:py-3 px-4 rounded-xl border border-danger-subtle">
                                    <p class="text-xs sm:text-sm text-gray-700 font-medium">
                                        DANA : READIFY STORE
                                    </p>
                                    <p class="text-xs sm:text-sm text-gray-700 font-medium">
                                        +62 1234 5678 9101
                                    </p>
                                </div>
                            </div>

                            {{-- Upload Bukti --}}
                            <div class="flex flex-col gap-1.5 sm:gap-2">
                                <label for="bukti" class="text-sm font-medium text-gray-700">
                                    Upload Bukti Transfer
                                </label>
                                <input type="file" id="bukti" wire:model="payment_proof" x-data
                                    x-on:change="$wire.set('is_uploading', true)"
                                    x-on:livewire-upload-finish="$wire.set('is_uploading', false)"
                                    x-on:livewire-upload-error="$wire.set('is_uploading', false)"
                                    class="w-full px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl border border-dashed
                                       border-gray-300 bg-gray-50 focus:border-blue-600 focus:ring-2
                                       focus:ring-blue-100 transition duration-200 outline-none
                                       text-xs sm:text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg
                                       file:border-0 file:text-xs file:font-medium
                                       file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                                <span x-data x-show="$wire.is_uploading"
                                    class="text-xs text-blue-500 flex items-center gap-1">
                                    <svg class="animate-spin h-3 w-3" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    Mengupload file...
                                </span>
                                @error('payment_proof')
                                    <div class="flex items-start px-3 py-2 text-xs sm:text-sm
                                            text-fg-danger-strong rounded-xl bg-danger-soft border border-danger-subtle"
                                        role="alert">
                                        <svg class="w-4 h-4 me-2 shrink-0 mt-0.5" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        <p>{{ $message }}</p>
                                    </div>
                                @enderror
                            </div>
                            <div class="block lg:hidden bg-gray-50 rounded-2xl p-5 space-y-3 border border-gray-100">
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Paket</span>
                                    <span
                                        class="font-medium text-gray-800">{{ $this->pilihanPaket->name ?? '' }}</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Durasi</span>
                                    <span
                                        class="font-medium text-gray-800">{{ $this->pilihanPaket->duration_days ?? '0' }}
                                        hari</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Harga</span>
                                    <span
                                        class="font-semibold text-gray-900">Rp.{{ number_format($this->pilihanPaket->price ?? 0, 0, 0) }}</span>
                                </div>
                            </div>

                        </div>{{-- end space-y --}}

                        {{-- Submit Button --}}
                        <button wire:loading.attr="disabled" wire:click="submit" x-data
                            x-bind:disabled="$wire.is_uploading"
                            x-bind:class="$wire.is_uploading ? 'opacity-50 cursor-not-allowed' : ''"
                            wire:loading.class="pointer-events-none cursor-not-allowed opacity-60"
                            class="w-full py-2.5 sm:py-3 mt-5 sm:my-4 rounded-xl bg-blue-600 text-white
                               font-semibold text-sm sm:text-base hover:bg-blue-700 active:scale-95
                               transition duration-200 shadow-sm">
                            <span wire:loading class="opacity-50" wire:target="submit, payment_proof">
                                ⏳ Sedang mengupload file
                            </span>
                            <span wire:loading.remove wire:target="submit, payment_proof">Submit</span>
                        </button>

                    </form>
                </div>
            </div>

        </div>

    </section>
    {{-- Toast --}}
    @if (session('sukses'))
        <div x-data="{ show: false }" x-show="show" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4" x-init="setTimeout(() => {
                show = true;
                setTimeout(() => show = false, 3000)
            }, 300)"
            class="fixed top-36 left-1/2 -translate-x-1/2 z-50
        w-[calc(100%-2rem)] sm:w-auto sm:max-w-sm
        flex items-start sm:items-center px-4 py-3 text-sm
        text-fg-success-strong rounded-xl bg-success-soft
        border border-success-subtle shadow-lg"
            role="alert">
            <svg class="w-4 h-4 me-2 shrink-0 mt-0.5 sm:mt-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <p>{{ session('sukses') }}</p>
        </div>
    @endif
</div>
