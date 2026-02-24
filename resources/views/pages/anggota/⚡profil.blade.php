<?php

use Livewire\Component;
use App\Models\Transaction;
use Livewire\Attributes\Computed;

new class extends Component {
    #[computed]
    public function konfirmasi()
    {
        $transaksi = Transaction::where('anggota_id', auth()->id())->first();
        switch ($transaksi->status) {
            case 'pending':
                return $transaksi;
                break;
            case 'disetujui':
                return $transaksi;
                break;
            case 'ditolak':
                return $transaksi;
                break;
            default:
                return null;
                break;
        }
        return null;
    }

    public function render()
    {
        return $this->view()->title('Profil')->layout('layouts.anggota');
    }
};
?>

<div>
    <div class="pt-32">
        <h1 class="text-3xl font-bold text-center">Profil Anggota</h1>
        @if ($this->konfirmasi)
            <div class="p-4 mb-4 text-sm text-fg-success-strong rounded-base bg-success-soft w-64 mx-auto mt-2"
                role="alert">
                @if ($this->konfirmasi()->status === 'disetujui')
                    <span class="font-medium">Status:</span> Berlangganan Aktif
                @elseif ($this->konfirmasi()->status === 'ditolak')
                    <span class="font-medium">Status:</span> Berlangganan Ditolak
                @elseif ($this->konfirmasi()->status === 'pending')
                    <span class="font-medium">Status:</span> Menunggu Konfirmasi
                @else
                    <span class="font-medium">Status:</span> Tidak Berlangganan
                @endif
            </div>
        @endif
    </div>
</div>
