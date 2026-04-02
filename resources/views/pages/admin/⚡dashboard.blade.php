<?php

use Livewire\Component;
use App\Models\User;
use App\Models\MemberSubscription;
use App\Models\SubscriptionTransaction;
use Livewire\Attributes\Computed;

new class extends Component {
    #[Computed]
    public function subscribes()
    {
        return MemberSubscription::active()->get();
    }

    #[Computed]
    public function profit()
    {
        $activeUserIds = $this->subscribes->pluck('user_id')->unique();

        return SubscriptionTransaction::where('status', 'completed')->whereIn('user_id', $activeUserIds)->sum('amount');
    }

    #[Computed]
    public function users()
    {
        return User::role('anggota')->latest()->get();
    }

    public function render()
    {
        return $this->view()->layout('layouts.admin', ['title' => 'Dashboard']);
    }
};
?>

<div>

    <!-- CARDS -->
    <div class="w-full mb-6">


        <div class="bg-linear-to-r w-full from-green-400 to-emerald-600 text-white p-6 md:p-8 rounded-2xl shadow-lg ">
            <p class="opacity-80 lg:text-base">Total Profit Langganan</p>
            <h3 class="text-2xl font-bold mt-2 lg:text-3xl">Rp {{ number_format($this->profit(), 0, ',', '.') }}</h3>
        </div>

    </div>
    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-linear-to-r w-full from-indigo-500 to-indigo-600 text-white p-6 md:p-8 rounded-2xl shadow-lg ">
            <p class="opacity-80 lg:text-base">Total Pengguna</p>
            <h3 class="text-2xl font-bold mt-2 lg:text-3xl">{{ $this->users->count() }}</h3>
        </div>
        <div class="bg-linear-to-r from-cyan-400 to-cyan-600 text-white p-6 md:p-8 rounded-2xl shadow-lg ">
            <p class="opacity-80 text-sm md:text-base">
                Total pengguna berlangganan
            </p>
            <h3 class="text-3xl md:text-4xl font-bold mt-2">{{ $this->subscribes->count() }}</h3>
        </div>
    </div>
</div>
