<?php

use Livewire\Component;
use App\Models\SubscriptionTransaction;
use Livewire\Attributes\Computed;

new class extends Component {
    public $id;

    public function mount($id)
    {
        $this->id = $id;
    }

    #[Computed]
    public function invoice()
    {
        return SubscriptionTransaction::where('user_id', auth()->id())
            ->where('id', $this->id)
            ->first();
    }

    public function render()
    {
        return $this->view()->title('Detail Transaksi')->layout('layouts.anggota');
    }
};
?>

<div>
    <div
        class="min-h-screen bg-linear-to-br from-slate-900 via-slate-800 to-slate-900 flex items-center justify-center px-12 py-24 lg:py-12 lg:px-4">

        {{-- Decorative background elements --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-1/4 w-96 h-96 bg-emerald-500/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-80 h-80 bg-teal-400/5 rounded-full blur-3xl"></div>
        </div>

        <div class="relative w-full max-w-lg">
            <div class="flex justify-between">
                <a href="{{ url()->previous() }}"
                    class="inline-flex items-center gap-2 text-emerald-100 hover:text-white text-sm lg:text-base font-semibold mb-4 transition-colors duration-200 group">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    Kembali
                </a>
                <a href="{{ route('anggota.invoice.download', $this->invoice) }}"
                    class="inline-flex items-center gap-2 text-emerald-100 hover:text-white text-sm lg:text-base font-semibold mb-4 transition-colors duration-200 group">
                    Download
                    <span class="group-hover:translate-x-1 transition-transform duration-200">&rarr;</span>
                </a>
            </div>

            {{-- Invoice Card --}}
            <div class="bg-white/3 backdrop-blur-xl border border-white/10 rounded-2xl overflow-hidden shadow-2xl">

                {{-- Header --}}
                <div class="relative bg-linear-to-r from-emerald-500 to-teal-500 px-8 py-6 overflow-hidden">
                    {{-- Decorative circles --}}
                    <div class="absolute -top-6 -right-6 w-32 h-32 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-8 -right-2 w-24 h-24 bg-white/5 rounded-full"></div>

                    <div class="relative flex items-center justify-between">
                        <div>
                            <p
                                class="text-emerald-100 text-2xs lg:text-xs font-medium tracking-wide md:tracking-widest mb-1">
                                INVOICE PEMBAYARAN</p>
                            <h1 class="text-white text-xl lg:text-2xl font-bold tracking-tight">Detail Transaksi</h1>
                        </div>
                        <div class="text-right">
                            <div
                                class="bg-white/20 backdrop-blur-sm rounded-xl p-2 lg:px-4 lg:py-2.5 border border-white/20">
                                <p class="text-white/70 text-2xs tracking-wider mb-0.5">ID TRANSAKSI</p>
                                <p class="text-white font-mono font-semibold text-xs lg:text-sm">
                                    {{ $this->invoice->invoice_number }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status Badge --}}
                <div class="px-5 lg:px-8 py-4 border-b border-white/5 bg-white/2">
                    <div class="flex items-center gap-6 md:justify-between">
                        <span
                            class="inline-flex items-center gap-1.5 bg-emerald-500/15 text-emerald-400 text-xs font-semibold px-3 py-1.5 rounded-full border border-emerald-500/20">
                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                            Pembayaran Berhasil
                        </span>
                        <span class="text-white/30 text-xs md:hidden">·</span>
                        <span class="text-white/60 text-xs">{{ $this->invoice->paid_date->format('d M Y') }}</span>
                    </div>
                </div>

                {{-- Content --}}
                <div class="px-5 lg:px-8 py-6 space-y-6">

                    {{-- Informasi Pengguna --}}
                    <div>
                        <p class="text-white/30 text-2xs uppercase tracking-widest font-semibold mb-3">Informasi
                            Pengguna</p>
                        <div class="bg-white/4 rounded-xl border border-white/[0.07] overflow-hidden">
                            <div class="flex items-center gap-4 px-5 py-4 border-b border-white/5">
                                <div
                                    class="w-9 h-9 bg-linear-to-br from-violet-500 to-purple-600 rounded-full flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white/40 text-2xs tracking-wider">NAMA PENGGUNA</p>
                                    <p class="text-white font-semibold text-sm">{{ $this->invoice->member->username }}
                                    </p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 divide-x divide-white/5">
                                <div class="px-5 py-4">
                                    <p class="text-white/40 text-2xs tracking-wider mb-1">TRANSFER ATAS NAMA</p>
                                    <p class="text-white/90 text-sm font-medium">{{ $this->invoice->name }}</p>
                                </div>
                                <div class="px-5 py-4">
                                    <p class="text-white/40 text-2xs tracking-wider mb-1">NOMOR PENGIRIM
                                    </p>
                                    <p class="text-white/90 font-mono text-sm font-medium">{{ $this->invoice->number }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Informasi Paket --}}
                    <div>
                        <p class="text-white/30 text-2xs tracking-widest font-semibold mb-3">RINCIAN PAKET
                        </p>
                        <div
                            class="bg-white/4 rounded-xl border border-white/[0.07] px-3 lg:px-5 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-9 h-9 bg-linear-to-br from-teal-500 to-emerald-600 rounded-xl flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M20 6h-2.18c.07-.44.18-.88.18-1.36C18 2.06 15.96 0 13.32 0c-1.3 0-2.48.52-3.32 1.36L9 2.36 7.96 1.36C7.12.52 5.96 0 4.64 0 2.04 0 0 2.06 0 4.64 0 5.12.08 5.56.18 6H2C.9 6 0 6.9 0 8v12c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white/40 text-2xs tracking-wider mb-0.5">NAMA PAKET</p>
                                    <p class="text-white font-semibold text-sm">{{ $this->invoice->package->name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-white/40 text-2xs tracking-wider mb-0.5">HARGA PAKET</p>
                                <p class="text-white/90 font-mono text-sm font-medium">
                                    Rp {{ number_format($this->invoice->package->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-8 py-4 bg-white/2 border-t border-white/5 flex items-center justify-between">
                    <p class="text-white/20 text-2xs tracking-wider">TERIMA KASIH ATAS PEMBAYARAN ANDA</p>
                    <div class="flex items-center gap-1.5">
                        <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>
                        <div class="w-1.5 h-1.5 bg-teal-400 rounded-full"></div>
                        <div class="w-1.5 h-1.5 bg-cyan-400 rounded-full"></div>
                    </div>
                </div>

            </div>

            {{-- Shadow glow --}}
            <div
                class="absolute inset-0 -z-10 bg-linear-to-r from-emerald-500/10 to-teal-500/10 rounded-2xl blur-2xl scale-95 translate-y-2">
            </div>

        </div>
    </div>
</div>
