<?php

use Livewire\Component;
use App\Models\Transaction;
use Livewire\Attributes\Computed;

new class extends Component {
    #[computed]
    public function konfirmasi()
    {
        return Transaction::where('anggota_id', auth()->id())->first();
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
                <span class="font-medium">Konfirmasi berlangganan</span> {{ $this->konfirmasi()->status }}
            </div>
        @endif
    </div>
</div>
