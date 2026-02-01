<?php

use Livewire\Component;

new class extends Component {
    public function render()
    {
        return $this->view()->title('Login');
    }
};
?>

<div
    class="min-h-screen bg-linear-to-br from-orange-100 via-blue-400 to-blue-600 flex items-center justify-center font-sans">
    <section class="min-h-screen w-full flex items-center justify-center px-4">
        <div class="bg-white/30 backdrop-blur-lg p-8 rounded-2xl shadow-2xl w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-orange-50 bg-clip-text mb-2">
                    KOMIKPAGE
                </h1>
                <p class="text-gray-600">Masuk ke akun Anda</p>
            </div>
            <form class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email"
                        class="w-full bg-orange-50 py-3 px-4 border-2 rounded-lg border-blue-500 focus:border-blue-300 focus:ring-1 focus:ring-blue-200 focus:outline-none transition duration-300"
                        placeholder="Masukkan email Anda" />
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full bg-orange-50 py-3 px-4 border-2 rounded-lg border-blue-500 focus:border-blue-300 focus:ring-1 focus:ring-blue-200 focus:outline-none transition duration-300"
                        placeholder="Masukkan password Anda" />
                </div>

                <button type="submit"
                    class="w-full py-3 px-4 bg-linear-to-r from-blue-500 to-blue-700 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-105 transition duration-300">
                    Masuk
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Belum punya akun?
                    <a wire:navigate href="{{ route('register') }}"
                        class="text-blue-600 hover:text-blue-500 font-medium">Daftar
                        sekarang</a>
                </p>
            </div>
        </div>
    </section>
</div>
