<?php

use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\Paket;
use Livewire\WithFileUploads;
use App\Traits\WithTransactionValidation;
use App\Traits\WithFlashMessages;
use App\Models\Transaction;

new class extends Component {
    use WithFileUploads, WithTransactionValidation, WithFlashMessages;

    public $pilihanPaketId = '';
    public $namaPengirim = '';
    public $nomorPengirim = '';
    public $buktiPembayaran;

    #[Computed]
    public function pilihanPaket()
    {
        if (!$this->pilihanPaketId) {
            return null;
        }
        return Paket::find($this->pilihanPaketId);
    }

    #[computed]
    public function paketLangganan()
    {
        return Paket::all();
    }

    public function submit()
    {
        $this->validateTransaction();

        Transaction::create([
            'anggota_id' => auth()->id(),
            'paket_id' => $this->pilihanPaketId,
            'nama_pengirim' => $this->namaPengirim,
            'nomor_pengirim' => $this->nomorPengirim,
            'bukti_pembayaran' => $this->buktiPembayaran->store('bukti-pembayaran', 'public'),
            'tanggal_bayar' => now(),
        ]);

        $this->flashMessage('sukses', 'Berhasil mengajukan berlangganan!', 'anggota.berlangganan');
        $this->reset(['pilihanPaketId', 'namaPengirim', 'nomorPengirim', 'buktiPembayaran']);
    }

    public function render()
    {
        return $this->view()->title('Form Berlangganan');
    }
};
?>

