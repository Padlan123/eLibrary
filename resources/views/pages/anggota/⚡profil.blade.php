<?php

use Livewire\Component;
use App\Models\SubscriptionTransaction;
use Livewire\Attributes\Computed;

new class extends Component {
    #[Computed]
    public function konfirmasi()
    {
        $transaksi = SubscriptionTransaction::where('member_id', auth()->id())->first();
        if (!$transaksi->status == null) {
            switch ($transaksi->status) {
                case 'pending':
                    return $transaksi;
                    break;
                case 'approve':
                    return $transaksi;
                    break;
                case 'rejected':
                    return $transaksi;
                    break;
                default:
                    return null;
                    break;
            }
        } else {
            return null;
        }
    }

    #[computed]
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
                @if ($this->konfirmasi()->status === 'completed')
                    <span class="font-medium">Status:</span> Berlangganan Aktif
                @elseif ($this->konfirmasi()->status === 'rejected')
                    <span class="font-medium">Status:</span> Berlangganan Ditolak
                @elseif ($this->konfirmasi()->status === 'pending')
                    <span class="font-medium">Status:</span> Menunggu Konfirmasi
                @endif
            </div>
        @else
            <div class="p-4 mb-4 text-sm text-fg-success-strong rounded-base bg-success-soft w-64 mx-auto mt-2"
                role="alert">
                <span class="font-medium">Status:</span> Tidak Berlangganan
            </div>
        @endif
    </div>
</div>
