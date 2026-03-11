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
        do {
            $invoice = 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (SubscriptionTransaction::where('invoice_number', $invoice)->exists());

        return $invoice;
    }

    public function submit()
    {
        if ($this->is_uploading) {
            return;
        }

        $this->validateTransaction();

        if (!$this->payment_proof || !$this->payment_proof->isValid()) {
            $this->addError('payment_proof', 'File tidak valid, silakan upload ulang.');
            return;
        }

        // dd($this->packageSelectedId, $this->name, $this->number, $this->payment_proof);

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
    <section class="bg-linear-to-br from-gray-50 to-gray-100 min-h-screen pt-4">
        <div class="py-10 md:py-20 px-6 md:px-12 flex flex-col md:flex-row gap-10 max-w-7xl mx-auto">
            <div class="flex flex-col gap-6 w-full md:w-[55%]">
                <!-- Card Premium -->
                <figure
                    class="p-7 rounded-3xl bg-white border border-gray-100 shadow-sm hover:shadow-md transition duration-300">
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-800">
                            Paket Premium 1 Bulan
                        </h2>

                        <div class="flex items-end gap-2">
                            <p class="text-3xl font-bold text-gray-900">Rp 49.000</p>
                            <span class="text-gray-500 text-sm">/bulan</span>
                        </div>

                        <ul class="space-y-2 pt-2">
                            <li class="flex items-center gap-2 text-sm text-gray-600">
                                <ion-icon name="checkmark-outline" class="text-blue-600"></ion-icon>
                                Akses tanpa batas 30 hari
                            </li>
                            <li class="flex items-center gap-2 text-sm text-gray-600">
                                <ion-icon name="checkmark-outline" class="text-blue-600"></ion-icon>
                                Semua buku premium terbuka
                            </li>
                        </ul>
                    </div>
                </figure>

                <!-- Card Premium -->
                <figure class="relative p-7 rounded-3xl bg-white border-2 border-blue-600 shadow-md">
                    <span
                        class="absolute -top-3 right-8 text-xs px-4 py-1 rounded-full bg-blue-600 text-white font-medium tracking-wide shadow-sm">
                        REKOMENDASI
                    </span>

                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-800">
                            Paket Premium 6 Bulan
                        </h2>

                        <div class="flex items-end gap-2">
                            <p class="text-3xl font-bold text-gray-900">Rp 235.000</p>
                            <span class="text-gray-500 text-sm">/6 bulan</span>
                        </div>

                        <ul class="space-y-2 pt-2">
                            <li class="flex items-center gap-2 text-sm text-gray-600">
                                <ion-icon name="checkmark-outline" class="text-blue-600"></ion-icon>
                                Akses tanpa batas 180 hari
                            </li>
                            <li class="flex items-center gap-2 text-sm text-gray-600">
                                <ion-icon name="checkmark-outline" class="text-blue-600"></ion-icon>
                                Semua manfaat premium
                            </li>
                        </ul>
                    </div>
                </figure>
                <!-- Card Premium -->
                <figure class="relative p-7 rounded-3xl bg-white border-2 border-blue-600 shadow-md">
                    <span
                        class="absolute -top-3 right-8 text-xs px-4 py-1 rounded-full bg-blue-600 text-white font-medium tracking-wide shadow-sm">
                        Hemat 25%
                    </span>

                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-800">
                            Paket Premium 1 Tahun
                        </h2>

                        <div class="flex items-end gap-2">
                            <p class="text-3xl font-bold text-gray-900">Rp 235.000</p>
                            <span class="text-gray-500 text-sm">/1 Tahun</span>
                        </div>

                        <ul class="space-y-2 pt-2">
                            <li class="flex items-center gap-2 text-sm text-gray-600">
                                <ion-icon name="checkmark-outline" class="text-blue-600"></ion-icon>
                                Akses tanpa batas 12 bulan
                            </li>
                            <li class="flex items-center gap-2 text-sm text-gray-600">
                                <ion-icon name="checkmark-outline" class="text-blue-600"></ion-icon>
                                Semua manfaat paket premium
                            </li>
                        </ul>
                    </div>
                </figure>
            </div>

            <div class="w-full md:w-1/2">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8 space-y-6">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Transaksi
                    </h3>
                    @if (session('sukses'))
                        <div class="flex items-start sm:items-center p-4 mb-4 text-sm text-fg-success-strong rounded-base bg-success-soft border border-success-subtle"
                            role="alert">
                            <svg class="w-4 h-4 me-2 shrink-0 mt-0.5 sm:mt-0" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <p>{{ session('sukses') }}</p>
                        </div>
                    @endif
                    <form wire:submit="submit">
                        <div class="space-y-5">
                            <div class="flex flex-col gap-2">
                                <label for="username" class="text-sm font-medium text-gray-700 flex items-center gap-1">
                                    Nama <button data-popover-target="name-popover" data-popover-placement="top"
                                        type="button"
                                        class="text-body box-border shadow-xs font-medium leading-5 rounded-base text-sm "><svg
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                                        </svg>
                                    </button>

                                    <div data-popover id="name-popover" role="tooltip"
                                        class="absolute z-10 invisible inline-block w-64 text-sm text-body transition-opacity duration-300 bg-neutral-primary-soft border border-default rounded-base shadow-xs opacity-0">
                                        <div class="px-3 py-2">
                                            <p>Masukan nama e-wallet\bank</p>
                                        </div>
                                        <div data-popper-arrow></div>
                                    </div>
                                </label>
                                </label>
                                <input type="text" id="username" wire:model="name" placeholder="Masukkan nama"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition duration-200 outline-none" />
                                @error('name')
                                    <div class="flex items-start sm:items-center px-3 py-2 mb-4 text-sm text-fg-danger-strong rounded-base bg-danger-soft border border-danger-subtle"
                                        role="alert">
                                        <svg class="w-4 h-4 me-2 shrink-0 mt-0.5 sm:mt-0" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                            viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        <p>{{ $message }}</p>
                                    </div>
                                @enderror
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="email"
                                    class="text-sm font-medium text-gray-700 flex items-center gap-1">
                                    Nomor <button data-popover-target="popover-top" data-popover-placement="top"
                                        type="button"
                                        class="text-body box-border shadow-xs font-medium leading-5 rounded-base text-sm "><svg
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                                        </svg>
                                    </button>

                                    <div data-popover id="popover-top" role="tooltip"
                                        class="absolute z-10 invisible inline-block w-64 text-sm text-body transition-opacity duration-300 bg-neutral-primary-soft border border-default rounded-base shadow-xs opacity-0">
                                        <div class="px-3 py-2">
                                            <p>Masukan nomor e-wallet\bank</p>
                                        </div>
                                        <div data-popper-arrow></div>
                                    </div>
                                </label>
                                <input type="text" id="email" wire:model="number"
                                    placeholder="Masukkan nomor"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition duration-200 outline-none" />
                                @error('number')
                                    <div class="flex items-start sm:items-center px-3 py-2 mb-4 text-sm text-fg-danger-strong rounded-base bg-danger-soft border border-danger-subtle"
                                        role="alert">
                                        <svg class="w-4 h-4 me-2 shrink-0 mt-0.5 sm:mt-0" aria-hidden="true"
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

                            <div class="flex flex-col gap-2">
                                <label for="paket" class="text-sm font-medium text-gray-700">
                                    Pilih Paket
                                </label>
                                <select id="paket" wire:model.live="packageSelectedId"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition duration-200 outline-none">
                                    <option value="">-- Pilih Paket --</option>
                                    @foreach ($this->paketLangganan as $paket)
                                        <option value="{{ $paket->id }}">{{ $paket->name }}</option>
                                    @endforeach
                                </select>
                                @error('packageSelectedId')
                                    <div class="flex items-start sm:items-center px-3 py-2 mb-4 text-sm text-fg-danger-strong rounded-base bg-danger-soft border border-danger-subtle"
                                        role="alert">
                                        <svg class="w-4 h-4 me-2 shrink-0 mt-0.5 sm:mt-0" aria-hidden="true"
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

                            <div class="flex flex-col gap-2">
                                <label for="bukti" class="text-sm font-medium text-gray-700">
                                    Upload Bukti Transfer
                                </label>
                                <input type="file" id="bukti" wire:model="payment_proof" x-data
                                    x-on:change="$wire.set('is_uploading', true)"
                                    x-on:livewire-upload-finish="$wire.set('is_uploading', false)"
                                    x-on:livewire-upload-error="$wire.set('is_uploading', false)"
                                    class="w-full px-4 py-3 rounded-xl border border-dashed border-gray-300 bg-gray-50 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition duration-200 outline-none" />
                                <span x-data x-show="$wire.is_uploading"
                                    class="text-xs text-blue-500 flex items-center gap-1">
                                    <svg class="animate-spin h-3 w-3" ...></svg>
                                    Mengupload file...
                                </span>
                                @error('payment_proof')
                                    <div class="flex items-start sm:items-center px-3 py-2 mb-4 text-sm text-fg-danger-strong rounded-base bg-danger-soft border border-danger-subtle"
                                        role="alert">
                                        <svg class="w-4 h-4 me-2 shrink-0 mt-0.5 sm:mt-0" aria-hidden="true"
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
                        </div>

                        <div class="bg-gray-50 rounded-2xl p-5 space-y-3 border border-gray-100">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Paket</span>
                                <span class="font-medium text-gray-800">{{ $this->pilihanPaket->name ?? '' }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Harga</span>
                                <span
                                    class="font-semibold text-gray-900">Rp.{{ number_format($this->pilihanPaket->price ?? 0, 0, 0) }}</span>
                            </div>
                        </div>

                        <button wire:loading.attr="disabled" wire:click="submit" x-data
                            x-bind:disabled="$wire.is_uploading"
                            x-bind:class="$wire.is_uploading ? 'opacity-50 cursor-not-allowed' : ''"
                            wire:loading.class="pointer-events-none cursor-not-allowed opacity-60"
                            class="w-full py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 active:scale-95 transition duration-200 shadow-sm">
                            <span wire:loading class="opacity-50" wire:target="submit, payment_proof">⏳
                                Sedang
                                mengupload
                                file</span>
                            <span wire:loading.remove wire:target="submit, payment_proof">Submit</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <x-footer></x-footer>
</div>