<div>
    <section class="py-8 antialiased bg-gray-900 md:py-32">
        <div class="mx-auto max-w-7xl px-4 2xl:px-0">
            <div class="mx-auto max-w-5xl">
                <h2 class="text-xl font-semibold text-white sm:text-2xl">Berlangganan</h2>
                @if (session('sukses'))
                    <div id="toast-simple"
                        class="flex items-center w-full max-w-sm p-4 text-white bg-gray-700 rounded-base shadow-xs border border-default"
                        role="alert">
                        <div class="inline-flex items-center justify-center shrink-0 size-7 text-green-500 rounded">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5" />
                            </svg>
                            <span class="sr-only">Check icon</span>
                        </div>
                        <div class="ms-2.5 text-sm border-s border-default ps-3.5">{{ session('sukses') }}</div>
                        <button type="button"
                            class="ms-auto flex items-center justify-center text-white hover:text-heading bg-transparent box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded text-sm h-8 w-8 focus:outline-none"
                            data-dismiss-target="#toast-simple" aria-label="Close">
                            <span class="sr-only">Close</span>
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                            </svg>
                        </button>
                    </div>
                @endif
                <div class="mt-6 sm:mt-8 lg:flex lg:items-start lg:gap-12">
                    <form wire:submit="submit" enctype="multipart/form-data"
                        class="w-full rounded-lg border p-4 shadow-sm border-gray-700 bg-gray-800 sm:p-6 lg:max-w-xl lg:p-8">
                        <div class="mb-6 grid grid-cols-2 gap-4">
                            <div class="col-span-2 sm:col-span-1">
                                <label for="nama-pengirim"
                                    class="mb-2 flex items-center gap-1 text-sm font-medium text-white">Nama
                                    Pengirim
                                    <button data-tooltip-target="nama-tooltip" data-tooltip-trigger="hover"
                                        class="text-gray-400 hover:text-gray-900 ">
                                        <svg class="h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm9.408-5.5a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM10 10a1 1 0 1 0 0 2h1v3h-1a1 1 0 1 0 0-2h4a1 1 0 1 0 0-2h-1v-4a1 1
                                                0 0 0-1-1h-2Z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div id="nama-tooltip" role="tooltip"
                                        class="tooltip invisible absolute z-10 inline-block rounded-lg  px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300 bg-gray-700">
                                        Masukkan nama lengkap pengirim (E-wallet/Bank)
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                </label>
                                <input wire:model="namaPengirim" type="text" id="nama-pengirim"
                                    class="block w-full rounded-lg border p-2.5 pe-10 text-sm border-gray-600 bg-gray-700 text-white placeholder:text-gray-400 "
                                    placeholder="Bonnie Green" />
                                @error('namaPengirim')
                                    <p class="mt-1 text-sm text-red-500"><span
                                            class="font-medium">{{ $message }}</span></p>
                                @enderror
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="nomor-pengirim"
                                    class="mb-2 flex items-center gap-1 text-sm font-medium  text-white">Nomor
                                    Pengirim

                                    <button data-tooltip-target="nomor-tooltip" data-tooltip-trigger="hover"
                                        class=" text-gray-500 hover:text-white">
                                        <svg class="h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm9.408-5.5a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM10 10a1 1 0 1 0 0 2h1v3h-1a1 1 0 1 0 0-2h4a1 1 0 1 0 0-2h-1v-4a1 1
                                                0 0 0-1-1h-2Z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div id="nomor-tooltip" role="tooltip"
                                        class="tooltip invisible absolute z-10 inline-block rounded-lg px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300 bg-gray-700">
                                        Masukkan nomor pengirim (E-wallet/Bank)
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                </label>
                                <input wire:model="nomorPengirim" type="text" id="nomor-pengirim"
                                    class="block w-full rounded-lg border p-2.5 pe-10 text-sm border-gray-600 bg-gray-700 text-white placeholder:text-gray-400 "
                                    placeholder="xxxx-xxxx-xxxx-xxxx" />
                                @error('nomorPengirim')
                                    <p class="mt-1 text-sm text-red-500"><span
                                            class="font-medium">{{ $message }}</span></p>
                                @enderror
                            </div>

                            <div>
                                <label for="paket"
                                    class="mb-2 flex items-center gap-1 text-sm font-medium text-white">Pilih
                                    Paket
                                    <button data-tooltip-target="paket-tooltip" data-tooltip-trigger="hover"
                                        class="text-gray-500 hover:text-white">
                                        <svg class="h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm9.408-5.5a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM10 10a1 1 0 1 0 0 2h1v3h-1a1 1 0 1 0 0-2h4a1 1 0 1 0 0-2h-1v-4a1 1
                                                0 0 0-1-1h-2Z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div id="paket-tooltip" role="tooltip"
                                        class="tooltip invisible absolute z-10 inline-block rounded-lg px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300 bg-gray-700">
                                        Pilih paket langganan yang sesuai dengan kebutuhan Anda
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                </label>
                                <div class="relative">
                                    <select wire:model.live.debounce="pilihanPaketId" id="paket"
                                        class="block w-full rounded-lg border p-2.5 pe-10 text-sm focus:border-primary-500 focus:ring-primary-500  border-gray-600 bg-gray-700 text-white placeholder:text-gray-400 focus:border-primary-500 focus:ring-primary-500">
                                        <option>pilih paket</option>
                                        @foreach ($this->paketLangganan as $paket)
                                            <option value="{{ $paket->id }}">{{ $paket->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pilihanPaketId')
                                        <p class="mt-1 text-sm text-red-500"><span
                                                class="font-medium">{{ $message }}</span></p>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <label for="bukti-pembayaran"
                                    class="mb-2 flex items-center gap-1 text-sm font-medium  text-white">
                                    Bukti Pembayaran
                                    <button data-tooltip-target="bukti-tooltip" data-tooltip-trigger="hover"
                                        class=" text-gray-500 hover:text-white">
                                        <svg class="h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm9.408-5.5a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM10 10a1 1 0 1 0 0 2h1v3h-1a1 1 0 1 0 0 2h4a1 1 0 1 0 0-2h-1v-4a1 1 0 0 0-1-1h-2Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div id="bukti-tooltip" role="tooltip"
                                        class="tooltip invisible absolute z-10 inline-block rounded-lg  px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300 bg-gray-700">
                                        Unggah bukti pembayaran Anda untuk memverifikasi transaksi
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                </label>
                                <input wire:model="buktiPembayaran" type="file" id="bukti-pembayaran"
                                    class="hidden"
                                    @change="fileName = $event.target.files[0] ? $event.target.files[0].name : 'Tidak ada file dipilih'" />
                                <label for="bukti-pembayaran"
                                    class="flex items-center cursor-pointer border border-gray-600 bg-gray-700 rounded-base w-full shadow-xs overflow-hidden">
                                    {{-- Tombol Pilih File --}}
                                    <span
                                        class="bg-gray-800 hover:bg-gray-600 text-white text-sm px-4 py-2 whitespace-nowrap transition-colors">
                                        Pilih File
                                    </span>

                                    @if ($this->buktiPembayaran)
                                        <span x-text="fileName" class="text-gray-400 text-sm px-3 truncate"></span>
                                    @else
                                        <span class="text-gray-400 text-sm px-3">Tidak ada file dipilih</span>
                                    @endif
                                </label>
                                @error('buktiPembayaran')
                                    <p class="mt-1 text-sm text-red-500"><span
                                            class="font-medium">{{ $message }}</span></p>
                                @enderror
                            </div>
                        </div>

                        <button wire:loading.attr="disabled" wire:target="buktiPembayaran" type="submit"
                            class="w-full text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                            <span wire:loading class="opacity-50" wire:target="buktiPembayaran">⏳ Sedang mengupload
                                file, harap
                                tunggu...</span>
                            <span wire:loading.remove wire:target="buktiPembayaran">Submit</span>
                        </button>
                    </form>

                    <div class="mt-6 grow sm:mt-8 lg:mt-0">
                        <div class="space-y-4 rounded-lg border  p-6 border-gray-700 bg-gray-800">
                            <div class="space-y-2">
                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="text-base font-normal text-gray-400">paket langganan
                                    </dt>
                                    <dd class="text-base font-medium  text-white">
                                        {{ $this->pilihanPaket->nama ?? '-' }}</dd>
                                    </dd>
                                </dl>
                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="text-base font-normal text-gray-400">Durasi Paket
                                    </dt>
                                    <dd class="text-base font-medium text-white">
                                        {{ $this->pilihanPaket->durasi ?? 0 }} Hari</dd>
                                </dl>
                            </div>

                            <dl class="flex items-center justify-between gap-4 border-t border-gray-700 pt-4">
                                <dt class="text-base font-normal text-gray-400">Harga Paket</dt>
                                <dd class="text-base font-medium text-green-500">Rp.
                                    {{ number_format($this->pilihanPaket->harga ?? 0, 0, ',', '.') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @livewire('footer_kecil')
</div>
